<figure id="video-container">
    <video
        id="video"
        controls
        preload="metadata">
        <source 
            src="<?php echo BASE_URL ?>/uploads/uploads/<?php echo escape($upload_data->upload_file) ?>"
            type="video/mp4">
        </source>
    </video>

    <div id="video-controls" class="controls" data-state="hidden">
        <button id="toggle-play" type="button" data-state="play"></button>
        <button id="stop" type="button" data-state="stop"></button>
        <div class="progress">
            <progress id="progress" value="0">
                <span id="progress-bar"></span>
            </progress>
        </div>
        <button id="mute" type="button" data-state="mute"></button>
        <button id="vol-dec" type="button" data-state="vol-down"></button>
        <button id="vol-inc" type="button" data-state="vol-up"></button>
        <button id="fs" type="button" data-state="go-fullscreen"></button>
    </div>
</figure>

<script src="<?php echo BASE_URL ?>/public/js/video-player.js"></script>
