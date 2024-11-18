<div class="radio-player card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Album Art and Info -->
        <div class="d-flex align-items-center gap-4 mb-4">
            <div class="radio-artwork pulse">
                <div class="artwork-inner">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="card-title mb-1 fw-bold text-gradient">Lofi Radio</h5>
                <p class="text-muted small mb-0">Chill beats to help you focus</p>
            </div>
        </div>

        <!-- Player Controls -->
        <div class="player-controls d-flex align-items-center justify-content-between mb-4">
            <button class="btn-play" id="playPauseBtn">
                <i class="fas fa-play"></i>
            </button>

            <!-- Volume Control -->
            <div class="volume-control d-flex align-items-center gap-3">
                <button class="btn-volume" id="volumeBtn">
                    <i class="fas fa-volume-up"></i>
                </button>
                <div class="volume-slider-container">
                    <input type="range" class="volume-slider" id="volumeSlider" min="0" max="100" value="100">
                </div>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="radio-status">
            <div class="status-content">
                <div class="radio-animation d-none">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <span id="statusText">Ready to play</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.radio-player {
    background: linear-gradient(145deg, #ffffff, #f5f7fa);
    border: 1px solid rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(10px);
}

.radio-artwork {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(45deg, #4f46e5, #2563eb);
    position: relative;
    overflow: hidden;
}

.artwork-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
}

.artwork-inner i {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.9);
}

.text-gradient {
    background: linear-gradient(45deg, #1a1a1a, #4a4a4a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Pulse Animation */
.pulse {
    position: relative;
}

.pulse::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: inherit;
    border-radius: inherit;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.05);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 0;
    }
}

/* Player Controls */
.btn-play {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(145deg, #4f46e5, #2563eb);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.btn-play:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
}

.btn-volume {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-volume:hover {
    background: #e2e8f0;
    color: #334155;
}

.volume-slider-container {
    position: relative;
    width: 100px;
    height: 24px;
    display: flex;
    align-items: center;
}

.volume-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 4px;
    border-radius: 2px;
    background: #e2e8f0;
    outline: none;
}

.volume-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.volume-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

/* Status Bar */
.radio-status {
    padding: 12px;
    background: #f8fafc;
    border-radius: 12px;
}

.status-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.radio-animation {
    display: flex;
    align-items: center;
    gap: 3px;
    height: 16px;
}

.radio-animation .bar {
    width: 3px;
    height: 100%;
    background: linear-gradient(to top, #4f46e5, #2563eb);
    border-radius: 3px;
    animation: sound 1.2s linear infinite;
}

.radio-animation .bar:nth-child(2) { animation-delay: 0.2s; }
.radio-animation .bar:nth-child(3) { animation-delay: 0.4s; }
.radio-animation .bar:nth-child(4) { animation-delay: 0.6s; }

@keyframes sound {
    0% { height: 4px; }
    50% { height: 16px; }
    100% { height: 4px; }
}

#statusText {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const audio = new Audio();
    audio.src = 'https://lofi.harshbanjare.me';
    audio.preload = 'none';
    audio.volume = 1.0;

    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeBtn = document.getElementById('volumeBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const statusText = document.getElementById('statusText');
    const animation = document.querySelector('.radio-animation');
    let isPlaying = false;
    let lastVolume = 1.0;

    // Play/Pause
    playPauseBtn.addEventListener('click', () => {
        if (!isPlaying) {
            statusText.textContent = 'Connecting...';
            audio.play().catch(error => {
                console.error('Play failed:', error);
                statusText.textContent = 'Error playing stream';
                animation.classList.add('d-none');
            });
        } else {
            audio.pause();
        }
    });

    // Volume Control
    volumeSlider.addEventListener('input', (e) => {
        const volume = e.target.value / 100;
        audio.volume = volume;
        lastVolume = volume;
        updateVolumeIcon(volume);
    });

    volumeBtn.addEventListener('click', () => {
        if (audio.volume > 0) {
            audio.volume = 0;
            volumeSlider.value = 0;
            volumeBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
        } else {
            audio.volume = lastVolume;
            volumeSlider.value = lastVolume * 100;
            updateVolumeIcon(lastVolume);
        }
    });

    function updateVolumeIcon(volume) {
        if (volume === 0) {
            volumeBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
        } else if (volume < 0.5) {
            volumeBtn.innerHTML = '<i class="fas fa-volume-down"></i>';
        } else {
            volumeBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
        }
    }

    // Audio Events
    audio.addEventListener('playing', () => {
        isPlaying = true;
        animation.classList.remove('d-none');
        statusText.textContent = 'Now Playing';
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
    });

    audio.addEventListener('pause', () => {
        isPlaying = false;
        animation.classList.add('d-none');
        statusText.textContent = 'Paused';
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    });

    audio.addEventListener('waiting', () => {
        statusText.textContent = 'Buffering...';
    });

    audio.addEventListener('error', () => {
        isPlaying = false;
        animation.classList.add('d-none');
        statusText.textContent = 'Error playing stream';
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    });

    // Clean up
    window.addEventListener('beforeunload', () => {
        audio.pause();
        audio.src = '';
    });
});
</script>
@endpush 