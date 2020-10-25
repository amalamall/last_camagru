<!DOCTYPE html>
<html lang="en">

<?php include_once(APPROOT . '/views/inc/head.php'); ?>

<body>

  <?php include_once(APPROOT . '/views/inc/navbar.php'); ?>
  <section class="hero">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">
          <?php if (isset($data['title'])) echo $data['title']; ?>
        </h1>
        <div class="container">
          <div class="notification">
            <?php if (isset($data['description'])) echo $data['description']; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include_once(APPROOT . '/views/inc/footer.php'); ?>

  <script type="text/javascript" src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>

</html>