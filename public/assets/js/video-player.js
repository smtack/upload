const videoContainer = document.getElementById('video-container');
const video = document.getElementById('video');
const videoControls = document.getElementById('video-controls');
const togglePlayBtn = document.getElementById('toggle-play');
const stop = document.getElementById('stop');
const mute = document.getElementById('mute');
const volInc = document.getElementById('vol-inc');
const volDec = document.getElementById('vol-dec');
const progress = document.getElementById('progress');
const fullscreen = document.getElementById('fs');

video.controls = false;

videoControls.setAttribute("data-state", "visible");

if (!document?.fullscreenEnabled) {
    fullscreen.style.display = "none";
}

togglePlayBtn.addEventListener('click', (e) => {
    if (video.paused || video.ended) {
        video.play();
    } else {
        video.pause();
    }
})

stop.addEventListener('click', (e) => {
    video.pause();
    video.currentTime = 0;
    progress.value = 0;

    changeButtonState("play-pause");
})

mute.addEventListener('click', (e) => {
    video.muted = !video.muted;
    changeButtonState("mute");
})

volInc.addEventListener('click', (e) => {
    alterVolume("+");
})

volDec.addEventListener('click', (e) => {
    alterVolume("-");
})

video.addEventListener('play', () => {
    changeButtonState("play-pause");
})

video.addEventListener('pause', () => {
    changeButtonState("play-pause");
})

video.addEventListener('loadedmetadata', () => {
    progress.setAttribute("max", video.duration);
})

video.addEventListener('timeupdate', () => {
    if (!progress.getAttribute("max")) {
        progress.setAttribute("max", video.duration)
    }

    progress.value = video.currentTime;
})

video.addEventListener('volumechange', () => {
    checkVolume();
})

progress.addEventListener('click', (e) => {
    if (!Number.isFinite(video.duration)) {
        return;
    }

    const rect = progress.getBoundingClientRect();
    const pos = (e.pageX - rect.left) / progress.offsetWidth;

    video.currentTime = pos * video.duration;
})

fullscreen.addEventListener('click', (e) => {
    if (document.fullscreenElement !== null) {
        document.exitFullscreen();
    } else {
        videoContainer.requestFullscreen();
    }
})

function changeButtonState(type) {
    if (type === "play-pause") {
        if (video.paused || video.ended) {
            togglePlayBtn.setAttribute("data-state", "play");
        } else {
            togglePlayBtn.setAttribute("data-state", "pause");
        }
    } else if (type === "mute") {
        mute.setAttribute("data-state", video.muted ? "unmute" : "mute");
    }
}

function checkVolume(dir) {
    if (dir) {
        const currentVolume = Math.floor(video.volume * 10) / 10;

        if (dir === "+" && currentVolume < 1) {
            video.volume += 0.1;
        } else if (dir === "-" && currentVolume > 0) {
            video.volume -= 0.1;
        }

        video.muted = currentVolume <= 0;
    }

    changeButtonState("mute");
}

function alterVolume(dir) {
    checkVolume(dir);
}
