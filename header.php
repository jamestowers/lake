<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="Copyright" content="Copyright &copy; <?php bloginfo('name'); ?> <?php echo date('Y'); ?>. All Rights Reserved.">

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="350">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scallable=no, minimal-ui"/>

		<!-- APPLE TAGS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <meta name="google-site-verification" content="-R7_4pXCiCiCBxxnXVC66Q65J6N_dEvojjNIbrYX4_c" />

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/manifest.json">
    <link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/icons/mstile-144x144.png">
    <meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/library/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- STANDARD META TAGS -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,800,400italic' rel='stylesheet' type='text/css'>


		<title><?php wp_title(''); ?></title>

		
		<?php wp_head(); ?>

	</head>

	<body>
    <a href="#" id="menu-toggle"><span></span></a>
	<div id="wrapper" class="group">      
        <div <?php body_class();?> >
            <header class="header group" role="banner">
                <a href="<?php echo get_bloginfo('url');?>" id="logo"><?php echo logo();?></a>

                <?php dropshop_social_icons();?>

                <?php if( wp_is_mobile() ){
                    echo '<nav role="navigation" id="nav-header">';
                        dropshop_nav_header();
                    echo '</nav>';
                } ?>
            </header>

  			<div id="content" class="group">