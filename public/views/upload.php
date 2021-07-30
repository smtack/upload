<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>New Upload</h2>

  <form enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($message)): ?>
      <div class="form-group">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="file" name="upload_file">
    </div>
    <div class="form-group">
      <input type="text" name="upload_title" placeholder="Title">
    </div>
    <div class="form-group">
      <textarea name="upload_description" placeholder="Description"></textarea>
    </div>
    <div class="form-group">
      <input type="submit" name="upload" value="Upload">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>