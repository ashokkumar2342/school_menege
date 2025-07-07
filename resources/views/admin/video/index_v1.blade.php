@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <h3>Video Upload</h3>
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form id="videoUploadForm" enctype="multipart/form-data">
                {{ csrf_field() }}
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
                    <div class="col-lg-12">                         
                        <div class="form-group">
                            <label>Choose Video:</label><span class="fa fa-asterisk"></span>
                            <input type="file" name="video" class="form-control" accept="video/*" required> 
                        </div>
                    </div>
                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Title</label><span class="fa fa-asterisk"></span>
                            <textarea name="title" class="form-control" required maxlength="250"></textarea> 
                        </div>
                    </div>
                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Description</label><span class="fa fa-asterisk"></span>
                            <textarea name="description" class="form-control" required maxlength="500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Progress</label>
                    <div style="background: #eee; height: 20px; border-radius: 5px;">
                        <div id="progressBar" style="background: #28a745; height: 100%; width: 0%;"></div>
                    </div>
                    <p id="uploadStatus" class="text-info mt-2"></p>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Upload Video</button>
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
    document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const progressBar = document.getElementById('progressBar');
        const uploadStatus = document.getElementById('uploadStatus');
        const submitButton = form.querySelector('button[type="submit"]');

        // 🔒 Disable submit button during upload
        submitButton.disabled = true;
        submitButton.innerText = 'Uploading...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('admin.video.store') }}", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                uploadStatus.textContent = percent + '% uploaded...';
            }
        });

        xhr.onload = function() {
            submitButton.disabled = false;
            submitButton.innerText = 'Upload Video';

            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                    uploadStatus.textContent = response.message;

                    // Clear fields
                    form.querySelector('[name="title"]').value = '';
                    form.querySelector('[name="description"]').value = '';
                    form.querySelector('[name="video"]').value = '';
                    progressBar.style.width = '0%';

                    // Refresh video table
                    const chapterDropdown = document.getElementById('chapter_select_box');
                    if (chapterDropdown) {
                        chapterDropdown.dispatchEvent(new Event('change'));
                    }
                } else {
                    uploadStatus.textContent = response.message || 'Upload failed.';
                }
            } else {
                uploadStatus.textContent = 'Server error occurred.';
            }
        };

        xhr.onerror = function() {
            submitButton.disabled = false;
            submitButton.innerText = 'Upload Video';
            uploadStatus.textContent = 'Network error occurred.';
        };

        xhr.send(formData);
    });
</script>

@endpush
