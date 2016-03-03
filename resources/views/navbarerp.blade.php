<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XXX - @yield('title')</title>
    <link href="{{ asset("//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="/css/navbar.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/erp">XXX</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">                        
<!--                         <li class="dropdown"> -->
<!--                             <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><input type="text" id="road_leave_city" /></a> -->
<!--                         </li> -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">基础资料<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/product/items">物料</a></li>
                                <li><a href="/product/itemclasses">物料类别</a></li>
                                <li><a href="/product/boms">物料清单</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">库存<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/inventory/warehouses">仓库</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">销售<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/sales/salesorders">销售订单</a></li>
                                <li><a href="/custinfos">客户</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/inventory/inventoryAvailabilityBySalesorder">库存可用量</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">采购<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/purchase/purchaseorders">采购订单</a></li>
                                <li><a href="/purchase/vendinfos">供应商</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/inventory/inventoryAvailability">库存可用量</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">客户关系<span class="caret"></span></a>
                            <ul class="dropdown-menu">
{{--                                <li><a href="/crmaddounts">账户</a></li> --}}
                                <li><a href="/contacts">联系人</a></li>
                                <li><a href="/addr/addrs">地址</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">财务<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/accounting/receivables">应收</a></li>
                                <li><a href="/accounting/payables">应付</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">系统<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/system/employees">员工</a></li>
                                <li><a href="/system/users">用户管理</a></li>
{{--                                <li><a href="/system/permissions">权限管理</a></li> --}}
                            </ul>
                        </li>
                    </ul>
{{--                    <ul class="nav navbar-nav navbar-right">
                        @if((Auth::check()))
                            <li><a href="#">{!! Auth::user()->name !!}</a></li>
                            <li><a href="/auth/logout">退出登录</a></li>
                        @else
                            {!! Redirect::to('auth/login'); !!}
<!--                             {!! redirect('/') !!} -->
                        @endif
                    </ul>
--}}
                </div>
            </div>
        </nav>
        
        <div class="panel panel-info">
                @yield('main')
        </div>        
    </div>
    
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>    
</body>
    @yield('script')
</html>