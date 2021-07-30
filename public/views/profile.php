<?php require_once 'public/views/includes/header.php'; ?>

<div class="info">
  <?php if($user_data->user_profile_picture): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $user_data->user_profile_picture; ?>" alt="<?php echo $user_data->user_profile_picture; ?>">
  <?php endif; ?>
  
  <h2><?php echo $user_data->user_name; ?></h2>
  <h4><?php echo $user_data->user_username; ?></h4>
  <p>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($user_data->user_joined)); ?></p>
</div>
<div class="users-uploads">
  <?php if(!$users_uploads): ?>
    <h3><?php echo $user_data->user_name; ?> hasn't uploaded anything yet.</h3>
  <?php else: ?>
    <?php foreach($users_uploads as $upload): ?>
      <div class="upload-box">
        <?php $ext = explode('.', strtolower($upload->upload_file)); ?>

        <?php if(count(array_intersect($ext, $img_exts)) > 0): ?>
          <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload->upload_file; ?>" alt="<?php echo $upload->upload_file; ?>">
        <?php elseif(in_array('mp4', $ext)): ?>
          <video>
            <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload->upload_file; ?>" type="video/mp4">
          </video>
        <?php endif; ?>

        <h3><a href="view?id=<?php echo $upload->upload_id; ?>"><?php echo $upload->upload_title; ?></a></h3>
        <p>By <a href="<?php echo BASE_URL; ?>/profile?user=<?php echo $upload->user_username; ?>"><?php echo $upload->user_username; ?></a></p>
        <p><?php echo date('l j F Y \a\t H:i', strtotime($upload->upload_date)); ?></p>
      
        <?php if($user->loggedIn()): ?>
          <?php if($user_data->user_username === $user->data()->user_username): ?>
            <p><a href="edit?id=<?php echo $upload->upload_id; ?>">Edit</a></p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>