<?php require_once 'public/views/includes/header.php'; ?>

<div class="info">
  <div id="profile-picture">
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($profile->user_profile_picture); ?>" alt="<?php echo escape($profile->user_profile_picture); ?>">
  </div>
  <div id="user-info">
    <h2><?php echo escape($profile->user_name); ?></h2>
    <h4><?php echo escape($profile->user_username); ?></h4>
    <h5>Joined on <?php echo date('l j F Y \a\t H:i', strtotime(escape($profile->user_joined))); ?></h5>
    <h6><?php echo(count($follows_data) == 1) ? count($follows_data) . ' Follower' : count($follows_data) . ' Followers'; ?> &bull; <?php echo(count($users_uploads) == 1) ? count($users_uploads) . ' Upload' : count($users_uploads) . ' Uploads'; ?></h6>
    
    <?php if($user->loggedIn()): ?>
      <?php if($user->data()->user_username !== $profile->user_username): ?>
        <?php if(!findValue($follows_data, 'follow_user', $user->data()->user_id)): ?>
          <a href="<?php echo BASE_URL; ?>/follow?u=<?php echo escape($profile->user_id); ?>"><button>Follow</button></a>
        <?php else: ?>
          <a href="<?php echo BASE_URL; ?>/unfollow?u=<?php echo escape($profile->user_id); ?>"><button>Unfollow</button></a>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<div class="uploads">
  <?php if(count($users_uploads) == 0): ?>
    <h3 class="site-notice"><?php echo $profile->user_name; ?> hasn't uploaded anything yet.</h3>
  <?php else: ?>
    <?php foreach($users_uploads as $upload): ?>
      <div class="upload-box">
        <?php $ext = explode('.', strtolower($upload->upload_file)); ?>

        <?php if(count(array_intersect($ext, $image_extensions)) > 0): ?>
          <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" alt="<?php echo escape($upload->upload_file); ?>">
        <?php elseif(in_array('mp4', $ext)): ?>
          <video>
            <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" type="video/mp4">
          </video>
        <?php endif; ?>

        <h3><a href="view?id=<?php echo escape($upload->upload_id); ?>"><?php echo escape($upload->upload_title); ?></a></h3>
        <h5><?php echo timeAgo(escape($upload->upload_date)); ?> &bull; <?php echo($upload->upload_views == 1) ? escape($upload->upload_views) . ' View' : escape($upload->upload_views) . ' Views'; ?></h5>
      
        <?php if($user->loggedIn()): ?>
          <?php if($profile->user_username === $user->data()->user_username): ?>
            <h5><a href="edit?id=<?php echo escape($upload->upload_id); ?>">Edit</a></h5>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>