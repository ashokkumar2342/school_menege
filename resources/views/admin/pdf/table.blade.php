@foreach($rs_result as $rs_val)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ $rs_val->title }}</h5>
            </div>
            <div class="card-body">
              {{--   <iframe 
                    src="{{ route('admin.common.pdf.view2', Crypt::encrypt('app/'.$rs_val->pdf_path)) }}" 
                    width="100%" 
                    height="400px"
                ></iframe> --}}
               
              {{--   <iframe 
    src="{{ asset('pdfjs/web/viewer.html') }}?file={{ route('admin.common.pdf.view', Crypt::encrypt('app/' . $rs_val->pdf_path)) }}&disableDownload=true&disablePrint=true&toolbar=0"
    width="100%"
    height="700px"
    style="border: none;">
</iframe> --}}
{{-- <iframe 
    src="{{ url('pdfjs/web/viewer.html') }}?file={{ urlencode(route('admin.common.pdf.view2', Crypt::encrypt('app/' . $rs_val->pdf_path))) }}&disableDownload=true&disablePrint=true"
    width="100%" 
    height="700px" 
    style="border: none;">
</iframe>
 --}}
<iframe 
    src="{{ url('pdfjs/web/viewer.html') }}?file={{ urlencode(route('admin.common.pdf.view', Crypt::encrypt('app/' . $rs_val->pdf_path))) }}&disableDownload=true&disablePrint=true"
    width="100%" 
    height="700px" 
    style="border: none;">
</iframe>
                <p class="mt-2">{{$rs_val->description }}</p>
            </div>
        </div>
    </div>
@endforeach

