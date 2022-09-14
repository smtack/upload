<?php require_once 'public/views/includes/header.php'; ?>

<div class="uploads">
  <?php if(!$uploads): ?>
    <h3 class="site-notice">No Uploads. Upload a picture or video, or follow someone.</h3>
  <?php else: ?>
    <?php foreach($uploads as $upload): ?>
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
        <h5>Uploaded by <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload->user_username); ?>"><?php echo escape($upload->user_username); ?></a> &bull; <?php echo timeAgo(escape($upload->upload_date)); ?></h5>
        <h5><?php echo($upload->upload_views == 1) ? escape($upload->upload_views) . ' View' : escape($upload->upload_views) . ' Views'; ?></h5>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>