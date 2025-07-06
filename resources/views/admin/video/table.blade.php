<style>
    .youtube-style-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    background-color: #fff;
}

.youtube-style-card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.youtube-video-thumb {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 aspect */
    background: #000;
}

.youtube-video-thumb video {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    object-fit: cover;
}

.video-duration {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0,0,0,0.75);
    color: #fff;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: 500;
}

.youtube-video-info {
    padding: 12px 14px;
}

.youtube-video-title {
    font-size: 15px;
    font-weight: 600;
    color: #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.youtube-video-desc {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
    height: 2.8em; /* ~2 lines */
    overflow: hidden;
}

.youtube-video-info small {
    font-size: 12px;
    color: #888;
}

</style>
@foreach ($rs_videos as $rs_val)
<div class="col-md-6 col-lg-4 mb-4">
    <div class="youtube-style-card">
        <div class="youtube-video-thumb">
            @php
                $token = bin2hex(random_bytes(32));
                $url = url('viewvideo/stream') . '/' . Crypt::encrypt($rs_val->id) . '/' . $token;
                $duration = '12:45'; // You can make this dynamic if needed
            @endphp

            <video
                class="manualVideoPlayer"
                data-video-id="{{ $rs_val->id }}"
                data-token="{{ $token }}"
                muted
                controls
                playsinline
                controlsList="nodownload"
                preload="metadata"
            >
                <source src="{{ $url }}" type="video/mp4">
            </video>

            {{-- Duration Badge --}}
            <span class="video-duration">{{ $duration }}</span>
        </div>

        <div class="youtube-video-info">
            {{-- Channel Avatar + Name --}}
            <div class="d-flex align-items-center mb-2">
                <img src="{{ asset('temp_1/img/favicon.png') }}" class="rounded-circle mr-2" width="36" height="36" alt="Avatar">
                <div>
                    <h6 class="youtube-video-title mb-0" title="{{ $rs_val->title }}">{{ $rs_val->title }}</h6>
                    <small class="text-muted">EageSkool Channel</small>
                </div>
            </div>

            {{-- Description --}}
            <p class="youtube-video-desc mb-1">{{ \Illuminate\Support\Str::limit($rs_val->description, 100) }}</p>

            {{-- Views + Upload Date --}}
            <small class="text-muted">
                {{ rand(1,9) }}.{{ rand(0,9) }}K views • {{ \Carbon\Carbon::parse(12-10-2025)->diffForHumans() }}
            </small>
        </div>
    </div>
</div>
@endforeach
