<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">            
                {{ config('custom.companyname') }}
                <!-- <img alt="Brand" src="/images/logo.png" width="30" height="30"> -->
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
            @unless (Auth::guest())
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">基础资料<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_product')
                        <li><a href="/product/items">物料</a></li>
                        <li><a href="/product/itemclasses">物料类别</a></li>
                        <li><a href="/product/boms">物料清单</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">库存<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_inventory')
                        <li><a href="/inventory/warehouses">仓库</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">销售<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_sales')
                        <li><a href="/sales/salesorders">销售订单</a></li>
                        <li><a href="/sales/custinfos">客户</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/inventory/inventoryAvailabilityBySalesorder">库存可用量</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">采购<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_purchase')
                        <li><a href="/purchase/purchaseorders">采购订单</a></li>
                        <li><a href="/purchase/vendinfos">供应商</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/inventory/inventoryAvailability">库存可用量</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">客户关系<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_crm')
                        {{-- <li><a href="/crmaddounts">账户</a></li> --}}
                        <li><a href="/contacts">联系人</a></li>
                        <li><a href="/addr/addrs">地址</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">财务<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_accounting')
                        <li><a href="/accounting/receivables">应收</a></li>
                        <li><a href="/accounting/payables">应付</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">审批<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_approval')
                        <li><a href="/approval/reimbursements">报销</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/approval/approversettings">设置</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">系统<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @can('module_system')
                        <li><a href="/system/employees">员工</a></li>
                        <li><a href="/system/users">用户管理</a></li>
                        @endcan
                        {{-- <li><a href="/system/permissions">权限管理</a></li> --}}
                    </ul>
                </li>
            @endunless
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登录</a></li>
                    <li><a href="{{ url('/register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出登录</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>