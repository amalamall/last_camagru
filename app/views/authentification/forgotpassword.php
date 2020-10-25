<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

  <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
        <div class="column">
          <form class="box" action="<?php echo URLROOT; ?>/authentification/forgotpassword" method="post">

            <?php flash('password_mail_rest_success'); ?>
            <p class="title">
              Forgot Password
            </p>
            <div class="field">
              <label class="label">Email</label>
              <div class="control">
                <input type="email" name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Email address">
                <p class="help is-danger"><?php echo $data['email_err']; ?></p>
              </div>
            </div>
            <h6 class="subtitle is-6 has-text-centered">Return to <a href="<?php echo URLROOT; ?>/authentification/login">Login</a></h6>
            <div class="has-text-centered">
              <button class="button is-success is-large">Reset</button>
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