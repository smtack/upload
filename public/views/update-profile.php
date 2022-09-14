<?php require_once 'public/views/includes/header.php'; ?>

<div class="form">
  <h2>Update Profile</h2>

  <form action="<?php $self; ?>" method="POST">
    <?php if(isset($validation)): ?>
      <?php foreach($validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_name" value="<?php echo escape($user->data()->user_name); ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" value="<?php echo escape($user->data()->user_username); ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" value="<?php echo escape($user->data()->user_email); ?>">
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?php echo Hash::generateToken('token'); ?>">
      <input type="submit" name="update_profile" value="Update Profile">
    </div>
  </form>
</div>
<div class="form">
  <h2>Upload Profile Picture</h2>

  <form enctype="multipart/form-data" action="<?php $self; ?>" method="POST">
    <?php if(isset($picture_validation)): ?>
      <?php foreach($picture_validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <input type="file" name="user_profile_picture">
    </div>
    <div class="form-group">
      <input type="hidden" name="picture-token" value="<?php echo Hash::generateToken('picture-token'); ?>">
      <input type="submit" name="upload_profile_picture" value="Upload Profile Picture">
    </div>
  </form>
</div>
<div class="form">
  <h2>Change Password</h2>

  <form action="<?php $self; ?>" method="POST">
    <?php if(isset($password_validation)): ?>
      <?php foreach($password_validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
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
      <input type="hidden" name="password-token" value="<?php echo Hash::generateToken('password-token'); ?>">
      <input type="submit" name="change_password" value="Change Password">
    </div>
  </form>
</div>
<div class="form">
  <h2>Delete Profile</h2>

  <form action="<?php $self; ?>" method="POST">
    <?php if(isset($delete_validation)): ?>
      <?php foreach($delete_validation->errors() as $message): ?>
        <div class="form-group">
          <p class="message error"><?php echo $message; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Enter Password">
    </div>
    <div class="form-group">
      <input type="hidden" name="delete-token" value="<?php echo Hash::generateToken('delete-token'); ?>">
      <input type="submit" name="delete_profile" value="Delete Profile">
    </div>
  </form>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>