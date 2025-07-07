<style>
    .hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12) !important;
}

    .btn-group .btn {
    min-width: 70px;
}
</style>
@foreach($rs_result as $rs_val)
@php
    $pdfFile = 'https://eageskoolvideo.s3.ap-south-1.amazonaws.com/' . $rs_val->pdf_path;
    $pdfUrl = url('pdfjs/web/viewer.html') . '?file=' . urlencode($pdfFile) . '&disableDownload=true&disablePrint=true';
@endphp

<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow rounded-4 border-0 bg-white hover-shadow" style="transition: 0.3s ease;">
        <div class="card-body p-3 d-flex flex-column">

            {{-- Title --}}
            <h5 class="fw-semibold mb-2" style="font-size: 1.05rem; color: #1d3557;">
                <i class="fa fa-file-pdf text-danger me-1"></i> 
                {{ \Illuminate\Support\Str::limit($rs_val->title, 60) }}
            </h5>

            {{-- PDF Preview --}}
            <div class="mb-3 border border-light rounded" style="overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                <iframe 
                    src="{{ $pdfUrl }}" 
                    width="100%" 
                    height="200px" 
                    style="border: none;">
                </iframe>
            </div>

            {{-- Description --}}
            <p class="text-muted mb-3 flex-grow-1" style="font-size: 0.875rem;">
                {{ \Illuminate\Support\Str::limit($rs_val->description, 100) }}
            </p>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                {{-- View Button --}}
                <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill me-2">
                    <i class="fa fa-eye me-1"></i> View
                </a>

                {{-- Admin Buttons --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-danger" {{-- onclick="deletePdf({{ $rs_val->id }})" --}}>
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach
