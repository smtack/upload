<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="results">
    <h2>Search: <?php echo str_replace('%', '', $keywords); ?></h2>

    <ul>
        <li id="toggleUploadResults">Uploads</li>
        <li id="toggleUserResults">Users</li>
    </ul>

    <div id="uploadResults">
        <?php if(!$upload_results): ?>
            <p class="site-notice">No results found</p>
        <?php else: ?>
            <?php foreach($upload_results as $upload_result): ?>
                <div class="result">
                    <div id="profile-picture">
                        <?php if(is_video($upload_result->upload_file)): ?>
                            <video>
                                <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_result->upload_file); ?>" type="video/mp4">
                            </video>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload_result->upload_file); ?>" alt="<?php echo escape($upload_result->upload_file); ?>">
                        <?php endif; ?>
                    </div>
                    <div id="user-info">
                        <h3><a href="<?php echo BASE_URL; ?>/view?id=<?php echo escape($upload_result->upload_id); ?>"><?php echo escape($upload_result->upload_title); ?></a></h3>
                        <h5>
                            By <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload_result->user_username); ?>"><?php echo escape($upload_result->user_name); ?></a>
                            &bull; <?= Core\Date::timeAgo(escape($upload_result->upload_date)) ?>
                        </h5>
                        <h5><?php echo($upload_result->upload_views == 1) ? escape($upload_result->upload_views) . ' View' : escape($upload_result->upload_views) . ' Views'; ?></h5>
                        <p><?php echo escape($upload_result->upload_description); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="userResults">
        <?php if(!$user_results): ?>
            <p class="site-notice">No results found</p>
        <?php else: ?>
            <?php foreach($user_results as $user_result): ?>
                <div class="result">
                    <div id="profile-picture">
                        <?php if($user_result->user_profile_picture): ?>
                            <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo escape($user_result->user_profile_picture); ?>" alt="<?php echo escape($user_result->user_profile_picture); ?>">
                        <?php endif; ?>
                    </div>
                    <div id="user-info">
                        <h3><a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($user_result->user_username); ?>"><?php echo escape($user_result->user_name); ?></a></h3>
                        <h4><?php echo escape($user_result->user_username); ?></h4>
                        <h5>Joined <?= Core\Date::timeAgo(escape($user_result->user_joined)) ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>
