<?php require_once 'public/views/includes/header.php'; ?>

<div class="info">
  <?php if($profile->user_profile_picture): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $profile->user_profile_picture; ?>" alt="<?php echo $profile->user_profile_picture; ?>">
  <?php endif; ?>
  
  <h2><?php echo $profile->user_name; ?></h2>
  <h4><?php echo $profile->user_username; ?></h4>
  <span>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($profile->user_joined)); ?></span>

  <span>
    <?php echo(count($follows_data) == 1) ? count($follows_data) . ' Follower' : count($follows_data) . ' Followers'; ?> - 
    <?php echo(count($users_uploads) == 1) ? count($users_uploads) . ' Upload' : count($users_uploads) . ' Uploads'; ?>
  </span>

  <?php if($user->loggedIn()): ?>
    <?php if($user->data()->user_username !== $profile->user_username): ?>
      <?php if(!findValue($follows_data, 'follow_user', $user->data()->user_id)): ?>
        <a href="<?php echo BASE_URL; ?>/follow?u=<?php echo $profile->user_id; ?>"><button>Follow</button></a>
      <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/unfollow?u=<?php echo $profile->user_id; ?>"><button>Unfollow</button></a>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>
<div class="users-uploads">
  <?php if(count($users_uploads) == 0): ?>
    <h3 class="site-notice"><?php echo $profile->user_name; ?> hasn't uploaded anything yet.</h3>
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
        <p><?php echo($upload->upload_views == 1) ? $upload->upload_views . ' View' : $upload->upload_views . ' Views'; ?></p>
        <p><?php echo date('l j F Y \a\t H:i', strtotime($upload->upload_date)); ?></p>
      
        <?php if($user->loggedIn()): ?>
          <?php if($profile->user_username === $user->data()->user_username): ?>
            <p><a href="edit?id=<?php echo $upload->upload_id; ?>">Edit</a></p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>