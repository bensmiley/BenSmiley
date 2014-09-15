<?php
/*template name: Front Page */
get_header(); ?>
	
<?php $options = get_option('salient'); ?>



<!-- <div class="row transparent">
			<div class="search_area">
			<label class="text_white">You Can Sign Up For Our Free Version</label>
			<a href="http://chatcat.io/home/" class="messg_btn">Try It Now!</a>
			</div>
		</div> -->
<div class="home-wrap">
		
	<div class="container main-content">

		<div class="row">
	
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
	
			<?php endwhile; endif; ?>
				
		</div><!--/row-->

	</div><!--/container-->

</div><!--/home-wrap-->
	
<?php get_footer(); ?>