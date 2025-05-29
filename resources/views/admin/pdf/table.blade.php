@foreach ($rs_result as $rs_val)
    <div class="col-lg-4">
        <iframe src="{{ route('admin.common.pdf.view', Crypt::encrypt('app/'.$rs_val->pdf_path)) }}" width="100%" height="400px">
            This browser does not support PDFs. Please download the PDF to view it: <a href="{{ route('admin.common.pdf.view', Crypt::encrypt('app/'.$rs_val->pdf_path)) }}">Download PDF</a>
        </iframe>
    </div>
@endforeach
