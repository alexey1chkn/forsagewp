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
				      <img class="d-block w-100" src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="Second slide">
				    </div>
				    <div class="carousel-item">
				      <img class="d-block w-100" src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="Third slide">
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
					<div class="col-lg-6 col-12">
						<form action="/vybor-shin" method="GET">
							<div class="goods_selection-tires_main">
								<div class="row">
									<div class="col-7">								
										<div class="goods_selection-tires_main-characters">
											<ul>
												<li>
													<div class="characters">
														<span>Ширина</span>								
														<select name="shirina-shiny">
															<option disabled selected> - </option>
															<!-- values: 10.00, 11.00, 6.45, 7.0, 7.5, 8.25, 8.4, 9.00 -->
															<option value="31">31</option>
															<option value="32">32</option>
															<option value="33">33</option>
															<option value="35">35</option> 
															<option value="135">135</option>
															<option value="155">155</option>
															<option value="165">165</option>
															<option value="175">175</option>
															<option value="185">185</option>
															<option value="195">195</option>
															<option value="205">205</option>
															<option value="215">215</option>
															<option value="225">225</option>
															<option value="235">235</option>
															<option value="245">245</option>
															<option value="255">255</option>
															<option value="260">260</option>
															<option value="265">265</option>
															<option value="275">275</option>
															<option value="280">280</option>
															<option value="285">285</option>
															<option value="295">295</option>
															<option value="300">300</option>
															<option value="305">305</option>
															<option value="315">315</option>
															<option value="385">385</option>
															<option value="386">386</option>
														</select>
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Высота</span>
														<select name="profil-shiny">
															<option disabled selected> - </option>
															<option value="6.45">6.45</option>		
															<option value="9.5">9.5</option>
															<option value="10.50">10.5</option>
															<option value="12.50">12.50</option>
															<option value="22.5">22.5</option>
															<option value="35">35</option>
															<option value="40">40</option>
															<option value="45">45</option>
															<option value="50">50</option>
															<option value="55">55</option>
															<option value="60">60</option>
															<option value="65">65</option>
															<option value="70">70</option>
															<option value="75">75</option>
															<option value="80">80</option>
															<option value="85">85</option>
															<option value="90">90</option>
															<option value="508">508</option>
															<option value="R1">R1</option>
														</select>
													</div>
												</li>
												<li>
													<div class="characters">
														<span>Диаметр</span>
														<select name="posadochnyj-diametr">
															<option disabled selected> - </option>	
															<option value="12">12</option>
															<option value="13">13</option>
															<option value="13С">13С</option>
															<option value="14">14</option>
															<option value="14C">14C</option>
															<option value="15">15</option>
															<option value="15C">15C</option>
															<option value="16">16</option>
															<option value="16C">16C</option>
															<option value="17">17</option>
															<option value="18">18</option>
															<option value="19">19</option>
															<option value="17.5">17.5</option>
															<option value="19.5">19.5</option>
															<option value="20">20</option>
															<option value="21">21</option>
															<option value="22">22</option>
															<option value="22.5">22.5</option>
														</select>												
													</div>
												</li>
												<li>
													<div class="characters">
														<div class="characters-checkbox">
															<img src="<?php echo get_template_directory_uri();?>/assets/img/cold.svg" alt="">
															<input type="checkbox" id="winter" name="winter" checked>
															<label for="winter"></label>
														</div>
														<div class="characters-checkbox">
															<img src="<?php echo get_template_directory_uri();?>/assets/img/sun.svg" class="summer" alt="">
															<input type="checkbox" id="summer" name="summer">
															<label for="summer"></label>
														</div>
													</div>
												</li>
												<li>
													<div class="characters">													
														<div class="characters-checkbox spikes">
															<span>Шипы</span>
															<input type="checkbox" id="spikes" name="spikes">
															<label for="spikes"></label>
														</div>
													</div>
												</li>
											</ul>										
										</div>
									</div>
									<div class="col-5">
										<div class="goods_selection-tires_main-visual">
											<span>Подбор шин</span>
											<img src="<?php echo get_template_directory_uri();?>/assets/img/tire_main_1.svg" alt="">
											<input type="submit" value="Подобрать">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!--END FORM=================================================================================================================-->
					<div class="col-lg-6 col-12">
						<form action="/diski" method="GET">
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
															<option value="17-5">17,5</option>
															<option value="18">18</option>
															<option value="19">19</option>
															<option value="20">20</option>
														</select>	
													</div>
												</li>
												<li>										
													<div class="characters">
														<span>PCD</span>
														<select name="pcd">
															<option disabled selected> ОШИБКА! </option>
															<option value="3">3</option>
															<option value="4-98">4/98</option>
															<option value="4-98-100">4/98-100</option>
															<option value="4-100">4/100</option>
															<option value="4-100-114">4/100-114</option>
															<option value="4-114">4/114</option>
															<option value="4-108">4/108</option>
															<option value="5-100">5/100</option>
															<option value="5-105">5/105</option>
															<option value="5-108">5/108</option>
															<option value="5-110">5/110</option>
															<option value="5-100-112">5/100-112</option>
															<option value="5-112">5/112</option>
															<option value="5-114">5/114</option>
															<option value="5-115">5/115</option>
															<option value="5-118">5*118</option>
															<option value="5-120">5/120</option>
															<option value="5-130">5/130</option>
															<option value="5-139">5/139</option>
															<option value="5-150">5/150</option>
															<option value="5-160">5*160</option>
															<option value="6-114">6/114</option>
															<option value="6-130">6/130</option>
															<option value="6-139">6/139</option>
															<option value="6-170">6*170</option>
															<option value="7">7</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Ст.отверстие</span>
														<select name="posadochnyj-diametr">
															<option disabled selected> - </option>
															<option value="50">50</option>
															<option value="52-5">52,5</option>
															<option value="54">54</option>
															<option value="54-1">54,1</option>
															<option value="56-1">56,1</option>
															<option value="56-5">56,5</option>
															<option value="56-6">56,6</option>
															<option value="56-7">56,7</option>
															<option value="57">57</option>
															<option value="57-1">57,1</option>
															<option value="58-1">58,1</option>
															<option value="58-5">58,5</option>
															<option value="58-6">58,6</option>
															<option value="60">60</option>
															<option value="60-1">60,1</option>
															<option value="61-1">61,1</option>
															<option value="63-3">63,3</option>
															<option value="63-4">63,4</option>
															<option value="64-1">64,1</option>
															<option value="65-1">65,1</option>
															<option value="66-1">66,1</option>
															<option value="66-6">66,6</option>
															<option value="67">67</option>
															<option value="67-1">67,1</option>
															<option value="69-1">69,1</option>
															<option value="70-1">70,1</option>
															<option value="70-2">70,2</option>
															<option value="70-3">70,3</option>
															<option value="70-6">70,6</option>
															<option value="71-1">71,1</option>
															<option value="71-5">71,5</option>
															<option value="71-6">71,6</option>
															<option value="72-5">v</option>
															<option value="72-6">72,6</option>
															<option value="73">73</option>
															<option value="73-1">73,1</option>
															<option value="73-2">73,2</option>
															<option value="74-1">74,1</option>
															<option value="84-1">84,1</option>
															<option value="89-1">89,1</option>
															<option value="92-5">92,5</option>
															<option value="98">98</option>
															<option value="98-5">98,5</option>
															<option value="98-6">98,6</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Вылет ЕТ</span>
														<select name="vylet-diska">
															<option disabled selected> - </option>
															<!-- -10, 102, 106, 3, 4  -->
															<option value="15">15</option>
															<option value="16">16</option>
															<option value="18">18</option>
															<option value="20">20</option>
															<option value="23">23</option>
															<option value="24">24</option>
															<option value="25">25</option>
															<option value="26">26</option>
															<option value="28">28</option>
															<option value="30">30</option>
															<option value="31">31</option>
															<option value="32">32</option>
															<option value="33">33</option>
															<option value="34">34</option>
															<option value="35">35</option>
															<option value="36">36</option>
															<option value="37">37</option>
															<option value="37-5">37,5</option>
															<option value="38">38</option>
															<option value="39">39</option>
															<option value="40">40</option>
															<option value="41">41</option>
															<option value="42">42</option>
															<option value="43">43</option>
															<option value="44">44</option>
															<option value="45">45</option>
															<option value="46">46</option>
															<option value="47">47</option>
															<option value="48">48</option>
															<option value="49">49</option>
															<option value="50">50</option>
															<option value="51">51</option>
															<option value="52">52</option>
															<option value="52-5">52,5</option>
															<option value="53">53</option>
															<option value="54">54</option>
															<option value="55">55</option>
															<option value="56">56</option>
															<option value="57">57</option>
															<option value="58">58</option>
															<option value="60">60</option>
															<option value="62">62</option>
															<option value="63">63</option>
															<option value="66">66</option>
															<option value="67">67</option>
															<option value="68">68</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Ширина диска</span>
														<select name="shirina-diska">
															<option disabled selected> - </option>
															<option value="4">4</option>
															<option value="4-5-j">4.5J</option>
															<option value="5-0-j">5.0J</option>
															<option value="5-5-j">5.5J</option>
															<option value="6-0-j">6.0J</option>
															<option value="6-5">6.5</option>
															<option value="6-5-j">6.5J</option>
															<option value="7-0-j">7.0J</option>
															<option value="7-5-j">7.5J</option>
															<option value="8-0-j">8.0J</option>
															<option value="8-5-j">8.5J</option>
															<option value="9-0j">9.0J</option>
															<option value="9-5">9.5</option>
															<option value="10">10</option>
															<option value="10-5">10.5</option>
														</select>	
													</div>
												</li>
												<li>												
													<div class="characters">
														<span>Тип диска</span>
														<select>
															<option value="">Литой</option>
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
											<input type="submit" value="Подобрать">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
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
										<a href="/drugie-tovary/">Показать</a>
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
				<div id="map"></div>
			</div>
			<!--FOOTER=============================================================================================================================================================-->
			<div class="footer-banner">
				<div class="row justify-content-xl-around justify-content-center align-items-center">
					<div class="col-3"><!-- col-md-5 -->
						<div class="footer-banner-logo">
							<img src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="">
						</div>
					</div>
					<div class="col-7"><!--  col-md-8 -->
						<div class="footer-banner-title">
							<span>Максимальный ассортимент</span><br>
							<span>За минимальные цены</span>
						</div>
					</div>
				</div>
			</div>
<?php get_footer(); ?>