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
            if( empty($arParams['SHOW_RESULT_ON_TIME']) ) {
                $arParams['SHOW_RESULT_ON_TIME'] = '00:00';
            }
            if( empty($arParams['SHOW_RESULT_AFTER']) ) {
                $arParams['SHOW_RESULT_AFTER'] = 1800;
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
                throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_USER'), 1);
            }

            $componentId = Context::getCurrent()->getRequest()->getPost('COMPONENT_ID');
            if( empty($componentId) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT'), 2);
            }
            $arParams = !empty($_SESSION[$componentId]) ? $_SESSION[$componentId] : [];
            if( empty($arParams) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT'), 3);
            }

            $questionId = Context::getCurrent()->getRequest()->getPost('QUESTION_ID');
            $answerString = Context::getCurrent()->getRequest()->getPost('ANSWER');

            $quiz = new \Outcode\Quiz($arParams["API_PATH"]);
            $question = $quiz->getQuestionByTime(time());
            if( !empty($question) || $questionId != $question['ID'] ) {
                if( empty($answerString) ) {
                    throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_EMPTY'), 5);
                }

                $resultArr['result'] = $quiz->addResult($questionId, $answerString, true);

                /*Скрываем от пользователя реальный резльтат*/
                if( isset($resultArr['result']['RESULT']) ) {
                    $resultArr['result']['RESULT'] =  $resultArr['result']['RESULT'] > 0 ? 1 : 0;
                }
                /*Скрываем от пользователя реальный резльтат*/

                if( empty($resultArr['result']) ) throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_COMPONENT'), 7);

            } else {
                throw new Exception(Loc::getMessage('CANT_QUIZ_ANSWER_TIME'), 4);
            }

            return $resultArr;
        }

        public function executeComponent() {
            $this->arResult['COMPONENT_ID'] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
            $this->arResult['COMPONENT_NAME'] = $this->GetName();

            $_SESSION[$this->arResult['COMPONENT_ID']] = $this->arParams;

            global $USER;

            $showStartTime = strtotime(date('d-m-Y ' . $this->arParams["SHOW_RESULT_ON_TIME"]));

            $startDateAll = 315532800;
            $endDateAll = strtotime(date('d-m-Y 23:59:59'));

            $startDateWeek = strtotime( date('d-m-Y', strtotime('-1 week')) ); //315532800; FOR ALL RESULTS

            $currentTime = time();

            $cacheTime = !empty($this->arParams['CACHE_TIME']) && !empty($this->arParams['CACHE_TYPE']) && $this->arParams['CACHE_TYPE'] == 'A' ? $this->arParams['CACHE_TIME'] : 0;
            $cacheHash = 'QuizComponent' . SITE_ID . date('l');
            $cacheDir = '/outcode/quiz';

            $quiz = new \Outcode\Quiz($this->arParams["API_PATH"]);

            $this->arResult['RESULT_TABLE'] = [];
            $this->arResult['RESULT_TABLE_USER'] = $quiz->getUserResults($startDateWeek, ($currentTime - intval($this->arParams['SHOW_RESULT_AFTER'])));

            if( $USER->IsAuthorized() ) {
                $obCache = new CPHPCache();
                if($obCache->InitCache($cacheTime, $cacheHash, $cacheDir)) {
                    $this->arResult['RESULT_TABLE'] = $obCache->GetVars();
                } else {
                    $obCache->StartDataCache();

                    $isShowAlways = !empty($this->arParams['SHOW_RESULTS_ON_LAST_ALWAYS']) && $this->arParams['SHOW_RESULTS_ON_LAST_ALWAYS'] == 'Y';
                    if( $isShowAlways || ( $showStartTime < $currentTime) ) {
                        $this->arResult['RESULT_TABLE'] = $isShowAlways ? $quiz->getAllResults($startDateAll, $endDateAll) : $quiz->getAllResults($startDateWeek, ($currentTime - intval($this->arParams['SHOW_RESULT_AFTER'])));
                    }

                    $obCache->EndDataCache($this->arResult['RESULT_TABLE']);
                }
            }

            /* Получаем вопрос */
            $this->arResult['QUESTION'] = !empty($USER->GetEmail()) ? $quiz->getQuestionByTime(time()) : [];
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
                $this->arResult['QUESTION']['POSITION'] = $quiz->getQuestionPosition($startDateWeek, $currentTime, $this->arResult['QUESTION']['ID']);
            }
            /* Получаем вопрос */

            $uid = $quiz->getUserUid();
            $this->arResult['LINK_LOGIN'] = $USER->IsAuthorized() && !empty($USER->GetEmail()) ? '' : $this->arParams['PERSONAL_PATH'];
            $this->arResult['EMAIL_WARNING'] = empty($USER->GetEmail());
            //$this->arResult['LINK_GET_BONUS'] = !empty($uid) ? $this->arParams['PERSONAL_PATH'] . '?bonusid=' . $uid : '';
            $this->arResult['USER_ID'] = $USER->IsAuthorized() ? $USER->GetID() : 0;

			
            $this->includeComponentTemplate();
        }
    }