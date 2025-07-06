@foreach ($rs_videos as $rs_val)
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card video-card shadow-sm border-0">
        <div class="card-header text-center text-white video-header">
            <h5 class="card-title mb-0">{{ $rs_val->title }}</h5>
        </div>
        <div class="card-body bg-light rounded-bottom">
            @php
                $token = bin2hex(random_bytes(32));
                $url = url('viewvideo/stream') . '/' . Crypt::encrypt($rs_val->id) . '/' . $token;
            @endphp

            <div class="video-wrapper mb-3">
                <video
                    class="manualVideoPlayer"
                    data-video-id="{{ $rs_val->id }}"
                    data-token="{{ $token }}"
                    style="width:100%; border-radius: 8px;"
                    controls
                    playsinline
                    controlsList="nodownload"
                >
                    <source src="{{ $url }}" type="video/mp4">
                </video>
            </div>

            <p class="video-description">{{ $rs_val->description }}</p>
        </div>
    </div>
</div>
@endforeach
