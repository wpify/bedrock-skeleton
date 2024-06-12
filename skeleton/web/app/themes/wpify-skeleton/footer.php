<?php

$controller = braasi(\WpifySkeleton\Controllers\Views\FooterViewController::class);
?>
</main>
<footer class="site-footer">
	<?php echo $controller->get();?>
</footer>
<div data-app="side-cart"></div>
<?php wp_footer(); ?>
</body>
</html>
