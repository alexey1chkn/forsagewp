<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package forsage_Penza
 */

?>

	</div><!-- #content -->

</div><!-- #page -->		
		<div class="container">
			<!--FOOTER=============================================================================================================================================================-->
			<div class="footer-banner">
				<div class="row justify-content-xl-around justify-content-md-center align-items-center">
					<div class="col-xl-3 col-md-5">
						<div class="footer-banner-logo">
							<img src="<?echo get_template_directory_uri();?>/assets/img/logo.svg" alt="">
						</div>
					</div>
					<div class="col-xl-7 col-md-8">
						<div class="footer-banner-title"><span>Максимальный ассортимент</span><span>За минимальные цены</span></div>
					</div>
				</div>
			</div>
		<div class="bottom-bar">
			<div class="row">
				<div class="col-xl-12 col-md-0">
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
		</div>
		<footer>
			<div class="footer-copyright">© Шинный центр Форсаж, 2018 г. Все права защищены законодательством РФ</div>
			<div class="footer-developed-by">
				Сайт разработан командой<a href="#"><span>40digit</span></a>
			</div>
		</footer>
	</div>
</div>

<!-- Bootstrap core JavaScript ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="<?echo get_template_directory_uri();?>/assets/js/jquery-3.3.1.min.js"><\/script>')</script>
		<!-- <script src="../../../../assets/js/vendor/popper.min.js"></script>
		<script src="../../../../dist/js/bootstrap.min.js"></script> -->
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<!-- <script src="../../../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
	

<?php wp_footer(); ?>

</body>
</html>
