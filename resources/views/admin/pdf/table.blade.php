@foreach($rs_result as $rs_val)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ $rs_val->title }}</h5>
            </div>
            <div class="card-body">
                <iframe 
                    src="{{ route('admin.common.pdf.view', Crypt::encrypt('app/'.$rs_val->pdf_path)) }}" 
                    width="100%" 
                    height="400px"
                ></iframe>
                <p class="mt-2">{{$rs_val->description }}</p>
            </div>
        </div>
    </div>
@endforeach

