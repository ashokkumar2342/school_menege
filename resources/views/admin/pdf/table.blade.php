@foreach($rs_result as $rs_val)
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $rs_val->title }}</h5>
        </div>
        <div class="card-body">
            @php
                $pdfUrl = url('pdfjs/web/viewer.html') . 
                          '?file=' . urlencode(route('admin.common.pdf.view', Crypt::encrypt('app/' . $rs_val->pdf_path))) . 
                          '&disableDownload=true&disablePrint=true';
            @endphp

            <iframe 
                src="{{ $pdfUrl }}"
                width="100%" 
                height="200px" 
                style="border: none;">
            </iframe>

            <!-- View in new tab link -->
            <div class="mt-2">
                <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-primary btn-sm float-right">
                    <i class="fa fa-eye"> View</i>
                </a>
            </div>

            <p class="mt-2">{{ $rs_val->description }}</p>
        </div>
    </div>
</div>
@endforeach
