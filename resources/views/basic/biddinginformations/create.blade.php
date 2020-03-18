@extends('navbarerp')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }} ">
    <link href="{{ asset('css/jquery-editable-select.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('main')
    @can('basic_biddinginformation_edit')
        <h2>投标项目 -- 添加</h2>
        <hr/>

        {!! Form::open(array('url' => 'basic/biddinginformations', 'class' => 'form-horizontal')) !!}
            @include('basic.biddinginformations._form', ['submitButtonText' => '添加'])

        <!-- <div class="form-control" id="aaa" >
        </div> -->

        <div id="dynamicSelectWrapper">
            @foreach($biddinginformationdefinefields as $biddinginformationdefinefield)
                <div class="form-group">
                    {!! Form::label($biddinginformationdefinefield->name, $biddinginformationdefinefield->name, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-4 col-sm-6' >
                        @if ($biddinginformationdefinefield->type == 2)
                            <?php $arr = explode(',', $biddinginformationdefinefield->select_strings); ?>
                                {!! Form::select($biddinginformationdefinefield->name, array_combine($arr, $arr), null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
                        @else
                            <div class="form-control dynamicSelect" data-name="<?php echo $biddinginformationdefinefield->name?>"></div>
                            <!-- {!! Form::text($biddinginformationdefinefield->name, null, ['class' => 'form-control']) !!} -->
                        @endif
                    </div>
                    <div class='col-xs-4 col-sm-4'>
                        @can('basic_biddinginformation_remark')
                            {!! Form::text($biddinginformationdefinefield->name . '_remark', null, ['class' => 'form-control', 'placeholder' => '备注/批注']) !!}
                        @endcan
                    </div>
                </div>
            @endforeach
            </div>

            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('添加', ['class' => 'btn btn-primary']) !!}
            </div>
            </div>
        {!! Form::close() !!}

        @include('errors.list')
    @else
        无权限
    @endcan
@endsection

@section('script')
    <script type="text/javascript" src="/js/jquery-editable-select.js"></script>
    <script type="text/javascript" src="/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/bootstrap-select/js/i18n/defaults-zh_CN.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function(e) {
        $('.dynamicSelect').each(function(index, select) {
            var name = $(select).attr('data-name');
            $(select).editableSelect({id: name});
        })

        $('#dynamicSelectWrapper').on('click', function (evt) {
            var target = evt.target || evt.srcElement;
            if ($(target).attr('data-name')) {
                var name = $(target).attr('data-name');
                var url = '/basic/biddinginformationitems/getvaluesbykey/' + name;
                if (window['URL_' + url]) {
                    return;
                }
                else {
                    $.get(url, {}, function (result) {
                        result && (window['URL_' + url] = true);
                        $.each(result, function (i, t) {
                            $(target).editableSelect('add', t);
                        });
                        $('#listWrapper_' + name).css('display', 'block');//fix plugin filter issue
                    });

                }
            }
                
        });



        // test
        // $("#aaa").editableSelect({id: 'aaa'});
        // $("#aaa").on('focus', function (e) {
        //     var url = '/basic/biddinginformationitems/getvaluesbykey/1';
        //     if (window['URL_' + url]) {
        //         return;
        //     }
        //     else {
        //         $.get(url, {}, function (result) {
        //             result && (window['URL_' + url] = true);
        //             $.each(result, function (i, t) {
        //                 $('#aaa').editableSelect('add', t);
        //             });
        //             $('#listWrapper_aaa').css('display', 'block')
        //         });

        //     }
            
        // });


    });

    </script>
@endsection
