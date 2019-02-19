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
		<div class="bottom-bar">
			<div class="row">
				<div class="col-xl-12 col-md-0">
					<div class="main-menu">
						<nav class="main-menu-nav">
							<ul>
								<li><a href="#">Главная</a></li>
								<li><a href="#">Шины</a></li>
								<li><a href="#">Диски</a></li>
								<li><a href="#">АКБ</a></li>
								<li><a href="#">Крепежи</a></li>
								<li><a href="#">Кольца</a></li>
								<li><a href="#">О нас</a></li>
							</ul>
						</nav>
						<a href="#" class="main-menu-btn">
							<span></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<div class="footer-copyright">© Шинный центр Форсаж, 2018 г. Все права защищены законодательством РФ</div>
			<div class="footer-developed-by">
				Сайт разработан командой<a href="https://vk.com/wiesg"><span>40digit</span></a>
			</div>
		</footer>
	</div>


<!-- Bootstrap core JavaScript ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
		<script src="<?php echo get_template_directory_uri();?>/assets/bootstrap/js/bootstrap.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri();?>/assets/js/jquery-3.3.1.min.js"><\/script>')</script>

<script>
	$('.main-menu-btn').on('click', function(e) {
  e.preventDefault();
  $(this).toggleClass('main-menu-btn_active');
  $('.main-menu-nav').toggleClass('main-menu-nav_active');
});
</script>

<?php wp_footer(); ?>
</body>
</html>
