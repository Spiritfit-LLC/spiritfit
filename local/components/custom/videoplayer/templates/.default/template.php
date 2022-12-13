<?php
?>
<style>
    .video-container{
        height: 400px;
        position: relative;
    }
    .videoPlayer {
        height:100%;
    }

    .videoPlayer video {
        width: 100%;
        height: 100%;
    }

    .video-track {
        height: 5px;
        width: 100%;
        background-color: #b6b6b6;
    }

    .timeline {
        height: 5px;
        width: 0;
        background-color: #ff7628;
    }
    .buttons {
        position: absolute;
        top: 50%;
        bottom: 50%;
        left: 50%;
        right: 50%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    .buttons button{
        margin: 0 10px;
        background: none;
        border: none;
    }

    .buttons button svg{
        width:20px;
        height:20px;
        fill: #fe6000;
        stroke: black;
        stroke-width: 2px;
    }

    .videoPlayer.stop .start-btn{
        display:block;
    }



</style>
<div class="videoPlayer stop" data-status="stop" id="<?=$arResult['COMPONENT_ID']?>">
    <video src="<?=$arResult['VIDEOFILE']?>" type="video/mp4" <?if(!empty($arResult['POSTER'])):?> poster="<?=$arResult['POSTER']?>" <?endif;?> controls></video>
    <div id="controls">

    </div>
</div>