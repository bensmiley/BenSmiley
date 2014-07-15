<?php 
/*template name: Contact*/
get_header(); ?>


<?php nectar_page_header($post->ID);  ?>

<?php 
$options = get_option('salient'); 



wp_enqueue_script('googleMaps', 'https://maps.google.com/maps/api/js?sensor=false', NULL, NULL, TRUE);
wp_enqueue_script('nectarMap', get_template_directory_uri() . '/js/map.js', array('jquery', 'googleMaps'), '1.0', TRUE);


?>

<div class="container-wrap">

	<div id="contact-map" class="nectar-google-map" data-greyscale="<?php if(!empty($options['map-greyscale'])) echo $options['map-greyscale']; ?>" data-extra-color="<?php if(!empty($options['map-color'])) echo $options['map-color']; ?>" data-enable-animation="<?php if(!empty($options['enable-map-animation'])) echo $options['enable-map-animation']; ?>" data-enable-zoom="<?php if(!empty($options['enable-map-zoom'])) echo $options['enable-map-zoom']; ?>" data-zoom-level="<?php if(!empty($options['zoom-level'])) echo $options['zoom-level']; ?>" data-center-lat="<?php if(!empty($options['center-lat'])) echo $options['center-lat']; ?>" data-center-lng="<?php if(!empty($options['center-lng'])) echo $options['center-lng']; ?>" data-marker-img="<?php if(!empty($options['marker-img'])) echo $options['marker-img']; ?>"></div>
	
	<div class="map-marker-list contact-map">
		<?php
			$count = 0;

			for($i = 1; $i <= 10; $i++){
				if(!empty($options['map-point-'.$i]) && $options['map-point-'.$i] != 0 ) {
					$count++;

					echo '<div class="map-marker" data-lat="'.$options['latitude'.$i].'" data-lng="'.$options['longitude'.$i].'" data-mapinfo="'.$options['map-info'.$i].'"></div>';
				}	
			}
		?>
		
	</div>
	
	<div class="container main-content">
		
		<div class="row">
	
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
	
			<?php endwhile; endif; ?>
				
	
		</div><!--/row-->
		
	</div><!--/container-->

</div>
<?php get_footer(); ?>