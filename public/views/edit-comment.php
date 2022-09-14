<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Edit Comment</h2>

  <form action="<?php $self; ?>" method="POST">
    <?php if(isset($validation)): ?>
      <?php foreach($validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <textarea name="comment_text"><?php echo escape($comment->comment_text); ?></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?php echo Hash::generateToken('token'); ?>">
      <input type="submit" name="edit_comment" value="Edit Comment">
    </div>
  </form>
</div>

<div class="form">
  <h2>Delete Comment</h2>

  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <input type="hidden" name="delete-token" value="<?php echo Hash::generateToken('delete-token'); ?>">
      <input type="submit" name="delete_comment" value="Delete Comment">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>