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

			if( empty($arParams['SHOW_RESULT_ON_DAYS']) ) {
                $arParams['SHOW_RESULT_ON_DAYS'] = ['Friday', 'Saturday', 'Sunday'];
            }

            if( empty($arParams['SHOW_RESULT_ON_FIRST']) ) {
                $arParams['SHOW_RESULT_ON_FIRST'] = '22:00:00';
            }

            if( empty($arParams['CALCULATE_FULL_RESULT']) || $arParams['CALCULATE_FULL_RESULT'] == 'N' ) {
                $arParams['CALCULATE_FULL_RESULT'] = false;
            } else {
                $arParams['CALCULATE_FULL_RESULT'] = true;
            }

            if( !isset($arParams['LIMIT']) ) {
                $arParams['LIMIT'] = 10;
            } else {
                $arParams['LIMIT'] = intval($arParams['LIMIT']);
            }
			
			return $arParams;
        }

        public function ConfigureActions() {
            return [
                'selectPrize'=>[
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

        public function selectPrizeAction() {

            if( empty($this->arParams["USER_ID"]) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_PRIZE_COMPONENT'), 1);
            }

            $componentId = Context::getCurrent()->getRequest()->getPost('componentId');
            if( empty($componentId) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_PRIZE_COMPONENT'), 2);
            }

            $arParams = !empty($_SESSION[$componentId]) ? $_SESSION[$componentId] : [];
            if( empty($arParams) || empty($arParams['PROPERTY_NUM']) || empty($arParams['PROPERTY_MSG']) || empty($arParams['IS_ENABLED']) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_PRIZE_COMPONENT'), 3);
            }

            $elementId = Context::getCurrent()->getRequest()->getPost('elementId');
            if( empty($elementId) ) {
                throw new Exception(Loc::getMessage('CANT_QUIZ_PRIZE_COMPONENT'), 4);
            }

            $prizeObj = new \Outcode\Prize();

            if( $arParams['IS_ENABLED'] ) {
                return $prizeObj->selectPrize($elementId, $arParams['PROPERTY_NUM'], $arParams['PROPERTY_MSG']);
            } else {
                throw new Exception(Loc::getMessage('CANT_QUIZ_PRIZE_COMPONENT'), 5);
            }

            return false;
        }

        public function executeComponent() {
            
            $this->arResult['COMPONENT_ID'] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
            $this->arResult['COMPONENT_NAME'] = $this->GetName();

            $_SESSION[$this->arResult['COMPONENT_ID']] = $this->arParams;

            global $USER;

            $this->arResult['SHOW_BUTTON'] = true;

            $startDateWeek = $this->arParams["CALCULATE_FULL_RESULT"] ? 315532800 : strtotime('monday this week');
            $showStartTime = strtotime( date('d-m-Y ' . $this->arParams["SHOW_RESULT_ON_FIRST"]) );
            $currentDay = date('l');

            $firstValue = reset($this->arParams["SHOW_RESULT_ON_DAYS"]);

            $prizeObj = new \Outcode\Prize();

            if( !in_array($currentDay, $this->arParams["SHOW_RESULT_ON_DAYS"]) ) {
                $this->arResult['SHOW_BUTTON'] = false;
            } else if( $currentDay == $firstValue && time() < $showStartTime ) {
                $this->arResult['SHOW_BUTTON'] = false;
            }

            $minValue = 0;
            $currentCount = 0;
            $this->arResult['BUTTON_NAME'] = Loc::getMessage('QUIZ_PRIZE_SELECT');
            if( $this->arResult['SHOW_BUTTON'] && !empty($this->arParams['PROPERTY_NUM']) ) {
                $res = CIBlockElement::GetByID($this->arParams['ELEMENT_ID']);
                if($resObj = $res->GetNextElement()) {
                    $arProps = $resObj->GetProperties();
                    foreach( $arProps as $code => $item ) {
                        if( $code == 'PRISE_NAME' ) {
                            $this->arResult['BUTTON_NAME'] = $item['VALUE'];
                        } else if( $code == $this->arParams['PROPERTY_NUM'] ) {
                            $currentCount = intval($item['VALUE']);
                        } else if( $code == $this->arParams['PROPERTY_MINVALUE'] ) {
                            $minValue = intval($item['VALUE']);
                        }
                    }
                }
                if( $currentCount <= 0 ) $this->arResult['SHOW_BUTTON'] = false;
                if( $this->arResult['SHOW_BUTTON'] ) $this->arResult['SHOW_BUTTON'] = $prizeObj->isButtonEnabled($startDateWeek, time(), $this->arParams['LIMIT'], $minValue, $this->arParams['CALCULATE_FULL_RESULT']);
            }

            $_SESSION[$this->arResult['COMPONENT_ID']]['IS_ENABLED'] = $this->arResult['SHOW_BUTTON'];

            $this->includeComponentTemplate();
        }
    }