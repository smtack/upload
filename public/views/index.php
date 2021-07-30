<?php require_once 'public/views/includes/header.php'; ?>

<div class="uploads">
  <?php if(!$uploads): ?>
    <h3>No Uploads</h3>
  <?php else: ?>
    <?php foreach($uploads as $upload): ?>
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
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>