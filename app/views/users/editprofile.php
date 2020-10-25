<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

    <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>

    <div class="hero-body">
        <div class="container">
            <div class="columns is-5-tablet is-4-desktop is-3-widescreen">
                <div class="column">
                    <form class="box" action="<?php echo URLROOT; ?>/users/editprofile" method="post">
                        <!-- <div class="field has-text-centered">
                                <img src="images/logo.png" width="167">
                            </div> -->
                        <?php flash('profile_edit_success'); ?>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control ">
                                <input type="email" name="email" class="input" value="<?php if (!empty($data['email'])) echo $data['email'];
                                                                                        else echo $_SESSION['user_email']; ?>">
                                <p class="help is-danger"><?php echo $data['email_err']; ?></p>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">full name</label>
                            <div class="control ">
                                <input type="text" name="fullname" class="input" value="<?php if (!empty($data['fullname'])) echo $data['fullname'];
                                                                                        else echo $_SESSION['user_fullname']; ?>">
                                <p class="help is-danger"><?php echo $data['fullname_err']; ?></p>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Username</label>
                            <div class="control ">
                                <input type="text" name="username" class="input" value="<?php if (!empty($data['username'])) echo $data['username'];
                                                                                        else echo $_SESSION['user_username']; ?>">
                                <p class="help is-danger"><?php echo $data['username_err']; ?></p>
                            </div>
                        </div>
                        <div class="field">
                            <label class="checkbox">
                                <input type="checkbox" name="send_notif" <?php if (($_SESSION['send_notif']) == 1) echo 'checked="true"';
                                                                            else echo ''; ?>>
                                I agree to Enable Notification with email
                            </label>
                        </div>
                        <div class="field">
                            <button class="button is-success">
                                Edit profile
                            </button>
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