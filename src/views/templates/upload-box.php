<div class="upload-box">        
    <?php if(is_video($upload->upload_file)): ?>
        <video>
            <source src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <img src="<?php echo BASE_URL; ?>/uploads/uploads/<?php echo escape($upload->upload_file); ?>" alt="<?php echo escape($upload->upload_file); ?>">
    <?php endif; ?>

    <h3>
        <a href="view?id=<?php echo escape($upload->upload_id); ?>">
            <?php if(strlen($upload->upload_title) > 32): ?>
                <?php echo escape(substr($upload->upload_title, 0, 32)) . '...' ?>
            <?php else: ?>
                <?php echo (escape($upload->upload_title)) ?>
            <?php endif; ?>
        </a>
    </h3>

    <h5>
        Uploaded by <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($upload->user_username); ?>"><?php echo escape($upload->user_username); ?></a>
        &bull; <?= Date::timeAgo(escape($upload->upload_date)) ?>
    </h5>

    <h5><?php echo($upload->upload_views == 1) ? escape($upload->upload_views) . ' View' : escape($upload->upload_views) . ' Views'; ?></h5>
</div>