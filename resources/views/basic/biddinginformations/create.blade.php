@extends('navbarerp')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }} ">
@endsection

@section('main')
    @can('basic_biddinginformation_edit')
        <h2>投标项目 -- 添加</h2>
        <hr/>

        {!! Form::open(array('url' => 'basic/biddinginformations', 'class' => 'form-horizontal')) !!}
            @include('basic.biddinginformations._form', ['submitButtonText' => '添加'])

        <select class="form-control" id="aaa" data-live-search="true">
            {{--<option value="0">人工导入</option>--}}
            {{--<option value="1">数据服务平台</option>--}}
        </select>


            @foreach($biddinginformationdefinefields as $biddinginformationdefinefield)
                <div class="form-group">
                    {!! Form::label($biddinginformationdefinefield->name, $biddinginformationdefinefield->name, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-4 col-sm-6'>
                        @if ($biddinginformationdefinefield->type == 2)
                            <?php $arr = explode(',', $biddinginformationdefinefield->select_strings); ?>
                                {!! Form::select($biddinginformationdefinefield->name, array_combine($arr, $arr), null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
                        @else
                            {{--{!! Form::text($biddinginformationdefinefield->name, null, ['class' => 'form-control']) !!}--}}
                            {!! Form::text($biddinginformationdefinefield->name, null, ['class' => 'form-control']) !!}
                        @endif
                    </div>
                    <div class='col-xs-4 col-sm-4'>
                        @can('basic_biddinginformation_remark')
                            {!! Form::text($biddinginformationdefinefield->name . '_remark', null, ['class' => 'form-control', 'placeholder' => '备注/批注']) !!}
                        @endcan
                    </div>
                </div>
            @endforeach

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
    {{--<script type="text/javascript" src="/js/jquery-editable-select.js"></script>--}}
    <script type="text/javascript" src="/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/bootstrap-select/js/i18n/defaults-zh_CN.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#aaa").selectpicker()
                .on('shown.bs.select', function (e) {
                    $.ajax({
                        type: "GET",
                        url: "{!! url('/system/users/getitemsbykey/吴') !!}",
                        success: function(result) {
//                            alert('bbb');
                            var strhtml = '';
                            $.each(result.data, function(i, field) {
                                strhtml += "<option value=" + field.name + ">" + field.name + "</option>";
                            });
//                            alert(strhtml);
                            $("#aaa").empty().append(strhtml);
                            $("#aaa").selectpicker('refresh');
                            $('#aaa').selectpicker('render');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert('error');
                        }
                    });
            })

//            $("#aaa").editableSelect({
//                onCreate: function () {
//                    console.log("下拉框创建");
//                },
//                onShow:function () {
//                    console.log("下拉框显示")
//                },
//                onHide:function () {
//                    console.log("下拉框隐藏")
//                },
//                onSelect:function () {
//                    console.log("下拉框选项被选中")
//                }
//            })
//                .on('select.editable-select', function (e, dom) {
//                    console.log(dom.val() + '' + dom.text());
//                })

            ;

//                .on('input', function (e) {
//                    bindSc($(this).val());
//                })
//            ;

//            $("select[name='aaa']").on('input', function (e) {
//                alert('aaa');
//                bindSc($(this).val());
//            });

            var bindSc = function (value) {
                alert('aaa');
                var search = value;
                $.ajax({
                    type: "GET",
                    url: "{!! url('/system/users/getitemsbykey/吴') !!}",
                    success: function(result) {
                        $(this).editableSelect('clear');//清空现有数据
                        $.each(result.data, function(i, field) {
                            $('#i_CustomerId_ES').editableSelect('add', function () {
                                $(this).val(field.id);
                                $(this).text(field.name);
                            });//调用add方法 通过函数的方式绑定上val和txt
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });

//                $.get('...', {search: search}, function (result) {
//                    $('#i_CustomerId_ES').editableSelect('clear');//清空现有数据
//                    $.each(result, function (i, t) {
//                        $('#i_CustomerId_ES').editableSelect('add', function () {
//                            $(this).val(t.Id);
//                            $(this).text(t.Name);
//                        });//调用add方法 通过函数的方式绑定上val和txt
//                    })
//                })
            }



//            $('#select_sohead')
//                .editableSelect({
//                    effects: 'slide',
//                })
//
//                .on('select.editable-select', function (e, li) {
//                    if (li.val() > 0)
//                        $('input[name=sohead_id]').val(li.val());
//                    else
//                        $('input[name=sohead_id]').val('');
//                })
//            ;
        });
    </script>
@endsection
