@foreach ($rs_result as $rs_val)
    <div class="col-lg-4">
        <video width="320" height="240" controls controlsList="nodownload" style="border: 2px solid black;">
            <source src="{{ route('admin.common.video.view', Crypt::encrypt($rs_val->video_path)) }}" type="video/mp4">
            </video>
        </div>
    </div>
@endforeach
