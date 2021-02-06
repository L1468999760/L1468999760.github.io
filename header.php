<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' );?>" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" rel="stylesheet" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script( 'jquery' );
	wp_head();
?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/images/jquery.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/comments-ajax.js"></script>
</head>
<body class="home blog">
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		<a class="site-logo" href="<?php bloginfo( 'url' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/><img class="no-grav" src="<?php echo get_option( 'logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a>
		<hgroup>
		<h1 class="site-title"><a href="<?php echo get_option('home'); ?>" title="<?php bloginfo('name'); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
			<h2 class="site-description"><?php bloginfo('description'); ?></h2>	
		</hgroup>

		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text">Menu</h1>
			<div class="menu-navigation-container">
				<?php $top_nav = wp_nav_menu( array( 'theme_location'=>'cl', 'fallback_cb'=>'', 'container'=>'', 'menu_id'=>'menu-navigation', 'menu_class'=>'menu', 'echo'=>false, 'after'=>'' ) );$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );echo $top_nav;?>
			</div>	
		</nav>
	</header>