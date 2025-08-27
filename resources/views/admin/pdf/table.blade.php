<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.15) !important;
    }
    .pdf-card-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1d3557;
        line-height: 1.4;
    }
    .pdf-card-description {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.5;
    }
</style>

@foreach($rs_result as $rs_val)
@php
    $s3Key   = $rs_val->pdf_path; 
    $encPath = Crypt::encrypt($s3Key);
    $fileParam = urlencode(route('pdf.proxy', $encPath));
    $pdfUrl = url('pdfjs/web/viewer.html') 
                . '?file=' . $fileParam 
                . '&disableDownload=true&disablePrint=true';
@endphp

<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm rounded-4 border-0 bg-white hover-shadow" style="transition: 0.3s ease;">
        
        {{-- PDF Preview on Top --}}
        <div class="border-bottom">
            <iframe 
                src="{{ $pdfUrl }}" 
                width="100%" 
                height="220px" 
                style="border: none; border-radius: 12px 12px 0 0;">
            </iframe>
        </div>

        <div class="card-body p-3 d-flex flex-column">
            
            {{-- Title --}}
            <h5 class="pdf-card-title mb-2">
                <i class="fa fa-file-pdf text-danger me-2"></i> 
                {{ \Illuminate\Support\Str::limit($rs_val->title, 55) }}
            </h5>

            {{-- Description --}}
            <p class="pdf-card-description mb-3 flex-grow-1">
                {{ \Illuminate\Support\Str::limit($rs_val->description, 100) }}
            </p>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                <a href="{{ $pdfUrl }}" target="_blank" 
                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="fa fa-eye me-1"></i> View
                </a>
                {{-- <button type="button" 
                        class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="fa fa-trash"></i> Delete
                </button> --}}
            </div>

        </div>
    </div>
</div>
@endforeach
