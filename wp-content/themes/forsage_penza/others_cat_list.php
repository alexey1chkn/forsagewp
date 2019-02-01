<?php 

/*
	*Template Name: others_cat_list
	*Template Post Type: post, page, product
*/

get_header();
// Ensure visibility.
?>

<div class="row justify-content-around">
	<div class="col-2.5">
		<div class="category-container">
			<a href="/akb/">
				<img src="<?php echo get_template_directory_uri();?>/assets/img/akb.jpg" alt="">
				<span>Аккумуляторы</span>
			</a>
		</div>
	</div>
	<div class="col-2.5">
		<div class="category-container">
			<a href="/krepezhi/">
				<img src="<?php echo get_template_directory_uri();?>/assets/img/akb.jpg" alt="">
				<span>Крепежи</span>
			</a>
		</div>
	</div>
		<div class="col-2.5">
		<div class="category-container">
			<a href="/kolca/">
				<img src="<?php echo get_template_directory_uri();?>/assets/img/akb.jpg" alt="">
				<span>Кольца</span>
			</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>