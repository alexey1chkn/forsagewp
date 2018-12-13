<?

/*
	*Template Name: Main_page
	*Template Post Type: post, page, product
*/

get_header();

 ?>
			<!--SLIDER===============================================================================================================--->
			<div class="slider-main_page">
				<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
				  <ol class="carousel-indicators">
				    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
				  </ol>
				  <div class="carousel-inner">
				    <div class="carousel-item active">
				      <img class="d-block w-100" src="<?echo get_template_directory_uri();?>/assets/img/logo.png" alt="First slide">
				    </div>
				    <div class="carousel-item">
				      <img class="d-block w-100" src="<?echo get_template_directory_uri();?>/assets/img/logo.png" alt="Second slide">
				    </div>
				    <div class="carousel-item">
				      <img class="d-block w-100" src="<?echo get_template_directory_uri();?>/assets/img/logo.png" alt="Third slide">
				    </div>
				  </div>
				  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="sr-only">Previous</span>
				  </a>
				  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			<!--Selection of goods==========================================================================================================-->
			<div class="goods_selection">
				<div class="row">
					<div class="col-6">
						<div class="goods_selection-tires_main">
							<div class="row">
								<div class="col-7">
									<div class="goods_selection-tires_main-characters">
										<ul>
											<li>
												<span>Ширина</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Высота</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Диаметр</span>
												<div class="characters"></div>
											</li>
											<li>
												<div class="characters">
													<img src="<?echo get_template_directory_uri();?>/assets/img/cold.svg" alt="">
													<img src="<?echo get_template_directory_uri();?>/assets/img/sun.svg" alt="">
												</div>
											</li>
											<li>
												<span>Шипы</span>
												<div class="characters"></div>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-5">
									<div class="goods_selection-tires_main-visual">
										<span>Подбор шин</span>
										<img src="<?echo get_template_directory_uri();?>/assets/img/tire_main_1.svg" alt="">
										<a href="#">Подобрать</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="goods_selection-discs_main">
							<div class="row">
								<div class="col-7">
									<div class="goods_selection-tires_main-characters">
										<ul>
											<li>
												<span>Диаметр</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>PSD</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Ст.отверстие</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Вылет ЕТ</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Ширина диска</span>
												<div class="characters"></div>
											</li>
											<li>
												<span>Тип диска</span>
												<div class="characters"></div>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-5">
									<div class="goods_selection-discs_main-visual">
										<span>Подбор шин</span>
										<img src="<?echo get_template_directory_uri();?>/assets/img/disc_main_1.svg" alt="">
										<a href="#">Подобрать</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--EXTRA GOODS+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++========================================================================-->
				<div class="row">
					<div class="col-6">
						<div class="goods_selection-tires_others">
							<div class="row">
								<div class="col-7">
									<div class="goods_selection-tires_others-characters">
										<span class="custom_title">Другие товары</span>
											<ul>
												<li>Аккумуляторы</li>
												<li>Крепежи</li>
												<li>Кольца</li>
											</ul>
									</div>
								</div>
								<div class="col-5">
									<div class="goods_selection-tires_others-visual">
										<a href="#">Показать</a>
										<img src="<?echo get_template_directory_uri();?>/assets/img/others.svg" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="goods_selection-tires_others">
							<div class="row">
								<div class="col-7">
									<div class="goods_selection-tires_others-characters">
										<span class="custom_title">Шины и диски</span>
											<p>Для сельхоз и грузовой техник</p>
									</div>
								</div>
								<div class="col-5">
									<div class="goods_selection-tires_others-visual">
										<a href="#">Показать</a>
										<img src="<?echo get_template_directory_uri();?>/assets/img/extra.svg" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
			<!--MAP===============================================================================================================================-->
			<div class="map-main_page">
				<iframe frameborder="no" style="border: 1px solid #a3a3a3; box-sizing: border-box;" width="100%" height="550" src="http://widgets.2gis.com/widget?type=firmsonmap&amp;options=%7B%22pos%22%3A%7B%22lat%22%3A53.222903699090196%2C%22lon%22%3A44.966611862182624%2C%22zoom%22%3A16%7D%2C%22opt%22%3A%7B%22city%22%3A%22penza%22%7D%2C%22org%22%3A%225911502792046450%22%7D"></iframe>
				<div class="map-main_page-info">
					<div class="map-main_page-info-title"><span>Как Нас Найти</span></div>
					<div class="map-main_page-info-inner">
						<div class="map-main_page-info-inner-address"><span>г. Пенза,  Проспект победы 88</span></div>
						<div class="map-main_page-info-inner-phone_numbers">
							<ul>
								<li><span>Отдел продаж:</span>    8 8412 <span>20-45-40</span></li>
								<li><span>Шиномонтаж:</span>   8 8412 <span>20-45-39</span></li>
								<li><span>Доп. номер:   8 903 323-56-06</span></li>
							</ul>
						</div>
						<div class="map-main_page-info-inner-work_hours">
							<ul>
								<li><span>Режим работы:</span> Пн-Пт 8:30 - 19:00</li>
								<li>Сб.  8:30 - 17:00</li>
								<li>Вс.  8:30 - 16:00</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!--FOOTER============================================================================================================================================================-->
			<div class="footer-banner">
				<div class="row justify-content-around align-items-center">
					<div class="col-3">
						<div class="footer-banner-logo">
							<img src="<?echo get_template_directory_uri();?>/assets/img/logo.svg" alt="">
						</div>
					</div>
					<div class="col-7">
						<div class="footer-banner-title">Максимальный ассортимент<br>За минимальные цены</div>
					</div>
				</div>
			</div>
		<div class="row">
			<div class="col-12">
				<div class="main-menu">
					<ul>
						<li><a href="#">Главная</a></li>
						<li><a href="#">Шины</a></li>
						<li><a href="#">Диски</a></li>
						<li><a href="#">АКБ</a></li>
						<li><a href="#">Крепежи</a></li>
						<li><a href="#">Кольца</a></li>
						<li><a href="#">О нас</a></li>
					</ul>
				</div>
			</div>
		</div>
		<footer>
			<div class="copyright">© Шинный центр Форсаж, 2018 г. Все права защищены законодательством РФ</div>
			<div class="developed-by">
				Сайт разработан командой<a href="#"><span>40digit</span></a>
			</div>
		</footer>
	</div>
</div>

<? get_footer(); ?>