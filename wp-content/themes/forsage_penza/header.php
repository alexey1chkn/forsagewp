<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package forsage_Penza
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!--FROM MY PC-PROJECT-->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

		<!-- Icons -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<!--FONTS-->
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'forsage_penza' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php  echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php  echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$forsage_penza_description = get_bloginfo( 'description', 'display' );
			if ( $forsage_penza_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php  echo $forsage_penza_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<!-- <nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'forsage_penza' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav> #site-navigation -->

	</header><!-- #masthead -->
	
	<div id="content" class="site-content">
		<!--START CUSTOME CODE=====================================================================================-->
	<div class="wrapper">
		
		<div class="container-fluid">
			<!-- Header========================================================================================================================== -->
			<div class="top-bar">
				<div class="header">
					<div class="row align-items-center justify-content-around">			
						<div class="col-4">
							<div class="header-logo">
								<img src="<?php echo get_template_directory_uri();?>/assets/img/logo.svg" alt="">
							</div>
						</div>
						<div class="col-3">
							<a href="#map">
								<div class="header-map">
									<img src="<?php echo get_template_directory_uri();?>/assets/img/header-map_2.png" alt="">
									<span class="header-map-span_top">г. Пенза, Проспект победы 88</span>
									<div class="header-map-span_bottom">
										<span>Мы на карте</span>
										<span class="line">→</span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-2">
							<div class="header-platezhki">
								<img src="<?php echo get_template_directory_uri();?>/assets/img/platezhki.png" alt="">
								<span>Мы принимаем</span>
							</div>
						</div>
						<div class="col-1.5">
							<div class="header-cart">
								<a href="/cart"><img src="<?php echo get_template_directory_uri();?>/assets/img/Vector.svg" alt=""></a>
								<div class="header-cart-event">
									<span>
									<?php
										global $woocommerce; 
										 echo sprintf($woocommerce->cart->cart_contents_count); 
									?>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Contacts============================================================================================================== -->
				<div class="contacts">
					<div class="row">
						<div class="col-4">
							<div class="contacts-opening_hours">
								<div class="contacts-opening_hours-logo">
									<img src="<?php echo get_template_directory_uri();?>/assets/img/timer.svg" alt="">
								</div>
								<div class="contacts-opening_hours-ways">
									<div class="contacts-opening_hours-ways-first_way">Пн-Пт 8:30 - 19:00</div>
									<div class="contacts-opening_hours-ways-second_way">Сб. 8:30 - 17:00</div>
									<div class="contacts-opening_hours-ways-third_way">Вс. 8:30 - 16:00</div>
								</div>
								<div class="contacts-opening_hours-ways-check"></div>
							</div>
						</div>
						<div class="col-4">
							<div class="contacts-phone-numbers">
								<div class="contacts-phone-numbers-logo">
									<img src="<?php echo get_template_directory_uri();?>/assets/img/phone.svg" alt="">
								</div>
								<div class="contacts-phone-numbers-list">
									<div class="contacts-phone-numbers-list-nubmer-1">Отдел продаж: 8 (8412) 20-45-40</div>
									<div class="contacts-phone-numbers-list-nubmer-2">Шиномонтаж: 8 (8412) 20-45-39</div>
									<div class="contacts-phone-numbers-list-nubmer-3">Доп. номер:	8 (903) 323-56-06</div>
								</div>
								<div class="contacts-phone-numbers-list-nubmer-check"></div>
							</div>
						</div>
						<div class="col-4">
							<div class="contacts-email">forsage58@gmail.com</div>
						</div>
					</div>
				</div>
				<!-- Menu===================================================================================================================== -->
				<div class="row">
					<div class="col-12">
						<div class="main-menu">
							<nav class="main-menu-nav">
								<ul>
									<li><a href="/">Главная</a></li>
									<li><a href="/vybor-shin">Шины</a></li>
									<li><a href="/diski">Диски</a></li>
									<li><a href="/akb">АКБ</a></li>
									<li><a href="/krepezhi">Крепежи</a></li>
									<li><a href="/kolca">Кольца</a></li>
									<li><a href="/o-nas">О нас</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>