<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if( !empty($BLOCKS["BLOCK7_LIST"]) ) { ?>
	<div class="b-winners blockitem">
		<div class="content-center">
			<div class="landing-title"><?=$BLOCKS["BLOCK7_TITLE"]?></div>
			<div class="landing-description"><?=$BLOCKS["BLOCK7_DESCRIPTION"]?></div>
			<div class="b-winners-wrapper">
				<? foreach($BLOCKS["BLOCK7_LIST"] as $item) { ?>
					<div class="b-winners-wrapper__item">
						<div class="b-winners-wrapper__item-image">
							<img src="<?=$item["SRC"]?>" alt="<?=$BLOCKS["BLOCK7_TITLE"]?>" title="<?=$BLOCKS["BLOCK7_TITLE"]?>">
						</div>
						<div class="b-winners-wrapper__item-desc">
							<?=$item["DESCRIPTION"]?>
						</div>
					</div>
				<? } ?>
				<? if( !empty($BLOCKS["BLOCK7_GIFT_LIST"]) ) { ?>
					<div class="b-winners-wrapper__item last">
						<? foreach($BLOCKS["BLOCK7_GIFT_LIST"] as $item) { ?>
							<div class="b-winners-wrapper__item-line">
								<div class="item-line__img">
									<img src="<?=$item["SRC"]?>" alt="<?=$BLOCKS["BLOCK7_TITLE"]?>" title="<?=$BLOCKS["BLOCK7_TITLE"]?>">
								</div>
								<div class="item-line__desc">
									<?=$item["DESCRIPTION"]?>
								</div>
							</div>
						<? } ?>
					</div>
				<? } ?>
			</div>
		</div>
	</div>
<? } ?>
