<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>
	<?php include_once(APPROOT . '/views/inc/navbar.php'); ?>

	<div class="columns  is-centered">
		<div class="column is-half">
			<div class="card">
				<div class="card-image">
					<figure class="image is-3by2">
						<img src='<?php echo  URLROOT . "/public/img/posts/" . $data->post->picture_path; ?>' alt='<?php echo $data->post->title; ?>'>
					</figure>
				</div>
				<div class="card-content">
					<div class="media">
						<div class="media-content">
							<a class='level-item' aria-label='like'>
								<?php echo $data->countlike->nb . "   like"; ?>
							</a>
							<a class='level-item' aria-label='like'>
								<?php echo $data->countcomment->nb . "   comment"; ?>
							</a>
							<?php if ($data->user) : ?>
								<p class="title is-4"><?php echo $data->user->username; ?></p>
								<p class="subtitle is-6">@<?php echo $data->user->fullname; ?></p>
							<?php endif; ?>
						</div>
					</div>
					<div class="content">
						#Title :<b><?php echo $data->post->title ?></b><br>
						#Description :<b><?php echo $data->post->description ?></b>
						<br>
						<time datetime="2016-1-1"><?php echo $data->post->creation_date; ?></time><br><br>
						<?php if (isset($_SESSION['user_id'])) : ?>
							<?php if ($data->like) : ?>
								<a href='<?php echo URLROOT; ?>/posts/deletelike?id_post=<?php echo $data->post->id_post; ?>&view=1'><button id='unlike' name='unlike' value='Like' class='button is-success  is-medium  '>Unlike</button></a>
							<?php else : ?>
								<a href='<?php echo URLROOT; ?>/posts/addlike?id_post=<?php echo $data->post->id_post; ?>&view=1'><button id='like' name='like' value='Like' class='button is-success  is-medium  '>like</button></a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="column">
			  <?php flash('error-comment'); ?>
			  <?php if ($data->comments) : ?>
				<?php foreach ($data->comments as $comment) : ?>
					<article class="media">
						<div class="media-content">
							<div class="content">
								<p>
									<strong><?php echo $comment->username; ?></strong>
									<br>
									<?php echo $comment->comment; ?>
									<br>
									<small><?php echo $comment->created_at; ?></small>
								</p>
								<?php if (isset($_SESSION['user_id'])) : ?>
									<?php if ($comment->id_user == $_SESSION['user_id']) : ?>
										<a href='<?php echo  URLROOT . "/comments/deletecomment?id_post=" . $data->post->id_post . "&id_comment=" . $comment->id_comment ?>'><button class="button is-success">Delete</button></a>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
				<?php else : ?>
					<article class="media">
						<div class="media-content">
							<div class="content">
								<p>
									No Comment !!
								</p>
							</div>
						</div>
					</article>
				<?php endif; ?>
				<?php if (isset($_SESSION['user_id'])) : ?>
					<article class="media">
						<div class="media-content">
							<form method="POST" action="<?php echo URLROOT; ?>/comments/addcomment">
								<div class="field">
									<p class="control">
										<input type="hidden" class="input" name="id_post" value="<?php echo $data->post->id_post; ?>">
										<textarea class="textarea" name="comment" placeholder="Add a comment..."></textarea>
									</p>
								</div>
								<div class="field">
									<p class="control">
										<button class="button is-success">Post comment</button>
									</p>
								</div>
							</form>
						</div>
					</article>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php include_once(APPROOT . '/views/inc/footer.php'); ?>


	<script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>

</html>