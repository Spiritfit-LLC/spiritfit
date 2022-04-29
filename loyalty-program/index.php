<?
	define('HIDE_SLIDER', true);
	define('H1_HIDE', true);
	define('HOLDER_CLASS', 'loyality');
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetTitle("Программа лояльности");
	$APPLICATION->SetPageProperty("title", "Программа лояльности - фитнес-клуб Spirit. Fitness");
	$APPLICATION->SetPageProperty("description", "");
	
	$settings = Utils::getInfo();
	
	$APPLICATION->SetTitle(strip_tags($settings["PROPERTIES"]["LOYALITY_TITLE"]["~VALUE"]));
?>
<div class="content-center">
	<div class="loyality-top">
		<h1><?=$settings["PROPERTIES"]["LOYALITY_TITLE"]["~VALUE"]?></span></h1>
		<div class="loyality-top-description">
			Бонусная программа для членов клуба Spirit.Fitness.<br/>
			Для участия в программе необходимо оплатить любой вид контракта, регистрация в программе лояльности происходит автоматически.
		</div>
		<div class="loyality-top-levels">
			<div class="loyality-top-levels__title">Уровни лояльности <span>Spirit.</span> Fitness</div>
			<div class="loyality-top-levels__description">Чем чаще и дольше вы тренируетесь — тем выше уровень лояльности и больше привилегий.</div>
		</div>
	</div>
	<div class="loyality-levels">
		<div class="loyality-levels-item">
			<div class="loyality-levels-item__title">Знаток <span>Spirit.</span></div>
			<div class="loyality-levels-item__image">
				<img src="/images/loyality/item_1.jpg" alt="Знаток Spirit." title="Знаток Spirit.">
			</div>
			<div class="loyality-levels-item__content">
				<div class="loyality-levels-item__description">Тренируется<br/>от 1 до 4 месяцев</div>
				<div class="loyality-levels-item__bonus">
					<span>+5</span>
					<span>бонусов за посещение</span>
				</div>
			</div>
		</div>
		<div class="loyality-levels-item">
			<div class="loyality-levels-item__title">Амбассадор <span>Spirit.</span></div>
			<div class="loyality-levels-item__image">
				<img src="/images/loyality/item_2.jpg" alt="Амбассадор Spirit." title="Амбассадор Spirit.">
			</div>
			<div class="loyality-levels-item__content">
				<div class="loyality-levels-item__description">Тренируется<br/>от 5 до 8 месяцев</div>
				<div class="loyality-levels-item__bonus">
					<span>+7</span>
					<span>бонусов за посещение</span>
				</div>
			</div>
		</div>
		<div class="loyality-levels-item">
			<div class="loyality-levels-item__title">Легенда <span>Spirit.</span></div>
			<div class="loyality-levels-item__image">
				<img src="/images/loyality/item_3.jpg" alt="Легенда Spirit." title="Легенда Spirit.">
			</div>
			<div class="loyality-levels-item__content">
				<div class="loyality-levels-item__description">Тренируется более<br/>9 месяцев</div>
				<div class="loyality-levels-item__bonus">
					<span>+10</span>
					<span>бонусов за посещение</span>
				</div>
			</div>
		</div>
		<div class="loyality-levels-item">
			<div class="loyality-levels-item__title">Личный кабинет</div>
			<div class="loyality-levels-item__image">
				<img src="/images/loyality/item_4.jpg" alt="Личный кабинет" title="Личный кабинет">
			</div>
			<div class="loyality-levels-item__content">
				<div class="loyality-levels-item__description"><a href="<?=$settings["PROPERTIES"]["LOYALITY_REGISTER"]["VALUE"]?>">Регистрация</a></div>
				<div class="loyality-levels-item__button">
					<div class="description"><a href="<?=$settings["PROPERTIES"]["LOYALITY_FORGOT"]["VALUE"]?>">Забыли пароль</a></div>
					<div class="button-wrapper"><a class="btn" href="<?=$settings["PROPERTIES"]["LOYALITY_LOGIN"]["VALUE"]?>">Войти</a></div>
				</div>
			</div>
		</div>
	</div>
	<div class="loyality-steps">
		<div class="loyality-steps-item__wrapper">
			<div class="loyality-steps-item">
				<div class="loyality-steps-item__icon">
					<svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M35 61.25C49.4975 61.25 61.25 49.4975 61.25 35C61.25 20.5025 49.4975 8.75 35 8.75C20.5025 8.75 8.75 20.5025 8.75 35C8.75 49.4975 20.5025 61.25 35 61.25Z" stroke="white" stroke-width="2.91667" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M34.912 46.32C31.5627 46.32 28.5547 45.1253 25.888 42.736L26.976 41.488C28.2347 42.6613 29.4827 43.5147 30.72 44.048C31.9573 44.56 33.3867 44.816 35.008 44.816C36.672 44.816 38.0267 44.4 39.072 43.568C40.1387 42.7147 40.672 41.6267 40.672 40.304C40.672 39.0667 40.224 38.0853 39.328 37.36C38.4533 36.6347 36.7893 36.0267 34.336 35.536C31.648 34.9813 29.7173 34.2133 28.544 33.232C27.3707 32.2507 26.784 30.896 26.784 29.168C26.784 27.504 27.4667 26.1067 28.832 24.976C30.1973 23.8453 31.9147 23.28 33.984 23.28C35.5627 23.28 36.9387 23.504 38.112 23.952C39.3067 24.3787 40.4907 25.0613 41.664 26L40.608 27.312C38.6667 25.6267 36.4373 24.784 33.92 24.784C32.2987 24.784 30.976 25.2 29.952 26.032C28.9493 26.8427 28.448 27.856 28.448 29.072C28.448 30.3307 28.896 31.3333 29.792 32.08C30.7093 32.8267 32.4373 33.456 34.976 33.968C37.5573 34.5013 39.424 35.2587 40.576 36.24C41.7493 37.2213 42.336 38.544 42.336 40.208C42.336 42 41.6427 43.472 40.256 44.624C38.8693 45.7547 37.088 46.32 34.912 46.32Z" fill="white"/>
						<path d="M25.888 42.736L25.5111 42.4074L25.187 42.7792L25.5543 43.1084L25.888 42.736ZM26.976 41.488L27.3169 41.1223L26.9388 40.7698L26.5991 41.1594L26.976 41.488ZM30.72 44.048L30.5221 44.5072L30.5288 44.51L30.72 44.048ZM39.072 43.568L39.3834 43.9592L39.3843 43.9584L39.072 43.568ZM39.328 37.36L39.0088 37.7449L39.0134 37.7486L39.328 37.36ZM34.336 35.536L34.235 36.0257L34.2379 36.0263L34.336 35.536ZM28.544 33.232L28.2232 33.6155L28.544 33.232ZM28.832 24.976L29.1509 25.3611L29.1509 25.3611L28.832 24.976ZM38.112 23.952L37.9336 24.4192L37.9438 24.4229L38.112 23.952ZM41.664 26L42.0535 26.3135L42.3679 25.9228L41.9763 25.6096L41.664 26ZM40.608 27.312L40.2802 27.6896L40.6721 28.0298L40.9975 27.6255L40.608 27.312ZM29.952 26.032L30.2664 26.4208L30.2673 26.4201L29.952 26.032ZM29.792 32.08L29.4719 32.4641L29.4764 32.4678L29.792 32.08ZM34.976 33.968L35.0772 33.4783L35.0749 33.4779L34.976 33.968ZM40.576 36.24L40.2518 36.6206L40.2552 36.6235L40.576 36.24ZM40.256 44.624L40.572 45.0115L40.5755 45.0086L40.256 44.624ZM34.912 45.82C31.6948 45.82 28.8035 44.677 26.2217 42.3636L25.5543 43.1084C28.3058 45.5737 31.4306 46.82 34.912 46.82V45.82ZM26.2649 43.0646L27.3529 41.8166L26.5991 41.1594L25.5111 42.4074L26.2649 43.0646ZM26.6351 41.8537C27.9246 43.0559 29.2198 43.9458 30.5221 44.5072L30.9179 43.5888C29.7456 43.0835 28.5447 42.2668 27.3169 41.1223L26.6351 41.8537ZM30.5288 44.51C31.8381 45.0518 33.3346 45.316 35.008 45.316V44.316C33.4388 44.316 32.0766 44.0682 30.9112 43.586L30.5288 44.51ZM35.008 45.316C36.7567 45.316 38.2296 44.8775 39.3834 43.9592L38.7606 43.1768C37.8237 43.9225 36.5873 44.316 35.008 44.316V45.316ZM39.3843 43.9584C40.5651 43.0138 41.172 41.7842 41.172 40.304H40.172C40.172 41.4691 39.7122 42.4155 38.7597 43.1776L39.3843 43.9584ZM41.172 40.304C41.172 38.9285 40.6654 37.7993 39.6426 36.9714L39.0134 37.7486C39.7826 38.3713 40.172 39.2049 40.172 40.304H41.172ZM39.6472 36.9751C38.6681 36.1632 36.8931 35.5375 34.4341 35.0457L34.2379 36.0263C36.6855 36.5158 38.2386 37.1062 39.0088 37.7449L39.6472 36.9751ZM34.437 35.0463C31.7751 34.497 29.9425 33.7498 28.8648 32.8485L28.2232 33.6155C29.4922 34.6769 31.5209 35.4656 34.235 36.0257L34.437 35.0463ZM28.8648 32.8485C27.8206 31.9751 27.284 30.7695 27.284 29.168H26.284C26.284 31.0225 26.9207 32.5262 28.2232 33.6155L28.8648 32.8485ZM27.284 29.168C27.284 27.6637 27.8917 26.4039 29.1509 25.3611L28.5131 24.5909C27.0416 25.8095 26.284 27.3443 26.284 29.168H27.284ZM29.1509 25.3611C30.412 24.3168 32.0106 23.78 33.984 23.78V22.78C31.8187 22.78 29.9827 23.3739 28.5131 24.5909L29.1509 25.3611ZM33.984 23.78C35.5167 23.78 36.8298 23.9976 37.9336 24.4191L38.2904 23.4849C37.0475 23.0104 35.6086 22.78 33.984 22.78V23.78ZM37.9438 24.4229C39.0777 24.8278 40.2138 25.4802 41.3517 26.3904L41.9763 25.6096C40.7675 24.6425 39.5356 23.9295 38.2802 23.4811L37.9438 24.4229ZM41.2745 25.6865L40.2185 26.9985L40.9975 27.6255L42.0535 26.3135L41.2745 25.6865ZM40.9358 26.9344C38.9056 25.172 36.5606 24.284 33.92 24.284V25.284C36.3141 25.284 38.4277 26.0814 40.2802 27.6896L40.9358 26.9344ZM33.92 24.284C32.2109 24.284 30.7688 24.7241 29.6367 25.6439L30.2673 26.4201C31.1832 25.6759 32.3864 25.284 33.92 25.284V24.284ZM29.6376 25.6432C28.5257 26.5422 27.948 27.6948 27.948 29.072H28.948C28.948 28.0172 29.373 27.1431 30.2664 26.4208L29.6376 25.6432ZM27.948 29.072C27.948 30.4657 28.452 31.6142 29.4719 32.4641L30.1121 31.6959C29.34 31.0525 28.948 30.1957 28.948 29.072H27.948ZM29.4764 32.4678C30.4963 33.298 32.3322 33.9449 34.8772 34.4581L35.0749 33.4779C32.5425 32.9671 30.9223 32.3553 30.1076 31.6922L29.4764 32.4678ZM34.8748 34.4577C37.4268 34.9849 39.1951 35.7205 40.2518 36.6206L40.9002 35.8594C39.6529 34.7968 37.6879 34.0178 35.0772 33.4783L34.8748 34.4577ZM40.2552 36.6235C41.3041 37.5008 41.836 38.6773 41.836 40.208H42.836C42.836 38.4107 42.1946 36.9419 40.8968 35.8565L40.2552 36.6235ZM41.836 40.208C41.836 41.8503 41.2096 43.1817 39.9365 44.2394L40.5755 45.0086C42.0757 43.7623 42.836 42.1497 42.836 40.208H41.836ZM39.94 44.2365C38.6607 45.2796 36.9986 45.82 34.912 45.82V46.82C37.1774 46.82 39.078 46.2297 40.572 45.0115L39.94 44.2365Z" fill="white"/>
					</svg>
				</div>
				<div class="loyality-steps-item__title">Что такое бонусы?</div>
				<div class="loyality-steps-item__description">
					<ul>
						<li>Бонусы - условные единицы Spirit., которые можно получить за посещения клуба, рекомендации, участия в мероприятиях и прочих активностях клубов, чем чаще и дольше посещать клуб, тем больше бонусов можно получить.</li>
						<li>Бонусы нельзя обменять на наличные деньги.</li>
					</ul>
				</div>
				<div class="loyality-steps-item__warning">1 бонус = 1 рубль</div>
			</div>
		</div>
		<div class="loyality-steps-item__wrapper">
			<div class="loyality-steps-item">
				<div class="loyality-steps-item__icon">
					<svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M35 61.25C49.4975 61.25 61.25 49.4975 61.25 35C61.25 20.5025 49.4975 8.75 35 8.75C20.5025 8.75 8.75 20.5025 8.75 35C8.75 49.4975 20.5025 61.25 35 61.25Z" stroke="white" stroke-width="2.91667" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M27 35.5L34 41.5L46.5 28" stroke="white" stroke-width="2.91667" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
				<div class="loyality-steps-item__title">Условия начисления бонусов</div>
				<div class="loyality-steps-item__description">
					<ul>
						<li>Бонусы начисляются с 1-го дня активации контракта.</li>
						<li>Бонусы можно использовать начиная с 31 дня с даты активации контракта.</li>
						<li>Бонусы начисляются при непрерывной и своевременной оплате контракта.</li>
						<li>Бонусы действительны и не аннулируются при условии возобновления контракта в течение 30 дней с даты расторжения контракта.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="loyality-steps-item__wrapper">
			<div class="loyality-steps-item">
				<div class="loyality-steps-item__icon">
					<svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M35 61.25C49.4975 61.25 61.25 49.4975 61.25 35C61.25 20.5025 49.4975 8.75 35 8.75C20.5025 8.75 8.75 20.5025 8.75 35C8.75 49.4975 20.5025 61.25 35 61.25Z" stroke="white" stroke-width="2.91667" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M35 20.4166V35L43.75 43.75" stroke="white" stroke-width="2.91667" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
				<div class="loyality-steps-item__title">Срок действия бонусов</div>
				<div class="loyality-steps-item__description">
					<ul>
						<li>Бонусы доступны в течение 12 месяцев со дня начисления, после чего аннулируются, если не использованы.</li>
					</ul>
				</div>
				<div class="loyality-steps-item__button">
					<a class="btn" href="<?=$settings["PROPERTIES"]["LOYALITY_PARTICIPATE"]["VALUE"]?>">Учавствовать</a>
				</div>
			</div>
		</div>
	</div>
	<div class="loyality-warning">
		<div class="loyality-warning-title">Важно!</div>
		<div class="loyality-warning-description">Чтобы использовать бонусы необходимо <a href="<?=$settings["PROPERTIES"]["LOYALITY_LOGIN"]["VALUE"]?>">войти</a></div>
		<div class="loyality-warning-description">Ознакомиться с <a href="<?=$settings["PROPERTIES"]["LOYALITY_STATE"]["VALUE"]?>">Положением</a> «О Программе лояльности»</div>
	</div>
	<div class="loyality-bonus">
		<div class="loyality-bonus-title">
			Накопление и списание бонусов
		</div>
		<div class="loyality-bonus-description">
			Чем чаще и дольше вы тренируетесь — тем выше уровень лояльности и больше привилегий.
		</div>
		<div class="loyality-bonus-table">
			<div class="table-cell">
				<div class="loyality-bonus-table__title">Как получить бонусы?</div>
				<ul>
					<li>
						<span class="title">Посещение клуба</span>
						Получайте бонусы за каждое посещение клуба (не более 1 посещения в день).
						<span class="table">
							<span>
								<span class="title">Знаток</span>
								5 бонусов
							</span>
							<span>
								<span class="title">Амбассадор</span>
								7 бонусов
							</span>
							<span>
								<span class="title">Легенда</span>
								10 бонусов
							</span>
						</span>
					</li>
					<li>
						<span class="title">Приветственный бонус <span>100</span></span>
						Мы начислим +100 бонусов всем, кто впервые оформит контракт.
					</li>
					<li>
						<span class="title">Рекомендация <span>100</span></span>
						Поделитесь вашим персональным промокодом с другом, и ваш друг получит скидку 500Р на покупку контракта, а вы +100 бонусов за каждого.
					</li>
					<li>
						<span class="title">День рождения <span>100</span></span>
						Укажите вашу дату рождения при оформлении контракта и получите +100 бонусов.
					</li>
					<li>
						<span class="title">День рождения клуба <span>100</span></span>
						Мы рады разделить это праздник с вами и дарим +100 бонусов (при со-доступе бонусы начисляются по клубу продажи)
					</li>
					<li>
						<span class="title">Переход на новый уровень лояльности <span>50</span></span>
						Выполнив условия перехода на новый уровень вы получите +50 бонусов
					</li>
				</ul>
			</div>
			<div class="table-cell">
				<div class="loyality-bonus-table__title">Как получить extra-бонусы?</div>
				<ul>
					<li>
						<span class="title">Дневное время <span>x 1,2</span></span>
						Посещайте клубы в период с 10.00 до 17.00 и мы умножим ваши бонусы за посещения x 1,2.
					</li>
					<li>
						<span class="title">Активные посещения <span>x 1,2</span></span>
						Посещайте клубы более 12 раз в месяц и мы умножим ваши бонусы за посещения x 1,2 (календарный месяц).
					</li>
					<li>
						<span class="title">Участие в фитнес-марафонах <span>x 2</span></span>
						Оплатите участие в фитнес-марафоне и мы умножим ваши бонусы за посещения x2 в период проведения проекта.
					</li>
				</ul>
			</div>
			<div class="table-cell">
				<div class="loyality-bonus-table__title">Как потратить бонусы?</div>
				<ul>
					<li>
						<span class="title">Накопленные бонусы можно потратить на скидку</span>
						<div class="loyality-bonus-table__blocks">
							<div class="loyality-bonus-table__blocks-item">
								<div>до</div>
								<div>20<span>%</span></div>
								<div>стоимости абонемента</div>
							</div>
							<div class="loyality-bonus-table__blocks-item">
								<div>до</div>
								<div>50<span>%</span></div>
								<div>стоимости дополнительных услуг</div>
							</div>
							<div class="loyality-bonus-table__blocks-item">
								<div>до</div>
								<div>100<span>%</span></div>
								<div>стоимости абонемента для друзей</div>
							</div>
						</div>
					</li>
				</ul>
				<div class="loyality-bonus-button">
					<a class="btn" href="<?=$settings["PROPERTIES"]["LOYALITY_CHECK"]["VALUE"]?>">Проверить баланс</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>