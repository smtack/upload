<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="info">
  <div id="profile-picture">
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($profile->user_profile_picture); ?>" alt="<?php echo escape($profile->user_profile_picture); ?>">
  </div>
  <div id="user-info">
    <h2><?php echo escape($profile->user_name); ?></h2>
    <h4><?php echo escape($profile->user_username); ?></h4>
    <h5>Joined on <?= Core\Date::format($profile->user_joined) ?></h5>
    <h6><?php echo(count($follows_data) === 1) ? count($follows_data) . ' Follower' : count($follows_data) . ' Followers'; ?> &bull; <?php echo(count($users_uploads) == 1) ? count($users_uploads) . ' Upload' : count($users_uploads) . ' Uploads'; ?></h6>

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
      <?php include VIEW_ROOT . '/templates/upload-box.php' ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>
