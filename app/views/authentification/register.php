<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

  <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
        <div class="column">
          <form class="box" action="<?php echo URLROOT; ?>/authentification/register" method="post">

            <p class="title">
              Not a member yet what are you waiting Subscribe Now?
            </p>
            <p class="subtitle">
              Sign up to continue.
            </p>
            <div class="field">
              <label class="label">full name</label>
              <div class="control">
                <input type="text" name="fullname" class="input <?php echo (!empty($data['fullname_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['fullname']; ?>" placeholder="Full Name">
                <p class="help is-danger"><?php echo $data['fullname_err']; ?></p>
              </div>
            </div>
            <div class="field">
              <label class="label">Username</label>
              <div class="control">
                <input type="text" name="username" class="input <?php echo (!empty($data['username_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['username']; ?>" placeholder="Name">
                <p class="help is-danger"><?php echo $data['username_err']; ?></p>
              </div>
            </div>
            <div class="field">
              <label class="label">Email</label>
              <div class="control">
                <input type="email" name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Email address">
                <p class="help is-danger"><?php echo $data['email_err']; ?></p>
              </div>
            </div>
            <div class="field">
              <label class="label">Password</label>
              <div class="control">
                <input type="password" name="password" class="input <?php echo (!empty($data['password_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="My password" autocomplete>
                <p class="help is-danger"><?php echo $data['password_err']; ?></p>
              </div>
            </div>
            <div class="field">
              <label class="label">Confirm Password: <sup>*</sup></label>
              <div class="control">
                <input type="password" name="confirm_password" class="input <?php echo (!empty($data['confirm_password_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['confirm_password']; ?>" placeholder="Confirm password" autocomplete>
                <p class="help is-danger"><?php echo $data['confirm_password_err']; ?></p>
              </div>
            </div>
            <h6 class="subtitle is-6 has-text-centered">are you a member ? <a href="<?php echo URLROOT; ?>/authentification/login">Login Now</a></h6>
            <div class="has-text-centered">
              <button class="button is-success is-large">Register Now</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php include_once(APPROOT . '/views/inc/footer.php'); ?>



  <script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>

</html>