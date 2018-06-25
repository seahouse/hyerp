@extends('approval.mindexmy_nav')

@section('title', '我发起的')

@section('mindexmy_main')
    
    {!! Form::open(['url' => '/approval/mindexmy/search', 'method' => 'post', 'role' => 'search']) !!}
        <div class="container-fluid search-area">
            <div class="row">
                <div class="ctrl1">
                    {!! Form::select('approvaltype', array('供应商付款' => '供应商付款', '下发图纸' => '下发图纸'), null, ['class' => 'form-control ctrl1', 'id' => 'approvaltype']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-8 col-sm-8 ctrl1">
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称']) !!}
                </div>
                <div class="col-xs-4 col-sm-4 ctrl2">
                    {!! Form::select('paymenttype', array('预付款' => '预付款', '进度款' => '进度款', '到货款' => '到货款', '安装结束款' => '安装结束款', '调试运行款' => '调试运行款', '环保验收款' => '环保验收款', '质保金' => '质保金'), null, 
                        ['class' => 'form-control', 'placeholder' => '--付款类型--']) !!}
                </div>
            </div>
            
            <div class="row">
                <button id="btnMore" type="button" class="btn btn-link more-search">展开更多条件</button>
                <input class="btn btn-primary search" type="submit" value="搜索">
            </div>
            <div id="expandArea" style="display:none;">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 ctrl1">
                        {!! Form::text('projectname', null, ['class' => 'form-control ctrl1', 'placeholder' => '项目名称']) !!}
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-sm-12 ctrl1">
                        {!! Form::text('productname', null, ['class' => 'form-control ctrl1', 'placeholder' => '商品名称']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 ctrl1">
                        {!! Form::text('suppliername', null, ['class' => 'form-control ctrl1', 'placeholder' => '供应商']) !!}
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursements/mshow/', 'href_suffix' => '',
            'href_pre_paymentrequest' => '/approval/paymentrequests/mshow/',
            'href_pre_issuedrawing' => '/approval/issuedrawing/mshow/'
        ])

    @if (isset($inputs))
        {!! $items->setPath('/approval/mindexmy')->appends($inputs)->links() !!}
    @else
        {!! $items->links() !!}
    @endif


        

@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            var panel = $('#expandArea')[0];
            $('#btnMore').click(function(){
                if (panel.style.display == 'none') {
                    panel.style.display = '';
                    this.innerHTML= '收起更多条件';
                }
                else {
                    panel.style.display = 'none';
                    this.innerHTML= '展开更多条件';
                }
            })
        });
    </script>
@endsection
