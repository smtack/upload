<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>Edit Upload</h2>

  <form enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($validation)): ?>
      <?php foreach($validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <?php if(is_video($upload_data->upload_file)): ?>
        <video>
          <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_data->upload_file); ?>" type="video/mp4">
        </video>
      <?php else: ?>
        <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_data->upload_file); ?>" alt="<?php echo escape($upload_data->upload_file); ?>">
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="upload_title" value="<?php echo escape($upload_data->upload_title); ?>">
    </div>
    <div class="form-group">
      <textarea name="upload_description"><?php echo escape($upload_data->upload_description); ?></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?= Core\Hash::generateToken('token') ?>">
      <input type="submit" name="edit" value="Edit">
    </div>
  </form>
</div>

<div class="form">
  <h2>Delete Upload</h2>

  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="form-group">
      <input type="hidden" name="delete-token" value="<?= Core\Hash::generateToken('delete-token') ?>">
      <input type="submit" name="delete_upload" value="Delete Upload">
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>
