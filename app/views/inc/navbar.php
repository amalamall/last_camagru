
  <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="<?php echo URLROOT; ?>">
    <strong><?php echo SITENAME; ?></strong>
    </a>

    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu ">
    <div class="navbar-start">
    <a href="<?php echo URLROOT; ?>" class="navbar-item is-active"><strong>Home</strong></a>
    </div>

    <div class="navbar-end">
      <?php if (isset($_SESSION['user_id'])): ?>
      <div class="navbar-item">
        <div class="navbar-item is-active">
        <a href="<?php echo URLROOT; ?>/posts/mygalery" class="navbar-item is-active has-text-white">Gallery</a>
        <a href="<?php echo URLROOT; ?>/users/editprofile" class="navbar-item is-active has-text-white">Edit Profile</a>
        <a href="<?php echo URLROOT; ?>/users/changepassword" class="navbar-item is-active has-text-white">Change password</a>
          <a href="<?php echo URLROOT; ?>/posts/studio" class="navbar-item is-active has-text-white">Take a Picture</a>
          <a href="<?php echo URLROOT; ?>/posts/upload" class="navbar-item is-active has-text-white">Upload Picture</a>
          <a href="<?php echo URLROOT; ?>/users/logout" class="navbar-item is-active has-text-white">
            <strong>Logout</strong>
          </a>
        </div>
      </div>
      <?php else: ?>
      <div class="navbar-item">
        <div class="navbar-item is-active">
          <a href="<?php echo URLROOT; ?>/authentification/register" class="navbar-item is-active has-text-white">
            <strong>Register</strong>
          </a>
          <a href="<?php echo URLROOT; ?>/authentification/login" class="button is-success">
            Login
          </a>
        </div>
      </div>
      <?php endif;?>

    </div>

    </div>
  </div>
</nav>