<?php require_once 'public/views/includes/header.php'; ?>

<div class="results">
  <h2>Following</h2>

  <?php if(!$follows): ?>
    <p class="site-notice">You aren't following anyone yet.</p>
  <?php else: ?>
    <?php foreach($follows as $follow): ?>
      <div class="result">
        <div class="result-img">
          <?php if($follow->user_profile_picture): ?>
            <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $follow->user_profile_picture; ?>" alt="<?php echo $follow->user_profile_picture; ?>">
          <?php endif; ?>
        </div>
        <div class="result-content">
          <h3><a href="<?php echo BASE_URL; ?>/profile?u=<?php echo $follow->user_username; ?>"><?php echo $follow->user_username; ?></a></h3>
          <a href="<?php echo BASE_URL; ?>/unfollow?u=<?php echo $follow->user_id; ?>"><button>Unfollow</button></a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>