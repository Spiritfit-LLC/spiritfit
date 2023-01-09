<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters(), 'componentName'=>$this->getComponent()->getName()])?>;
</script>
<div class="client-auth__container content-center">
    <a id="client-auth__btn" class="button" onclick="authAction()">АВТОРИЗАЦИЯ</a>
    <div id="user-message"></div>
</div>
