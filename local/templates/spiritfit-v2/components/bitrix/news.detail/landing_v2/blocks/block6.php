<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK6_LIST"])) { ?>
	<div class="b-treners blockitem">
		<div class="content-center">
			<div class="landing-title"><?=$BLOCKS["BLOCK6_TITLE"]?></div>
			<div class="landing-description"><?=$BLOCKS["BLOCK6_DESCRIPTION"]?></div>
			<div class="b-treners__wrapper">
				<? foreach($BLOCKS["BLOCK6_LIST"] as $leader) { ?>
					<? $source = !empty($leader["PROPERTIES"]["VIDEO"]["VALUE"]["SRC"]) ? base64_encode('<video autoplay controls><source src="'.$leader["PROPERTIES"]["VIDEO"]["VALUE"]["SRC"].'" type="video/'.$leader["PROPERTIES"]["VIDEO"]["VALUE"]["TYPE"].'"></video>') : ''; ?>
					<div class="b-twoside-card b-treners__item">
						<div class="b-twoside-card__inner">
							<div class="b-twoside-card__content">
								<div class="b-twoside-card__video <?=!empty($leader["PROPERTIES"]["VIDEO"]["VALUE"]["SRC"]) ? "has-video" : "" ?>" <?=!empty($leader["PROPERTIES"]["VIDEO"]["VALUE"]["SRC"]) ? "data-source=\"".$source."\"" : "" ?>>
									<img src="<?=$leader["PREVIEW_PICTURE"]?>" alt="<?=$leader["NAME"]?>" title="<?=$leader["NAME"]?>">
									<? if( !empty($leader["PROPERTIES"]["VIDEO"]["VALUE"]["SRC"]) ) { ?>
										<span class="play">
											<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M17.5089 9.8778C18.3561 10.3669 18.3561 11.5897 17.5089 12.0788L4.16598 19.7823C3.3188 20.2715 2.25984 19.6601 2.25984 18.6818L2.25984 3.27476C2.25984 2.29653 3.31881 1.68514 4.16598 2.17425L17.5089 9.8778Z" stroke="url(#paint0_linear_428_580)" stroke-width="2.54151"/>
												<defs>
													<linearGradient id="paint0_linear_428_580" x1="21.9565" y1="10.9783" x2="-6.00006" y2="10.9783" gradientUnits="userSpaceOnUse">
														<stop stop-color="#E23835"/>
														<stop offset="1" stop-color="#7B27EF"/>
													</linearGradient>
												</defs>
											</svg>
											Смотреть видео
										</span>
									<? } ?>
								</div>
								<div class="b-twoside-card__name">
									<?=str_replace(" ", "<br/>", $leader["NAME"])?>
								</div>
								<div class="b-twoside-card__open">
									<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M17.5089 9.8778C18.3561 10.3669 18.3561 11.5897 17.5089 12.0788L4.16598 19.7823C3.3188 20.2715 2.25984 19.6601 2.25984 18.6818L2.25984 3.27476C2.25984 2.29653 3.31881 1.68514 4.16598 2.17425L17.5089 9.8778Z" stroke="url(#paint0_linear_428_580)" stroke-width="2.54151"/>
										<defs>
											<linearGradient id="paint0_linear_428_580" x1="21.9565" y1="10.9783" x2="-6.00006" y2="10.9783" gradientUnits="userSpaceOnUse">
												<stop stop-color="#E23835"/>
												<stop offset="1" stop-color="#7B27EF"/>
											</linearGradient>
										</defs>
									</svg>
									Смотреть
								</div>
							</div>
							<div class="b-twoside-card__hidden-content">
								<div class="b-twoside-card__name">
									<?=$leader["NAME"]?>
								</div>
								<div class="b-twoside-card__description">
									<?=$leader["~DETAIL_TEXT"]?>
								</div>
								<a class="button setTrener" href="#set" data-id="<?=$leader["ID"]?>">Выбрать тренера</a>
							</div>
						</div>
					</div>
				<? } ?>
			</div>
		</div>
	</div>
<? } ?>