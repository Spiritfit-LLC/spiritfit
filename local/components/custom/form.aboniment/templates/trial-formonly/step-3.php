<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="subscription fixed js-page-trial-training" id="trial-subscription-fragment">
    <div class="subscription__aside subscription__aside--standalone">
        <div class="subscription__success-message">
            <?= $arResult["ELEMENT"]["~DETAIL_TEXT"] ?>
        </div>        
        <?php
        // передаю в цель клуб по id из get параметра
        $selectClub = '';
        foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
            if(!empty($itemClub['SELECTED'])){
                $selectClub = $itemClub['MESSAGE'];
            }
        }
        ?>
        <script>
            (dataLayer = window.dataLayer || []).push({
                'eCategory': 'conversion',
                'eAction': 'sendContactFormShort',
                'eLabel': '<?= $_SESSION['E_LABEL_SEND_CONTACT_FORM_SHORT'] . "/" . $selectClub ?>', // указывать название выбранного абонемента и клуба
                'eNI': false,
                'event': 'GAEvent'
            });            
        </script>
    </div>
    <style>
        .subscription__aside--standalone {
            width: 100%;
            height: 660px;
            margin-top: 0;
            margin-bottom: 0;
            padding: 70px 130px 40px;
            position: relative;
        }

        @media screen and (max-width: 1260px),
        screen and (min-aspect-ratio: 76/35) and (min-width: 1260px) {
            .subscription__aside--standalone {
                padding-left: 10%;
                padding-right: 10%;
            }
        }

        @media screen and (max-width: 768px) {
            .subscription__aside--standalone {
                min-width: 100vw;
            }
        }

        .subscription__success-message {
            font-size: 150%;
            line-height: 1.5;
            padding: 2em 1em;
        }
    </style>
</div>