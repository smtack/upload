<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="uploads">
  <?php if(!$uploads): ?>
    <h3 class="site-notice">No Uploads</h3>
  <?php else: ?>
    <?php foreach($uploads as $upload): ?>
      <?php include VIEW_ROOT . '/templates/upload-box.php' ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>