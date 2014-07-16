<!doctype html>
<html <?php language_attributes(); ?>>
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php $options = get_option('salient'); ?>

<?php if(!empty($options['responsive']) && $options['responsive'] == 1) { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

<?php } else { ?>
	<meta name="viewport" content="width=1200" />
<?php } ?>	

<!--Shortcut icon-->
<?php if(!empty($options['favicon'])) { ?>
	<link rel="shortcut icon" href="<?php echo nectar_options_img($options['favicon']); ?>" />
<?php } ?>


<title> <?php wp_title("|",true, 'right'); ?> <?php if (!defined('WPSEO_VERSION')) { bloginfo('name'); } ?></title>

<?php wp_head(); ?>

</head>

<?php
 global $post; 
 global $woocommerce; 

if($woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag()) {
	$header_title = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_title', true);
	$header_bg = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_bg', true);
	$header_bg_color = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_bg_color', true);
} 
else if(is_home() || is_archive()){
	$header_title = get_post_meta(get_option('page_for_posts'), '_nectar_header_title', true);
	$header_bg = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg', true); 
	$header_bg_color = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg_color', true);
}  else {
	$header_title = get_post_meta($post->ID, '_nectar_header_title', true);
	$header_bg = get_post_meta($post->ID, '_nectar_header_bg', true); 
	$header_bg_color = get_post_meta($post->ID, '_nectar_header_bg_color', true);
}

//check if parallax nectar slider is being used
$parallax_nectar_slider = using_nectar_slider();
$force_effect = get_post_meta($post->ID, '_force_transparent_header', true);

$logo_class = (!empty($options['use-logo']) && $options['use-logo'] == '1') ? null : 'class="no-image"'; 

?>

<body <?php body_class(); ?> data-bg-header="<?php echo (!empty($header_bg) || !empty($header_bg_color) || !empty($header_title) || $parallax_nectar_slider == 1 || $force_effect == 'on') ? 'true' : 'false'; ?>" data-ext-responsive="<?php echo (!empty($options['responsive']) && $options['responsive'] == 1 && !empty($options['ext_responsive']) && $options['ext_responsive'] == '1') ? 'true' : 'false'; ?>" data-header-resize="<?php if(!empty($options['header-resize-on-scroll'])) { echo $options['header-resize-on-scroll']; } else { echo '0'; } ?>" data-header-color="<?php echo (!empty($options['header-color'])) ? $options['header-color'] : 'light' ; ?>" <?php echo (!empty($options['transparent-header']) && $options['transparent-header'] == '1') ? null : 'data-transparent-header="false"'; ?> data-smooth-scrolling="<?php echo $options['smooth-scrolling']; ?>" data-responsive="<?php echo (!empty($options['responsive']) && $options['responsive'] == 1) ? '1'  : '0' ?>" >

<?php if(!empty($options['boxed_layout']) && $options['boxed_layout'] == '1') { echo '<div id="boxed">'; } ?>

<?php $using_secondary = (!empty($options['header_layout'])) ? $options['header_layout'] : ' '; 

