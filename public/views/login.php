<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Log In</h2>
    
  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($message)): ?>
      <div class="form-group">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="submit" name="login" value="Log In">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>