<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>
	<?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
	
	<article id="message" class="message is-warning">
		<div class="message-header">
			<p>Warning</p>
			<button class="delete" aria-label="delete"></button>
		</div>
		<div class="message-body">
			plz activate you webcam first
		</div>
	</article>

	<div class="columns is-centered">
		<div class="column   is-half">

			<div class="column" id="div_filter">
				<div id="filter_canvas">
				</div>
				<video id="video" playsinline autoplay>

				</video>

				<input type="text" class="input" id="title" placeholder="Enter a Title for this picture" name="title">
				<br><br>
				<textarea name="description" id="description" class="textarea" placeholder="Enter the description"></textarea>
				<div class="controller">
					<button id="snap" onclick=" savepic()" class="button is-info">Take the picture</button>
				</div>
			</div>	
			<div class="column  is-full" id="div_filter">

				<div class="columns is-centered   is-multiline">
					<div class="column  is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter1" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter1.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter1.png">
							</figure>


						</div>
					</div>
					<div class="column is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter2" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter2.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter2.png">
							</figure>
						</div>
					</div>
					<div class="column is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter3" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter3.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter3.png">
							</figure>
						</div>
					</div>
					<div class="column is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter4" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter4.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter4.png">
							</figure>
						</div>
					</div>
					<div class="column is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter5" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter5.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter5.png">
							</figure>
						</div>
					</div>
					<div class="column is-one-third">
						<div class="box">
							<label class="radio">
								<input type="radio" class="checkbox" id="filter6" name="filter" value="<?php echo URLROOT; ?>/public/img/filters/filter6.png" onclick='add_filter()'>
							</label>
							<figure class="image is-square">
								<img src="<?php echo URLROOT; ?>/public/img/filters/filter6.png">
							</figure>
						</div>
					</div>
				</div>
			</div>

			<div class="column is-narrow is-vcentered" id="studio">

			</div>
		</div>
		<div class="is-divider-vertical" data-content="OR"></div>
		<div class="column is-one-third">
			<?php flash('error-post'); ?>
			<?php foreach ($data['posts'] as $post) : ?>
				<figure class="image is-3by2">
					<img src="<?php echo URLROOT; ?>/public/img/posts/<?php echo $post->picture_path; ?>">
					<a href="<?php echo URLROOT; ?>/posts/deleteimage?id_post=<?php echo $post->id_post; ?>&path=<?php echo dirname(APPROOT); ?>/public/img/posts/<?php echo $post->picture_path; ?>" class="delete is-large is-danger"></a>
				</figure>
				<br>
			<?php endforeach; ?>
		</div>
	</div>



	<?php include_once(APPROOT . '/views/inc/footer.php'); ?>

	<script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/studio.js"></script>
	<script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>

</html>