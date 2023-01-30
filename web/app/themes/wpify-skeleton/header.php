<?php
use WpifySkeleton\Controllers\HeaderController;

$controller = wpify_skeleton_container()->get( HeaderController::class );
?><!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php echo $controller->scripts_header(); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php echo $controller->scripts_body_start(); ?>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="logo">
		<?php
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}
		?>
    </div>
    <nav class="main-navigation">
        <ul>
			<?php foreach ( $controller->menu() as $item ): ?>
                <li>
                    <a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a>
                </li>
			<?php endforeach; ?>
        </ul>
    </nav>
</header>
<main class="site-content">