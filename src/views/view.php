<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="upload">
    <div class="upload-content">
        <?php if(is_video($upload_data->upload_file)): ?>
            <?php include VIEW_ROOT . '/templates/video-player.php' ?>
        <?php else: ?>
            <img src="<?php echo BASE_URL ?>/uploads/uploads/<?php echo escape($upload_data->upload_file) ?>" alt="<?php echo escape($upload_data->upload_file) ?>">
        <?php endif; ?>
    </div>

    <div class="upload-info">
        <h3 class="upload-title"><?php echo escape($upload_data->upload_title); ?></h3>
        <div class="upload-user-info">
            <img class="upload-profile-picture" src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($upload_data->user_profile_picture); ?>" alt="Profile Picture">
            <div class="upload-user-header">
                <span class="upload-user"><a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload_data->user_username); ?>"><?php echo escape($upload_data->user_name); ?></a></span>
                <span class="upload-date"><?= Core\Date::format($upload_data->upload_date) ?></span>
            </div>
        </div>
        <p class="upload-description"><?php echo escape($upload_data->upload_description); ?></p>
    </div>

    <div class="rating">
        <form name="starForm" id="starForm" class="star-rating" action="<?= BASE_URL ?>/rate" method="GET">
            <input type="hidden" name="id" value="<?= escape($upload_data->upload_id) ?>">
            <?php foreach(range(5, 1) as $rating): ?>
                <?php @$is_checked = ($users_rating !== false) && ($users_rating->rating_number === $rating); ?>
                <input
                    onchange="this.form.submit()"
                    class="radio-input"
                    type="radio"
                    id="star-<?=$rating?>"
                    name="star-input"
                    value="<?=$rating?>"
                    <?= $is_checked ? 'checked' : '' ?>
                >
                <label class="radio-label" for="star-<?=$rating?>" title="<?=$rating?> stars"><?=$rating?> stars</label>
            <?php endforeach; ?>
        </form>
    </div>

    <div class="views">
        <img src="<?= asset('img/star.svg') ?>" alt="Star rating" /> <?= $star_rating ?>
        <img src="<?= asset('img/views.svg') ?>"> <?php echo(escape($upload_data->upload_views)); ?>

        <?php if($user->loggedIn()): ?>
            <?php if(!findValue($favorite_data, 'favorite_user', $user->data()->user_id)): ?>
                <a href="<?php echo BASE_URL; ?>/favorite?id=<?php echo escape($upload_data->upload_id); ?>"><img src="<?= asset('img/favorite.svg') ?>"></a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/unfavorite?id=<?php echo escape($upload_data->upload_id); ?>"><img src="<?= asset('img/unfavorite.svg') ?>"></a>
            <?php endif; ?>

            <?php echo count($favorite_data); ?>

            <img src="<?= asset('img/download.svg') ?>"> <a href="<?php echo BASE_URL; ?>/download?f=<?php echo urlencode($upload_data->upload_file); ?>">Download</a>
        <?php else: ?>
            <img src="<?= asset('img/unfavorite.svg') ?>"> <?php echo count($favorite_data); ?>
        <?php endif; ?>
    </div>
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
                    <input type="hidden" name="token" value="<?php echo Core\Hash::generateToken('token'); ?>">
                    <input type="submit" name="submit_comment" value="Comment">
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if(!$comments): ?>
        <h3 class="site-notice">No Comments</h3>
    <?php else: ?>
        <?php foreach($comments as $comment): ?>
            <div class="comment">
                <img class="comment-profile-picture" src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($comment->user_profile_picture); ?>" alt="Profile Picture">
                <div class="comment-content">
                    <div class="comment-header">
                        <span class="comment-user"><a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($comment->user_username); ?>"><?php echo escape($comment->user_name); ?></a></span>
                        <span class="comment-date"><?= Core\Date::format($comment->comment_date) ?></span>
                    </div>
                    <p class="comment-text"><?php echo escape($comment->comment_text); ?></p>
                    <span class="comment-options">
                        <?php if($user->loggedIn()): ?>
                            <?php if($user->data()->user_id === $comment->comment_by): ?>
                                <a href="<?php echo BASE_URL; ?>/edit-comment?id=<?php echo escape($comment->comment_id); ?>">Edit</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>
