@extends('navbarerp')

@section('title', '施工标字段')

@section('main')
    @can('basic_constructionbidinformationfield_view')
    <div class="panel-heading">
        <a href="{{ url('basic/constructionbidinformationfields/create') }}" class="btn btn-sm btn-success">新增</a>
        @can('basic_constructionbidinformationfield_edit')
            <a href="{{ url('basic/constructionbidinformationfields/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>
        @endcan
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/basic/constructionbidinformationfields/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
        <div class="form-group-sm">
            {!! Form::select('unit', $unitstrList, null, ['class' => 'form-control', 'placeholder' => '--单位--']) !!}

            {!! Form::select('projecttype', $projecttypes_constructionbidinformationfield, null, ['class' => 'form-control', 'placeholder' => '--项目类型--']) !!}

            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '名称']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div> 

    
    @if ($constructionbidinformationfields->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>排序</th>
                <th>项目类型</th>
                <th>华星单价</th>
                <th>投标人单价</th>
                <th>单位</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($constructionbidinformationfields as $constructionbidinformationfield)
                <tr>
                    <td>
                        {{ $constructionbidinformationfield->name }}
                    </td>
                    <td>
                        {{ $constructionbidinformationfield->sort }}
                    </td>
                    <td>
                        {{ $constructionbidinformationfield->projecttype }}
                    </td>
                    <td>
                        {{ $constructionbidinformationfield->unitprice }}
                    </td>
                    <td>
                        {{ $constructionbidinformationfield->unitprice_bidder }}
                    </td>
                    <td>
                        {{ $constructionbidinformationfield->unit }}
                    </td>
                    <td>
                        <div class="form-inline">
                            <a href="{{ URL::to('/basic/constructionbidinformationfields/'.$constructionbidinformationfield->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                            {{--<a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/attachsohead') }}" class="btn btn-success btn-sm" target="_blank">关联订单</a>--}}
                            {{--<a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/peoplecount') }}" class="btn btn-success btn-sm" target="_blank">人数</a>--}}
                            {!! Form::open(array('route' => array('basic.constructionbidinformationfields.destroy', $constructionbidinformationfield->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $constructionbidinformationfields->setPath('/basic/constructionbidinformationfields')->appends($inputs)->links() !!}


    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif

    @else
        无权限
    @endcan
@endsection

@section('script')
    <script type="text/javascript" src="/js/jquery-editable-select.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btn_xmjlsgrz_sohead_id").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('dingtalk/dtlogs/relate_xmjlsgrz_sohead_id') !!}",
                    data : $('#frmCondition').serialize(),
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert(result.errmsg);
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });

            $("#btn_gctsrz_sohead_id").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('dingtalk/dtlogs/relate_gctsrz_sohead_id') !!}",
                    data : $('#frmCondition').serialize(),
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert(result.errmsg);
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });


            $('#select_xmjlsgrz_sohead')
                .editableSelect({
                    effects: 'slide',
                })
                //                .on('shown.editable-select', function (e) {
                //                    console.log("shown");
                //                    console.log($('#selectProject').val());
                //                    if ($('#selectProject').val() == "--项目--")
                //                        $('#selectProject').val("");
                //                })
                .on('select.editable-select', function (e, li) {
//                    console.log(li.val() + li.text());
                    if (li.val() > 0)
                        $('input[name=xmjlsgrz_sohead_id]').val(li.val());
                    else
                        $('input[name=xmjlsgrz_sohead_id]').val('');
//                    console.log($('input[name=sohead_id]').val());
//                    console.log($('#project_id').val());
                })
            ;

            $('#select_xmjlsgrz_project')
                .editableSelect({
                    effects: 'slide',
                })
                .on('select.editable-select', function (e, li) {
//                    console.log(li.val() + li.text());
                    if (li.val() > 0)
                        $('input[name=xmjlsgrz_project_id]').val(li.val());
                    else
                        $('input[name=xmjlsgrz_project_id]').val('');
//                    console.log($('input[name=sohead_id]').val());
//                    console.log($('#project_id').val());
                })
            ;
        });

    </script>
@endsection
