@foreach ($rs_videos as $rs_val)
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $rs_val->title }}</h5>
        </div>
        <div class="card-body">
            @php
                $token = bin2hex(random_bytes(16)); // Random token
                $url = url('viewvideo/stream') . '/' . Crypt::encrypt($rs_val->id) . '/' . $token;   
            @endphp

            
            <div id="player">
                <video class="manualVideoPlayer" style="width:100%;" controls playsinline poster="" controlsList="nodownload">
                    <source src="{{ $url }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <p class="mt-2">{{ $rs_val->description }}</p>
        </div>
    </div>
</div>
@endforeach


