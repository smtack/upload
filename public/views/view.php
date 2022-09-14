<?php require_once 'public/views/includes/header.php'; ?>

<div class="upload">
  <?php $ext = explode('.', strtolower($upload_data->upload_file)); ?>
        
  <?php if(count(array_intersect($ext, $image_extensions)) > 0): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_data->upload_file); ?>" alt="<?php echo escape($upload_data->upload_file); ?>">
  <?php elseif(in_array('mp4', $ext)): ?>
    <video controls>
      <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_data->upload_file); ?>" type="video/mp4">
    </video>
  <?php endif; ?>

  <div class="upload-info">
    <h3><?php echo escape($upload_data->upload_title); ?></h3>
    <h5>Uploaded by <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload_data->user_username); ?>"><?php echo escape($upload_data->user_username); ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime(escape($upload_data->upload_date))); ?></h5>
    <p><?php echo escape($upload_data->upload_description); ?></p>

    <span>
      <img src="<?php echo BASE_URL; ?>/public/img/views.svg"> <?php echo(escape($upload_data->upload_views)); ?>

      <?php if($user->loggedIn()): ?>
        <?php if(!findValue($favorite_data, 'favorite_user', $user->data()->user_id)): ?>
          <a href="<?php echo BASE_URL; ?>/favorite?id=<?php echo escape($upload_data->upload_id); ?>"><img src="<?php echo BASE_URL; ?>/public/img/favorite.svg"></a>
        <?php else: ?>
          <a href="<?php echo BASE_URL; ?>/unfavorite?id=<?php echo escape($upload_data->upload_id); ?>"><img src="<?php echo BASE_URL; ?>/public/img/unfavorite.svg"></a>
        <?php endif; ?>
        
        <?php echo count($favorite_data); ?>

        <img src="<?php echo BASE_URL; ?>/public/img/download.svg"> <a href="<?php echo BASE_URL; ?>/download?f=<?php echo urlencode($upload_data->upload_file); ?>">Download</a>
      <?php else: ?>
        <img src="<?php echo BASE_URL; ?>/public/img/unfavorite.svg"> <?php echo count($favorite_data); ?>
      <?php endif; ?>
    </span>
  </div>
</div>

<div class="comments">
  <?php if($user->loggedIn()): ?>
    <div class="form">
      <form action="<?php $self; ?>" method="POST">
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
          <input type="hidden" name="token" value="<?php echo Hash::generateToken('token'); ?>">
          <input type="submit" name="submit_comment" value="Comment">
        </div>
      </form>
    </div>
  <?php endif; ?>

  <div class="comments-list">
    <?php if(!$comments): ?>
      <h3 class="site-notice">No Comments</h3>
    <?php else: ?>
      <h3>Comments</h3>

      <?php foreach($comments as $comment): ?>
        <div class="comment">
          <p><?php echo escape($comment->comment_text); ?></p>
          <h5>
            By <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($comment->user_username); ?>"><?php echo escape($comment->user_name); ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime(escape($comment->comment_date))); ?>
            
            <?php if($user->loggedIn()): ?>
              <?php if($user->data()->user_id === $comment->comment_by): ?>
                &bull; <a href="<?php echo BASE_URL; ?>/edit-comment?id=<?php echo escape($comment->comment_id); ?>">Edit</a>
              <?php endif; ?>
            <?php endif; ?>
          </h5>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require_once 'public/views/includes/footer.php'; ?>