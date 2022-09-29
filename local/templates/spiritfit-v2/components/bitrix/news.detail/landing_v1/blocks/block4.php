<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK4_ACTIVE"])) { ?>
    <div class="b-form">
        <div class="content-center">
		    <div class="landing-title">
			    <?=$BLOCKS["BLOCK4_TITLE"]?>
		    </div>
	    </div>
	    <?
            $APPLICATION->IncludeComponent(
                "custom:form.request.new",
                "on.page.block",
                array(
                    "AJAX_MODE" => "N",
                    "COMPONENT_TEMPLATE" => "on.page.block",
                    "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                    "WEB_FORM_FIELDS" => array(
                        0 => "name",
                        1 => "phone",
                        2 => "email",
                        3 => "personaldata",
                        4 => "rules",
                        5 => "privacy",
                    ),
                    "FORM_TYPE" => $BLOCKS["BLOCK4_FORM_TYPE"],
                    "CLIENT_TYPE" => $BLOCKS["BLOCK4_CLIENT_TYPE"],
                ),
            false);
        ?>
    </div>
<? } ?>