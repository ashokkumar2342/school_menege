@foreach ($rs_result as $rs_val)
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
