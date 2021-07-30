<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Update Profile</h2>

  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($update_message)): ?>
      <div class="form-group">
        <?php echo $update_message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_name" value="<?php echo $user->data()->user_name; ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" value="<?php echo $user->data()->user_username; ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" value="<?php echo $user->data()->user_email; ?>">
    </div>
    <div class="form-group">
      <input type="submit" name="update_profile" value="Update Profile">
    </div>
  </form>
</div>
<div class="form">
  <h2>Upload Profile Picture</h2>

  <form enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($picture_message)): ?>
      <div class="form-group">
        <?php echo $picture_message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="file" name="user_profile_picture">
    </div>
    <div class="form-group">
      <input type="submit" name="upload_profile_picture" value="Upload Profile Picture">
    </div>
  </form>
</div>
<div class="form">
  <h2>Change Password</h2>

  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($password_message)): ?>
      <div class="form-group">
        <?php echo $password_message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="password" name="current_password" placeholder="Current Password">
    </div>
    <div class="form-group">
      <input type="password" name="new_password" placeholder="New Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="submit" name="change_password" value="Change Password">
    </div>
  </form>
</div>
<div class="form">
  <h2>Delete Profile</h2>

  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <?php if(isset($delete_message)): ?>
      <div class="form-group">
        <?php echo $delete_message; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="submit" name="delete_profile" value="Delete Profile">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>