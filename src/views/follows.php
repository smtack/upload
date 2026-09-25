<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="results">
    <h2>Following</h2>

    <?php if(!$follows): ?>
        <p class="site-notice">You aren't following anyone yet.</p>
    <?php else: ?>
        <?php foreach($follows as $follow): ?>
            <div class="result">
                <div id="profile-picture">
                    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($follow->user_profile_picture); ?>" alt="<?php echo escape($follow->user_profile_picture); ?>">
                </div>
                <div id="user-info">
                    <h3><a href="/profile?u=<?php echo escape($follow->user_username); ?>"><?php echo escape($follow->user_name); ?></a></h3>
                    <h4><?php echo escape($follow->user_username); ?></h4>
                    <h5>Joined on <?= Core\Date::format($follow->user_joined) ?></h5>
                    <a class="button-link" href="/follow?u=<?php echo escape($follow->user_id); ?>">Unfollow</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>
