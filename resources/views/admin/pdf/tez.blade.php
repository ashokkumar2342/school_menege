@extends('admin.layout.base')
@section('body')
<div class="top-bar clearfix"></div>

<div class="row gutter">
    <div class="col-md-4 col-md-6 col-sm-6">
        <div class="page-title">
            <h3></h3>
        </div>
    </div>
</div>

<div class="row gutter">
    <div class="col-lg-12">
        <div class="panel panel-default card-view cardcurvy">
            <div class="panel-wrapper">
                <div class="panel-body">
                    <div class="row" id="iframeContainer">
                        <object data="{{ route('admin.common.pdf.view', Crypt::encrypt('app/'.@$rs_val->pdf_path)) }}" type="application/pdf" style="width:100%; height:1000px;">
                            <iframe
                                id="myIframe"
                                src="{{ route('admin.common.pdf.view', Crypt::encrypt('app/'.@$rs_val->pdf_path)) }}"
                                style="width:100%; height:1000px;"
                                frameborder="0"
                                allowfullscreen
                            >
                                <p>This browser does not support PDF!</p>
                            </iframe>
                        </object>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('admin_asset/dist/js/pdf.mjs') }}"></script>
@endsection