if($using_secondary == 'header_with_secondary') { ?>

	<div id="header-secondary-outer" data-full-width="<?php echo (!empty($options['header-fullwidth']) && $options['header-fullwidth'] == '1') ? 'true' : 'false' ; ?>">
		<div class="container">
			<nav class=" ">
				<?php if(!empty($options['enable_social_in_header']) && $options['enable_social_in_header'] == '1') { ?>
					<ul id="social">
						<?php  if(!empty($options['use-twitter-icon-header']) && $options['use-twitter-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['twitter-url']; ?>"><i class="icon-twitter"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-facebook-icon-header']) && $options['use-facebook-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['facebook-url']; ?>"><i class="icon-facebook"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-vimeo-icon-header']) && $options['use-vimeo-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['vimeo-url']; ?>"><i class="icon-vimeo"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-pinterest-icon-header']) && $options['use-pinterest-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['pinterest-url']; ?>"><i class="icon-pinterest"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-linkedin-icon-header']) && $options['use-linkedin-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['linkedin-url']; ?>"><i class="icon-linkedin"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-youtube-icon-header']) && $options['use-youtube-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['youtube-url']; ?>"><i class="icon-youtube"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-tumblr-icon-header']) && $options['use-tumblr-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['tumblr-url']; ?>"><i class="icon-tumblr"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-dribbble-icon-header']) && $options['use-dribbble-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['dribbble-url']; ?>"><i class="icon-dribbble"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-rss-icon-header']) && $options['use-rss-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo (!empty($options['rss-url'])) ? $options['rss-url'] : get_bloginfo('rss_url'); ?>"><i class="icon-rss"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-github-icon-header']) && $options['use-github-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['github-url']; ?>"><i class="icon-github-alt"></i></a></li> <?php } ?>
						<?php  if(!empty($options['use-behance-icon-header']) && $options['use-behance-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['behance-url']; ?>"><i class="icon-be"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-google-plus-icon-header']) && $options['use-google-plus-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['google-plus-url']; ?>"><i class="icon-google-plus"></i> </a></li> <?php } ?>
						<?php  if(!empty($options['use-instagram-icon-header']) && $options['use-instagram-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['instagram-url']; ?>"><i class="icon-instagram"></i></a></li> <?php } ?>
						<?php  if(!empty($options['use-stackexchange-icon-header']) && $options['use-stackexchange-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['stackexchange-url']; ?>"><i class="icon-stackexchange"></i></a></li> <?php } ?>
						<?php  if(!empty($options['use-soundcloud-icon-header']) && $options['use-soundcloud-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $options['soundcloud-url']; ?>"><i class="icon-soundcloud"></i></a></li> <?php } ?>
					</ul>
				<?php } ?>
				
				<?php if(has_nav_menu('secondary_nav')) { ?>
					<ul class="sf-menu">	
				   	   <?php wp_nav_menu( array('walker' => new Nectar_Arrow_Walker_Nav_Menu, 'theme_location' => 'secondary_nav', 'container' => '', 'items_wrap' => '%3$s' ) ); ?>
				    </ul>
				<?php }	?>
				
			</nav>
		</div>
	</div>

<?php } ?>

<div id="header-space"></div>

<?php 
	// header transparent option
	$transparency_markup = null;
	$activate_transparency = null;
	
	if(!empty($options['transparent-header']) && $options['transparent-header'] == '1') {
		
		$starting_color = (empty($options['header-starting-color'])) ? '#ffffff' : $options['header-starting-color'];
		$activate_transparency = using_page_header($post->ID);
		
		$transparency_markup = ($activate_transparency == 'true') ? 'data-transparent-header="true" class="transparent"' : null ;
	}
	
?>

<div id="header-outer" <?php echo $transparency_markup; ?> data-full-width="<?php echo (!empty($options['header-fullwidth']) && $options['header-fullwidth'] == '1') ? 'true' : 'false' ; ?>" data-using-secondary="<?php echo ($using_secondary == 'header_with_secondary') ? '1' : '0'; ?>" data-using-logo="<?php if(!empty($options['use-logo'])) echo $options['use-logo']; ?>" data-logo-height="<?php if(!empty($options['logo-height'])) echo $options['logo-height']; ?>" data-padding="<?php echo (!empty($options['header-padding'])) ? $options['header-padding'] : "28"; ?>" data-header-resize="<?php if(!empty($options['header-resize-on-scroll'])) echo $options['header-resize-on-scroll']; ?>">
	
	<?php get_template_part('includes/header-search'); ?>
	
	<header id="top">
		
		<div class="container">
			
			<div class="row">
				  
				<div class="col span_3">
					
					<a id="logo" href="<?php echo home_url(); ?>" <?php echo $logo_class; ?>>
						
						<?php if(!empty($options['use-logo'])) {
							
								$default_logo_class = (!empty($options['retina-logo'])) ? 'default-logo' : null;
								
								 echo '<img class="'.$default_logo_class.'" alt="'. get_bloginfo('name') .'" src="' . nectar_options_img($options['logo']) . '" />';
								 
								 if(!empty($options['retina-logo'])) echo '<img class="retina-logo" alt="'. get_bloginfo('name') .'" src="' . nectar_options_img($options['retina-logo']) . '" />';
							 	 
								 //starting logo 
								 if($activate_transparency == 'true'){
								 	
								 	 if(!empty($options['header-starting-logo'])) echo '<img class="starting-logo '.$default_logo_class.'"  alt="'. get_bloginfo('name') .'" src="' . nectar_options_img($options['header-starting-logo']) . '" />';
									 if(!empty($options['header-starting-retina-logo'])) echo '<img class="retina-logo starting-logo" alt="'. get_bloginfo('name') .'" src="' . nectar_options_img($options['header-starting-retina-logo']) . '" />';
									 
								 }
								 
							 } else { echo get_bloginfo('name'); } ?> 
					</a>

				</div><!--/span_3-->
				
				<div class="col center span_9">
					
					<a href="#" id="toggle-nav"><i class="icon-reorder"></i></a>
					
					<?php if (!empty($options['enable-cart']) && $options['enable-cart'] == '1') { 
						if ($woocommerce) { ?> 
							<!--mobile cart link-->
							<a id="mobile-cart-link" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><i class="icon-salient-cart"></i></a>
						<?php } 
					} ?>

                <?php

                if ( is_user_logged_in() ):
                    $userdata = wp_get_current_user();?>
						
					<!--admin signin part start-->
					
     <div  class="pull-right m-l-20">
                <div class="user-profile pull-left">
                    <img src="http://1.gravatar.com/avatar/5ba2411c7d1e867264c0dcec5a160a46?s=64&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D64&amp;r=G" alt="" width="35" height="35" id="user-photo">
                </div>
                <ul  id="menu1" class="menu nav quick-section ">
                    <li class="quicklinks btn">
                        <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="javascript:void(0)" id="user-options">
                            <div class="pull-left">
                                <span class=" display_name"><?php echo $userdata->display_name;?></span>
                            </div>
                        <span class="sf-sub-indicator" style="height: 16px;"><i class="icon-angle-down"></i></span>
                        </a>
						<ul id="popup" class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
							<li>
								<a href="#logout" id="logout"> <i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out </a>
							</li>
						</ul>
                    </li>
                </ul>
            </div>
                <?php endif; ?>
			<!--<div class="signup"><div><a href="home">signup</a></div></div>-->
            <!--admin signin part ends-->
            <nav class="alignright ">
                <ul class="sf-menu">
                    <?php
                    if(has_nav_menu('top_nav')) {
                        wp_nav_menu( array('walker' => new Nectar_Arrow_Walker_Nav_Menu, 'theme_location' => 'top_nav', 'container' => '', 'items_wrap' => '%3$s' ) );
                    }
                    else {
                        echo '<li><a href="">No menu assigned!</a></li>';
                    }
                    ?>
                    <li id="search-btn" ><div><a href=""><span class="icon-salient-search" aria-hidden="true"></span></a></div></li>
					<li id="sign-btn" ><div><a href=""><span class="icon-unlock-alt" aria-hidden="true"></span>Sign In</a></div></li>
                </ul>
            </nav>
					
					
				</div><!--/span_9-->
				<!--<div class="col span_3 col_last">
					<!--<div class="user_img">
							<img class="user" src="<?php bloginfo('wpurl'); ?>/wp-content/themes/ben-smiley/img/user.png"/>
					</div>
					<div class="welcome_txt"><span>Welcome Ben Smiley</span></div>-->
					<!--<div class="sign_in"><a href="http://www.bensmiley.ajency.in/home ">Sign in</a></div>
					<div class="sign_up"><a href="http://www.bensmiley.ajency.in/home ">Sign up</a></div>
				</div>-->
			
			</div><!--/row-->
			
		</div><!--/container-->
		
	</header>
	
	
	<?php if (!empty($options['enable-cart']) && $options['enable-cart'] == '1') { ?>
		<?php
		if ($woocommerce) { ?>
			
		<div class="cart-outer">
			<div class="cart-menu-wrap">
				<div class="cart-menu">
					<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><div class="cart-icon-wrap"><i class="icon-shopping-cart"></i> <div class="cart-wrap"><span><?php echo $woocommerce->cart->cart_contents_count; ?> </span></div> </div></a>
				</div>
			</div>
			
			<div class="cart-notification">
				<span class="item-name"></span> <?php echo __('was successfully added to your cart.', NECTAR_THEME_NAME); ?>
			</div>
			
			<?php
				// Check for WooCommerce 2.0 and display the cart widget
				if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
					the_widget( 'WC_Widget_Cart', 'title= ' );
				} else {
					the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
				}
			?>
				
		</div>
		
	 <?php } 
	 
   } 
   
   
   $using_fw_slider = using_nectar_slider();
   $using_fw_slider = (!empty($options['transparent-header']) && $options['transparent-header'] == '1') ? $using_fw_slider : 0;
   
   if($using_fw_slider) echo '<div class="ns-loading-cover"></div>';
   
   ?>		
	

</div><!--/header-outer-->


<div id="mobile-menu">
	
	<div class="container">
		<ul>
			<?php 
				if(has_nav_menu('top_nav')) {
					
				    wp_nav_menu( array('theme_location' => 'top_nav', 'menu' => 'Top Navigation Menu', 'container' => '', 'items_wrap' => '%3$s' ) ); 
					
					echo '<li id="mobile-search">  
					<form action="'.home_url().'" method="GET">
			      		<input type="text" name="s" value="" placeholder="'.__('Search..', NECTAR_THEME_NAME) .'" />
					</form> 
					</li>';
				}
				else {
					echo '<li><a href="">No menu assigned!</a></li>';
				}
			?>		
		</ul>
	</div>
	
</div>
