<?php require_once 'public/views/includes/header.php'; ?>

<div class="results">
  <h2>Search: <?php echo str_replace('%', '', $keywords); ?></h2>

  <?php if(!$user_results && !$upload_results): ?>
    <p class="site-notice">No results found</p>
  <?php endif; ?>
  
  <?php if($user_results): ?>
    <?php foreach($user_results as $user_result): ?>
      <div class="result">
        <div class="result-img">
          <?php if($user_result->user_profile_picture): ?>
            <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $user_result->user_profile_picture; ?>" alt="<?php echo $user_result->user_profile_picture; ?>">
          <?php endif; ?>
        </div>
        <div class="result-content">
          <h3><a href="<?php echo BASE_URL; ?>/profile?u=<?php echo $user_result->user_username; ?>"><?php echo $user_result->user_username; ?></a></h3>
          <span>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($user_result->user_joined)); ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <?php if($upload_results): ?>
    <?php foreach($upload_results as $upload_result): ?>
      <div class="result">
        <?php $ext = explode('.', strtolower($upload_result->upload_file)); ?>
        
        <div class="result-img">
          <?php if(count(array_intersect($ext, $img_exts)) > 0): ?>
            <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload_result->upload_file; ?>" alt="<?php echo $upload_result->upload_file; ?>">
          <?php elseif(in_array('mp4', $ext)): ?>
            <video>
              <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload_result->upload_file; ?>" type="video/mp4">
            </video>
          <?php endif; ?>
        </div>
        <div class="result-content">
          <h3><a href="<?php echo BASE_URL; ?>/view?id=<?php echo $upload_result->upload_id; ?>"><?php echo $upload_result->upload_title; ?></a></h3>
          <span>By <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo $upload_result->user_username; ?>"><?php echo $upload_result->user_name; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($upload_result->upload_date)); ?></span>
          <p><?php echo($upload_result->upload_views == 1) ? $upload_result->upload_views . ' View' : $upload_result->upload_views . ' Views'; ?></p>
          <p><?php echo $upload_result->upload_description; ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>