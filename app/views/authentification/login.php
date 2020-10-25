<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

  <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
        <div class="column">
          <form class="box" action="<?php echo URLROOT; ?>/authentification/login" method="post">

            <?php flash('register_success'); ?>
            <?php flash('activation_success'); ?>
            <?php flash('error_login'); ?>
            <p class="title">
              Already a member?
            </p>
            <p class="subtitle">
              Sign in to continue.
            </p>
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
            <h6 class="subtitle is-6 has-text-centered">Forgot password ?<a href="<?php echo URLROOT; ?>/authentification/forgotpassword">Reset Now</a></h6>
            <h6 class="subtitle is-6 has-text-centered">if not a member ? <a href="<?php echo URLROOT; ?>/authentification/register">Register Now</a></h6>
            <div class="has-text-centered">
              <button class="button is-success is-large">Login</button>
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