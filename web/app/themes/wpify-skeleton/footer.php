<?php
use WpifySkeleton\Controllers\FooterController;

$controller = wpify_skeleton_container()->get( FooterController::class );
?>
</main>
<footer class="site-footer">
	<?php dynamic_sidebar( 'footer' ); ?>
    <p>
	    <?php echo $controller->copyright() ?>
    </p>
</footer>
<?php wp_footer(); ?>
<?php echo $controller->scripts_body_end() ?>
</body>
</html>
