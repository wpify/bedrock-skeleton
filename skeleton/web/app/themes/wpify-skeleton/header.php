<?php
use WpifySkeleton\Controllers\Views\HeaderViewController;

$controller = wpify_skeleton( HeaderViewController::class );

?><!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Hypo na míru s.r.o.">
    <meta name="theme-color" content="#142E4E">

    <!--	--><?php //echo $controller->scripts_header(); ?>
	<?php wp_head(); ?>

    <script type="text/javascript">
        var ajax = new XMLHttpRequest();
        ajax.open("GET", "<?php echo get_stylesheet_directory_uri(); ?>/images/sprites.svg", true);
        ajax.send();
        ajax.onload = function (e) {
            var div = document.createElement("div");
            div.innerHTML = ajax.responseText;
            div.style.display = "none";
            document.body.insertBefore(div, document.body.childNodes[0]);
        }
    </script>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'wpify_skeleton_body_open' ); ?>
<?php //echo $controller->scripts_body_start(); ?>
<?php wp_body_open(); ?>
<?php
//$controller = hyponamiru_container()->get( HeaderController::class );
echo $controller->get();
?>
<main class="site-content">