<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Log In</h2>
    
  <form action="<?php $self; ?>" method="POST">
    <?php if(isset($validation)): ?>
      <?php foreach($validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <label for="remember">
        <input type="checkbox" name="remember"> Remember Me
      </label>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?php echo Hash::generateToken('token'); ?>">
      <input type="submit" name="login" value="Log In">
    </div>
    <div class="form-group">
      <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/signup">Sign Up</a>.</p>
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>