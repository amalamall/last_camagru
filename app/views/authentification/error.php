<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>
<?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
<div class="container">
	<article id="message" class="message is-warning">
		<div class="message-header">
			<p>Warning</p>
			<button class="delete" aria-label="delete"></button>
		</div>
		<div class="message-body">
			something went wrong 
		</div>
	</article>

</div>
<?php include_once(APPROOT . '/views/inc/footer.php'); ?>



<script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>

</html>