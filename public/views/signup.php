<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Sign Up</h2>

  <form action="<?php $self; ?>" method="POST">
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
      <input type="hidden" name="token" value="<?php echo Hash::generateToken('token'); ?>">
      <input type="submit" name="signup" value="Sign Up">
    </div>
    <div class="form-group">
      <p>Already have an account? <a href="<?php echo BASE_URL; ?>/login">Log In</a>.</p>
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>