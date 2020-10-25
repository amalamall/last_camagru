<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

  <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
  <div class="hero-body">
    <div class="container">
      <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
        <div class="column">
          <form class="box" action="<?php echo URLROOT; ?>/authentification/resetpassword" method="post">
            <p class="title">
              <?php flash('password_reset_success'); ?>
              <?php flash('password_reset_error'); ?>
              Reset Your Password
            </p>
            <div class="field">
              <div class="control">
                <input type="hidden" name="token" value="<?php if (isset($_GET['token']) && !empty($_GET['token'])) echo $_GET['token']; ?>">
              </div>
            </div>
            <div class="field">
              <label class="label">New Password</label>
              <div class="control">
                <input type="password" name="new_password" class="input <?php echo (!empty($data['new_password_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['new_password']; ?>" placeholder="My password" autocomplete>
                <p class="help is-danger"><?php echo $data['new_password_err']; ?></p>
              </div>
            </div>
            <div class="field">
              <label class="label">Confirm New Password: <sup>*</sup></label>
              <div class="control">
                <input type="password" name="confirm_new_password" class="input <?php echo (!empty($data['confirm_new_password_err'])) ? 'is-danger' : ''; ?>" value="<?php echo $data['confirm_new_password']; ?>" placeholder="Confirm password" autocomplete>
                <p class="help is-danger"><?php echo $data['confirm_new_password_err']; ?></p>
              </div>
            </div>
            <div class="has-text-centered">
              <button class="button is-success is-large">Reset Now</button>
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