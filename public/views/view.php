<?php require_once 'public/views/includes/header.php'; ?>

<div class="upload">
  <?php $ext = explode('.', strtolower($upload_data->upload_file)); ?>
        
  <?php if(count(array_intersect($ext, $img_exts)) > 0): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload_data->upload_file; ?>" alt="<?php echo $upload_data->upload_file; ?>">
  <?php elseif(in_array('mp4', $ext)): ?>
    <video controls>
      <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo $upload_data->upload_file; ?>" type="video/mp4">
    </video>
  <?php endif; ?>

  <h3><?php echo $upload_data->upload_title; ?></h3>
  <span>
    <p>By <a href="<?php echo BASE_URL; ?>/profile?user=<?php echo $upload_data->user_username; ?>"><?php echo $upload_data->user_username; ?></a></p>
    <p><?php echo date('l j F Y \a\t H:i', strtotime($upload_data->upload_date)); ?></p>
  </span>
  <p><?php echo $upload_data->upload_description; ?></p>
</div>
<div class="comments">
  <?php if($user->loggedIn()): ?>
    <div class="form">
      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($validation)): ?>
          <?php foreach($validation->errors() as $message): ?>
            <div class="form-group">
              <p class="message error"><?php echo $message; ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <div class="form-group">
          <textarea name="comment_text"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="submit_comment" value="Comment">
        </div>
      </form>
    </div>
  <?php endif; ?>

  <div class="comments-list">
    <?php if(!$comments): ?>
      <h3>No Comments</h3>
    <?php else: ?>
      <h3>Comments</h3>

      <?php foreach($comments as $comment): ?>
        <div class="comment">
          <p><?php echo $comment->comment_text; ?></p>
          <span>
            By <a href="<?php echo BASE_URL; ?>/profile?user=<?php echo $comment->user_username; ?>"><?php echo $comment->user_name; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($comment->comment_date)); ?>
            
            <?php if($user->loggedIn()): ?>
              <?php if($user->data()->user_id === $comment->comment_by): ?>
                <a href="<?php echo BASE_URL; ?>/edit-comment?id=<?php echo $comment->comment_id; ?>">Edit</a>
              <?php endif; ?>
            <?php endif; ?>
          </span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>


<?php require_once 'public/views/includes/footer.php'; ?>