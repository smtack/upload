<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="results">
    <h2>Your Uploads</h2>

    <?php if(!$uploads): ?>
        <p class="site-notice">You don't have any favorites.</p>
    <?php else: ?>
        <?php foreach($uploads as $upload): ?>
            <div class="result">
                <div id="profile-picture">          
                    <?php if(is_video($upload->upload_file)): ?>
                        <video>
                            <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" type="video/mp4">
                        </video>
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" alt="<?php echo escape($upload->upload_file); ?>">
                    <?php endif; ?>
                </div>
                <div id="user-info">
                    <h3><a href="view?id=<?php echo escape($upload->upload_id); ?>"><?php echo escape($upload->upload_title); ?></a></h3>
                    <h5>
                        By <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload->user_username); ?>"><?php echo escape($upload->user_username); ?></a>
                        on <?= Date::format($upload->upload_date) ?>
                    </h5>
                    <h5><?php echo($upload->upload_views == 1) ? escape($upload->upload_views) . ' View' : escape($upload->upload_views) . ' Views'; ?></h5>

                    <h4><a href="edit?id=<?php echo escape($upload->upload_id); ?>">Edit</a></h4>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>