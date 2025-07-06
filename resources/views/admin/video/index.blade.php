@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Video Upload</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                </ol>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{route('admin.video.store')}}" method="post" class="add_form" no-reset="true" enctype="multipart/form-data" reset-input-text="video" select-triger="chapter_select_box">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Class</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="class" class="form-control select2" id="select_class_box" data-table-new-without-pagination="true" onchange="callAjax(this,'{{route('admin.common.class.wise.subjects')}}','subject_select_box');" button-click="btn_show">
                                <option selected value="{{Crypt::encrypt(0)}}">Select Class</option>  
                                @foreach ($classes as $val_rec)
                                    <option value="{{Crypt::encrypt($val_rec->opt_id)}}">{{$val_rec->opt_text}}</option>  
                                @endforeach 
                            </select> 
                        </div>
                    </div>
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Subject</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="subject" class="form-control select2" id="subject_select_box" onchange="callAjax(this,'{{route('admin.common.subjects.wise.chapter')}}'+'?class_id='+$('#select_class_box').val()+'&subject_id='+$('#subject_select_box').val(),'chapter_select_box');">
                                 
                            </select> 
                        </div>
                    </div>
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Chapter/Topic</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="chpater" class="form-control select2" id="chapter_select_box" onchange="callAjax(this,'{{route('admin.video.table')}}','result_table_id');">
                                 
                            </select> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">                         
                        <div class="form-group">
                        <label>Choose Video:</label>
                        <span class="fa fa-asterisk"></span>
                            <input type="file" name="video" id="video" class="form-control" required accept="video/*">
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-6">                         
                        <div class="form-group">
                        <label>Title</label>
                        <span class="fa fa-asterisk"></span>
                            <textarea name="title" class="form-control" required placeholder="Please Enter Title" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">                         
                        <div class="form-group">
                        <label>Description</label>
                        <span class="fa fa-asterisk"></span>
                            <textarea name="description" class="form-control" required placeholder="Please Enter Description" maxlength="500"></textarea>
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <input type="submit" class="btn btn-primary" value="Upload">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card"> 
        <div class="card-body">

            <div class="row" id="result_table_id">
                
            </div>
            
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
function bindVideoWatchTracker() {
    const videos = document.querySelectorAll('.manualVideoPlayer');

    videos.forEach(video => {
        if (!video.dataset.trackerBound) {
            video.dataset.trackerBound = true;

            const videoId = video.getAttribute('data-video-id');
            const token = video.getAttribute('data-token');

            let lastStartTime = null;
            let intervalTracker = null;

            function sendWatchData(action, seconds) {
                if (seconds < 2 && action !== 'stop') return;

                fetch("{{ url('/admin/video/watch-event') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        video_id: videoId,
                        token: token,
                        watched_seconds: Math.floor(seconds),
                        action: action
                    })
                });
            }

            function startTracking() {
                lastStartTime = Date.now();
                sendWatchData('play', 0);

                intervalTracker = setInterval(() => {
                    if (lastStartTime) {
                        const watched = (Date.now() - lastStartTime) / 1000;
                        sendWatchData('watch', watched);
                        lastStartTime = Date.now();
                    }
                }, 15000);
            }

            function stopTracking(eventName = 'pause') {
                if (lastStartTime) {
                    const watched = (Date.now() - lastStartTime) / 1000;
                    sendWatchData(eventName, watched);
                    lastStartTime = null;
                }

                if (intervalTracker) {
                    clearInterval(intervalTracker);
                    intervalTracker = null;
                }
            }

            video.addEventListener('play', () => startTracking());
            video.addEventListener('pause', () => stopTracking('pause'));
            video.addEventListener('ended', () => stopTracking('stop'));
            window.addEventListener('beforeunload', () => stopTracking('stop'));
        }
    });
}
</script>
@endpush





