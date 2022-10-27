<?
    use Bitrix\Main\Context;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\Result;
    use Bitrix\Main\Engine\Contract\Controllerable;
    use Bitrix\Main\Engine\ActionFilter;
    
    class QuizComponent extends CBitrixComponent implements Controllerable {

        /**
    	* @var ErrorCollection
    	*/
    	public $errorCollection;
		
		/**
         * CustomOrder constructor.
         * @param CBitrixComponent|null $component
         * @throws Bitrix\Main\LoaderException
        */
        public function __construct(CBitrixComponent $component = null) {
            parent::__construct($component);
            
            if( !Loader::includeModule('outcode.quiz') ) {
                throw new RuntimeException(Loc::getMessage('CANT_FIND_QUIZ_MODULE'));
            }
			
			$this->errorCollection = [];
        }

        public function onIncludeComponentLang() {
            Loc::loadLanguageFile(__FILE__);
        }

        public function onPrepareComponentParams($arParams = []): array {
            global $USER;
			
			if( $USER->IsAuthorized() ) {
				$arParams['USER_ID'] = $USER->GetID();
			}

			if( empty($arParams['API_PATH']) ) {
                $arParams['API_PATH'] = Utils::getApiURL();
            }
            if( !isset($arParams['PERSONAL_PATH']) ) {
                $arParams['PERSONAL_PATH'] = '';
            }
			
			return $arParams;
        }

        public function ConfigureActions() {
            return [
                'sendAnswer'=>[
                    'prefilters' => [
                        new ActionFilter\HttpMethod(
                            array(ActionFilter\HttpMethod::METHOD_POST)
                        ),
                        new ActionFilter\Csrf(),
                    ],
                    'postfilters' => []
                ],
            ];
        }

        public function sendAnswerAction() {
            $resultArr = ['result' => [], 'error' => ''];
            if( empty($this->arParams["USER_ID"]) ) {
                $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_USER');
                return $resultArr;
            }

            $componentId = Context::getCurrent()->getRequest()->getPost('COMPONENT_ID');
            if( empty($componentId) ) {
                $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT');
                return $resultArr;
            }
            $arParams = !empty($_SESSION[$componentId]) ? $_SESSION[$componentId] : [];
            if( empty($arParams) ) {
                $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT');
                return $resultArr;
            }

            if( !empty($arParams['SHOW_QUESTION_ON_TIME']) && is_array($arParams['SHOW_QUESTION_ON_TIME']) ) {
                if (in_array(date('h'), $arParams['SHOW_QUESTION_ON_TIME'])) {
                    $questionId = Context::getCurrent()->getRequest()->getPost('QUESTION_ID');
                    $answerString = Context::getCurrent()->getRequest()->getPost('ANSWER');

                    if( empty($answerString) ) {
                        $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_EMPTY');
                        return $resultArr;
                    }

                    $quiz = new \Outcode\Quiz($this->arParams["API_PATH"]);
                    $resultArr['result'] = $quiz->addResult($questionId, $answerString);

                    if( empty($resultArr['result']) ) $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT');
                }
            } else {
                $resultArr['error'] = Loc::getMessage('CANT_QUIZ_ANSWER_TIME');
            }

            return $resultArr;
        }

        public function executeComponent() {
            $this->arResult['COMPONENT_ID'] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
            $this->arResult['COMPONENT_NAME'] = $this->GetName();

            $_SESSION[$this->arResult['COMPONENT_ID']] = $this->arParams;

            global $USER;

            $date = new DateTime('now');
            $date->modify('last day of this month');
            $lastDayOfMonth = $date->format('d');
            $dayOfWeek = date('l');
            $startDateWeek = strtotime( date('d-m-Y', strtotime('-1 week')) );
            $startDateMonth = strtotime( date('d-m-Y', strtotime('-1 month')) );
            //$startDateWeek = 315532800;
            //$startDateMonth = 315532800;
            $currentTime = time();

            $cacheTime = !empty($this->arParams['CACHE_TIME']) && !empty($this->arParams['CACHE_TYPE']) && $this->arParams['CACHE_TYPE'] == 'A' ? $this->arParams['CACHE_TIME'] : 0;
            $cacheHash = 'QuizComponent' . SITE_ID . $dayOfWeek;
            $cacheDir = '/outcode/quiz';

            $quiz = new \Outcode\Quiz($this->arParams["API_PATH"]);

            $this->arResult['RESULT_TABLE_WEEK'] = [];
            $this->arResult['RESULT_TABLE_MONTH'] = [];
            $this->arResult['RESULT_TABLE_USER'] = $quiz->getUserResults($startDateWeek, time());

            $obCache = new CPHPCache();
            if($obCache->InitCache($cacheTime, $cacheHash, $cacheDir)) {
                $cacheVars = $obCache->GetVars();

                $this->arResult["RESULT_TABLE_WEEK"] = $cacheVars['RESULT_TABLE_WEEK'];
                $this->arResult["RESULT_TABLE_MONTH"] = $cacheVars['RESULT_TABLE_MONTH'];

                unset($cacheVars);
            } else {
                $obCache->StartDataCache();

                $cacheVars = ['RESULT_TABLE_WEEK' => [], 'RESULT_TABLE_MONTH' => []];

                if( $dayOfWeek == $this->arParams['SHOW_RESULTS_DAY'] ) {
                    //$cacheVars['RESULT_TABLE_WEEK'] = $quiz->getAllResults($startDateWeek, strtotime(date('d-m-Y')));
                    $cacheVars['RESULT_TABLE_WEEK'] = $quiz->getAllResults($startDateWeek, time());
                }

                if( ( !empty($this->arParams['SHOW_RESULTS_ON_LAST']) && $this->arParams['SHOW_RESULTS_ON_LAST'] == 'Y'
                    && $lastDayOfMonth == date('d') )
                    || (!empty($this->arParams['SHOW_RESULTS_ON_LAST_ALWAYS']) && $this->arParams['SHOW_RESULTS_ON_LAST_ALWAYS'] == 'Y') ) {
                    $cacheVars['RESULT_TABLE_MONTH'] = $quiz->getAllResults($startDateMonth, strtotime(date('d-m-Y')));
                }

                $this->arResult['RESULT_TABLE_WEEK'] = $cacheVars['RESULT_TABLE_WEEK'];
                $this->arResult['RESULT_TABLE_MONTH'] = $cacheVars["RESULT_TABLE_MONTH"];

                $obCache->EndDataCache($cacheVars);
            }

            /* Получаем вопрос */
            $this->arResult['QUESTION'] = [];
            if( !empty($this->arParams['SHOW_QUESTION_ON_TIME']) && is_array($this->arParams['SHOW_QUESTION_ON_TIME']) ) {
                if( in_array(date('h'), $this->arParams['SHOW_QUESTION_ON_TIME']) ) {
                    $interval = intval($this->arParams['SHOW_QUESTION_INTERVAL']);
                    $this->arResult['QUESTION'] = $quiz->getQuestionByTime($currentTime, $currentTime + $interval);
                }
            }
            if( !empty($this->arResult['QUESTION']['PROPERTIES']['ANSWERS_STRING']['VALUE']) ) {
                foreach($this->arResult['QUESTION']['PROPERTIES']['ANSWERS_STRING']['VALUE'] as &$strValue) {
                    $strValue = str_replace('#', '', $strValue);
                }
                unset($strValue);
            }
            if( !empty($this->arResult['QUESTION']['ID']) ) {
                $this->arResult['QUESTION']['IS_ANSWERED'] = $quiz->isQuestionAnswered($this->arResult['QUESTION']['ID']);
                if( $this->arResult['QUESTION']['IS_ANSWERED'] ) {
                    $this->arResult['QUESTION']['IS_ANSWERED_SCORE'] = $quiz->getQuestionScore($this->arResult['QUESTION']['ID']);
                }
            }
            /* Получаем вопрос */

            $uid = $quiz->getUserUid();
            $this->arResult['LINK_LOGIN'] = $USER->IsAuthorized() ? '' : $this->arParams['PERSONAL_PATH'] . '?type=play';
            $this->arResult['LINK_GET_BONUS'] = !empty($uid) ? $this->arParams['PERSONAL_PATH'] . '?type=play&uid=' . $uid : '';
            $this->arResult['USER_ID'] = $USER->IsAuthorized() ? $USER->GetID() : 0;

			
            $this->includeComponentTemplate();
        }
    }