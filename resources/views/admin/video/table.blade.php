{{-- @foreach ($rs_result as $rs_val)
    <div class="col-4">
        <div class="card" style="max-width: 18rem;">
            <video src="{{ route('admin.common.video.view', Crypt::encrypt($rs_val->video_path)) }}" class="card-img-top" controls controlsList="nodownload"></video>

            <div class="card-body">
                <h5>{{$rs_val->title}}</h5>
                <p class="card-text">{{$rs_val->description}}</p>
            </div>
        </div>
    </div>
@endforeach
 --}}
 <div class="modal-content p-1">
    <button type="button" class="close border-0 bg-transparent ms-auto text-dark fw-bold fontSizeCloseButton"
        data-bs-dismiss="modal" id="close_btn_manual">×</button>

    <div class="modal-body" style="padding-bottom: 0px; padding-top: 0px;">
        @foreach ($rs_videos as $rs_val)
            @php
                $token = bin2hex(random_bytes(16)); // Random token
                $url = url('viewvideo/stream') . '/' . Crypt::encrypt($rs_val->id) . '/' . $token;   
            @endphp            
            <div class="row">
                <div id="player">
                    <video class="manualVideoPlayer" style="width:100%;" controls playsinline poster="" controlsList="nodownload">
                        <source src="{{ $url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        @endforeach
    </div>
</div>
@php
    $timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : 00;
@endphp

<script type="text/javascript">
   

    
</script>


