<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Sign Up</h2>

  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($validation)): ?>
      <?php foreach($validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_name" placeholder="Name">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" placeholder="Email">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="submit" name="signup" value="Sign Up">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>