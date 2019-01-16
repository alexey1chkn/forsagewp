<?php 

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
				      <img class="d-block w-100" src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="First slide">
				    </div>
				    <div class="carousel-item">
				      <img class="d-block w-100" src="<?php echo get_template_directory_uri();?>/assets/img/26_IUt-ANI4.jpg" alt="Second slide">
				    </div>
				    <div class="carousel-item">
				      <img class="d-block w-100" src="<?php echo get_template_directory_uri();?>/assets/img/8qlqduVasXY.jpg" alt="Third slide">
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
			<?php 
				$link = '/vybor-shin/?';
			?>
			<div class="goods_selection">
				<div class="row">
					<form action="" method="GET">
						<div class="col-lg-6 col-12">
							<div class="goods_selection-tires_main">
								<div class="row">
									<div class="col-lg-7 col-md-8 col-7">								
										<div class="goods_selection-tires_main-characters">
											<ul>
												<li>
													<div class="characters">
														<span>Ширина</span>								
														<select>
															<option value="">185</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Высота</span>
														<select>
															<option value="">185</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>
													</div>
												</li>
												<li>
													<div class="characters">
														<span>Диаметр</span>
														<select>
															<option value="">185</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>												
													</div>
												</li>
												<li>
													<div class="characters">
														<div class="characters-checkbox">
															<img src="<?php echo get_template_directory_uri();?>/assets/img/cold.svg" alt="">
															<input type="checkbox" id="winter" name="" checked>
															<label for="winter"></label>
														</div>
														<div class="characters-checkbox">
															<img src="<?php echo get_template_directory_uri();?>/assets/img/sun.svg" class="summer" alt="">
															<input type="checkbox" id="summer" name="">
															<label for="summer"></label>
														</div>
													</div>
												</li>
												<li>
													<div class="characters">													
														<div class="characters-checkbox spikes">
															<span>Шипы</span>
															<input type="checkbox" id="spikes" name="">
															<label for="spikes"></label>
														</div>
													</div>
												</li>
											</ul>										
										</div>
									</div>
									<div class="col-lg-5 col-md-4 col-5">
										<div class="goods_selection-tires_main-visual">
											<span>Подбор шин</span>
											<img src="<?php echo get_template_directory_uri();?>/assets/img/tire_main_1.svg" alt="">
											<a href="#">Подобрать</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!--END FORM=================================================================================================================-->
					<form action="/diski" method="GET">
						<div class="col-lg-6 col-12">
							<div class="goods_selection-discs_main">
								<div class="row">
									<div class="col-7">
										<div class="goods_selection-tires_main-characters">	
											<ul>
												<li>												
													<div class="characters">
														<span>Диаметр</span>
														<select name="diametr-diska">
															<option disabled selected> - </option>
															<option value="12">12</option>
															<option value="13">13</option>
															<option value="14">14</option>
															<option value="15">15</option>
															<option value="16">16</option>
															<option value="17">17</option>
															<option value="17,5">17,5</option>
															<option value="18">18</option>
															<option value="19">19</option>
															<option value="20">20</option>
														</select>	
													</div>
												</li>
												<li>										
													<div class="characters">
														<span>PSD</span>
														<select>
															<option value="">185</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Ст.отверстие</span>
														<select>
															<option value="">185</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Вылет ЕТ</span>
														<select name="vylet-diska">
															<option disabled selected> - </option>
															<option value="35">35</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Ширина диска</span>
														<select name="shirina-diska">
															<option disabled selected> - </option>
															<option value="4">4</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Тип диска</span>
														<select>
															<option value="">Литой</option>
															<option value="">195</option>
															<option value="">215</option>
															<option value="">225</option>
															<option value="">235</option>
														</select>	
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="col-5">
										<div class="goods_selection-discs_main-visual">
											<span>Подбор дисков</span>
											<img src="<?php echo get_template_directory_uri();?>/assets/img/disc_main_1.svg" alt="">
											<input type="submit">
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!--EXTRA GOODS+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++========================================================================-->
				<div class="row">
					<div class="col-md-6 col-12">
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
										<img src="<?php echo get_template_directory_uri();?>/assets/img/others.svg" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-12">
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
										<img src="<?php echo get_template_directory_uri();?>/assets/img/extra.svg" alt="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
			<!--MAP===============================================================================================================================-->
			<div class="map-main_page">
				<iframe frameborder="no" style="border: 1px solid #a3a3a3; box-sizing: border-box;" width="100%" height="550" src="https://widgets.2gis.com/widget?type=firmsonmap&amp;options=%7B%22pos%22%3A%7B%22lat%22%3A53.222903699090196%2C%22lon%22%3A44.966611862182624%2C%22zoom%22%3A16%7D%2C%22opt%22%3A%7B%22city%22%3A%22penza%22%7D%2C%22org%22%3A%225911502792046450%22%7D"></iframe>
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
			<!--FOOTER=============================================================================================================================================================-->
			<div class="footer-banner">
				<div class="row justify-content-xl-around justify-content-md-center align-items-center">
					<div class="col-3"><!-- col-md-5 -->
						<div class="footer-banner-logo">
							<img src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="">
						</div>
					</div>
					<div class="col-7"><!--  col-md-8 -->
						<div class="footer-banner-title"><span>Максимальный ассортимент</span><br><span>За минимальные цены</span></div>
					</div>
				</div>
			</div>
<?php get_footer(); ?>