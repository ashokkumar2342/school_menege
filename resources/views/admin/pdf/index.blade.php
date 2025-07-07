@extends('admin.layout.base')
@section('body')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>PDF Upload</h3>
            </div>
        </div> 
    </div>

    <div class="card card-info">
        <div class="card-body">
            <form id="pdfUploadForm" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Class <span class="fa fa-asterisk text-danger"></span></label>
                            <select name="class" class="form-control select2" id="select_class_box" 
                                onchange="callAjax(this,'{{ route('admin.common.class.wise.subjects') }}','subject_select_box');">
                                <option selected value="{{ Crypt::encrypt(0) }}">Select Class</option>  
                                @foreach ($classes as $val_rec)
                                    <option value="{{ Crypt::encrypt($val_rec->opt_id) }}">{{ $val_rec->opt_text }}</option>  
                                @endforeach 
                            </select> 
                        </div>
                    </div>

                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Subject <span class="fa fa-asterisk text-danger"></span></label>
                            <select name="subject" class="form-control select2" id="subject_select_box" 
                                onchange="callAjax(this,'{{ route('admin.common.subjects.wise.chapter') }}' + '?class_id=' + $('#select_class_box').val() + '&subject_id=' + $('#subject_select_box').val(),'chapter_select_box');">
                            </select> 
                        </div>
                    </div>

                    <div class="col-lg-4">                         
                        <div class="form-group">
                            <label>Chapter/Topic <span class="fa fa-asterisk text-danger"></span></label>
                            <select name="chpater" class="form-control select2" id="chapter_select_box" 
                                onchange="callAjax(this,'{{ route('admin.pdf.table') }}','result_table_id');">
                            </select> 
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">                         
                        <div class="form-group">
                            <label>Choose PDF: <span class="fa fa-asterisk text-danger"></span></label>
                            <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept="application/pdf" required>
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Title <span class="fa fa-asterisk text-danger"></span></label>
                            <textarea name="title" class="form-control" required placeholder="Please Enter Title" maxlength="250"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">                         
                        <div class="form-group">
                            <label>Description <span class="fa fa-asterisk text-danger"></span></label>
                            <textarea name="description" class="form-control" required placeholder="Please Enter Description" maxlength="500"></textarea>
                        </div>
                    </div>                    
                </div>

                {{-- Progress Bar --}}
                <div class="form-group">
                    <label>Progress</label>
                    <div style="background: #eee; height: 20px; border-radius: 5px;">
                        <div id="pdfProgressBar" style="background: #007bff; height: 100%; width: 0%; border-radius: 5px;"></div>
                    </div>
                    <p id="pdfUploadStatus" class="text-info mt-2"></p>
                </div>

                <div class="form-group text-center">
                    <button type="submit" id="pdfUploadBtn" class="btn btn-primary">Upload PDF</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card"> 
        <div class="card-body">
            <div class="row" id="result_table_id">
                {{-- Table updates here --}}
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());

    document.getElementById('pdfUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const progressBar = document.getElementById('pdfProgressBar');
        const uploadStatus = document.getElementById('pdfUploadStatus');
        const submitButton = document.getElementById('pdfUploadBtn');

        // Disable button
        submitButton.disabled = true;
        submitButton.textContent = 'Uploading...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('admin.pdf.store') }}", true);
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
            submitButton.textContent = 'Upload PDF';

            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                    uploadStatus.textContent = response.message;

                    // Reset specific fields
                    form.querySelector('[name="title"]').value = '';
                    form.querySelector('[name="description"]').value = '';
                    form.querySelector('[name="pdf_file"]').value = '';
                    progressBar.style.width = '0%';

                    // Trigger chapter dropdown again to refresh the table
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
            submitButton.textContent = 'Upload PDF';
            uploadStatus.textContent = 'Network error occurred.';
        };

        xhr.send(formData);
    });
</script>
@endpush
