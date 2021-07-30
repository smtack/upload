<?php require_once 'public/views/includes/header.php'; ?>

<div class="results">
  <?php if(!$results): ?>
    <h2>No Uploads Found...</h2>
  <?php else: ?>
    <h2>Search: <?php echo str_replace('%', '', $keywords); ?></h2>

    <?php foreach($results as $result): ?>
      <div class="result">
        <?php $ext = explode('.', strtolower($result->upload_file)); ?>
        
        <div class="result-img">
          <?php if(count(array_intersect($ext, $img_exts)) > 0): ?>
            <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $result->upload_file; ?>" alt="<?php echo $result->upload_file; ?>">
          <?php elseif(in_array('mp4', $ext)): ?>
            <video>
              <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $result->upload_file; ?>" type="video/mp4">
            </video>
          <?php endif; ?>
        </div>
        <div class="result-content">
          <h3><a href="<?php echo BASE_URL; ?>/view?id=<?php echo $result->upload_id; ?>"><?php echo $result->upload_title; ?></a></h3>
          <span>By <a href="<?php echo BASE_URL; ?>/profile?user=<?php echo $result->user_username; ?>"><?php echo $result->user_name; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($result->upload_date)); ?></span>
          <p><?php echo $result->upload_description; ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>