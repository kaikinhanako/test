<!-- jQuery include -->
<?= tag_script_js(URL_JQUERY); ?> 

<!-- Incliude Twitter share button widgets -->
<?= tag_script_js(URL_TWITTER_WIDGETS); ?> 
<?= tag_script_js(base_url(PATH_JQUERY_TEXT_CONVERT)); ?> 
<?= tag_script_js(base_url(PATH_BOOTSTRAP_JS)); ?> 

<!-- js of act on all page-->
<?= tag_script_js(base_url(PATH_JS . 'script.js')); ?>

<div class="facebook-btn">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.0";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
</div>

<?php
if (!empty($jss)) {
	foreach ($jss as $js) {
		?>
		<script src="<?= base_url(PATH_JS . "{$js}.js") ?>" type="text/javascript"></script>
		<script>
			window.jQuery || document.write('<script src="<?= URL_JQUERY_OFFLINE ?>"></script>');
		</script>
		<?php
	}
}
?>
</body>
</html>