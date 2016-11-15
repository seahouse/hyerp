@extends('navbarerp')

@section('title', '审批单报表')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">付款审批单报表
{{--            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div> --}}
        </div>
    </div>
    
    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
<!--     <div id="main" style="width: 600px;height:400px;"></div>
    <div id="graph_user" style="width: 600px;height:400px;"></div> -->
    <div class="panel-body">
    	<div id="main" class="col-xs-12 col-sm-6" style="height:400px"></div>
    	<div id="graph_user" class="col-xs-12 col-sm-6" style="height:400px"></div>
    </div>    
    
  

@endsection

@section('script')
	<script src="/js/{{ config('custom.echarts_jsname', 'echarts.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {

        	// option = {
        	// 	baseOption: {
        	// 		title: {
        	// 			x:'center'
        	// 		},
        	// 		series: [
        	// 			{
				     //        title: {
				     //            text: '月份金额'
				     //        },
				     //        tooltip: {},
				     //        legend: {
				     //            data:['金额']
				     //        },
				     //        xAxis: {
				     //            // data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
				     //            data: [{!! "'" . implode("','",array_pluck($paymentrequests, 'm')) . "'" !!}]
				     //        },
				     //        yAxis: {},
				     //        series: [{
				     //            name: '金额',
				     //            type: 'bar',
				     //            // data: [5, 20, 36, 10, 10, 20]
				     //            data: [{{ implode(',',array_pluck($paymentrequests, 'sum')) }}]
				     //        }]
        	// 			},
        	// 			{
				     //        title: {
				     //            text: '员工金额'
				     //        },
				     //        tooltip: {},
				     //        legend: {
				     //            data:['金额']
				     //        },
				     //        xAxis: {
				     //            // data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
				     //            data: [{!! "'" . implode("','",array_pluck($paymentrequests_user, 'name')) . "'" !!}]
				     //        },
				     //        yAxis: {},
				     //        series: [{
				     //            name: '金额',
				     //            type: 'bar',
				     //            // data: [5, 20, 36, 10, 10, 20]
				     //            data: [{{ implode(',',array_pluck($paymentrequests_user, 'sum')) }}]
				     //        }]
        	// 			}
        	// 		]
        	// 	},
        	// 	media: [
        	// 		{
        	// 			query: {
        	// 				maxWidth: 300		// 当容器宽度小于 300 时
        	// 			},
        	// 			option: {
        	// 				legend: {
        	// 					orient: 'vertical'	// 纵向布局
        	// 				},
        	// 				series: [
        	// 					{
        	// 						radius: [20, '50%'],
        	// 						center: ['50%', '30%']
        	// 					},
        	// 					{
        	// 						radius: [30, '50%'],
        	// 						center: ['50%', '75%']
        	// 					}
        	// 				]
        	// 			}
        	// 		},
        	// 		{

        	// 		}
        	// 	]
        	// };



			// 基于准备好的dom，初始化echarts实例
	        var myChart = echarts.init(document.getElementById('main'));

	        // 指定图表的配置项和数据
	        var option = {
	            title: {
	                text: '月份金额'
	            },
	            tooltip: {},
	            legend: {
	                data:['金额']
	            },
	            grid: {
			        left: '3%',
			        right: '4%',
			        bottom: '3%',
			        containLabel: true
			    },
	            xAxis: {
	                // data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
	                data: [{!! "'" . implode("','",array_pluck($paymentrequests, 'm')) . "'" !!}]
	            },
	            yAxis: {
	            	type : 'value'
	            },
	            series: [{
	                name: '金额',
	                type: 'bar',
	                // data: [5, 20, 36, 10, 10, 20]
	                data: [{{ implode(',',array_pluck($paymentrequests, 'sum')) }}]
	            }]
	        };

	        // 使用刚指定的配置项和数据显示图表。
	        myChart.setOption(option);



			// 基于准备好的dom，初始化echarts实例
	        myChart = echarts.init(document.getElementById('graph_user'));

	        // 指定图表的配置项和数据
	        var option = {
	            title: {
	                text: '员工金额'
	            },
	            tooltip: {},
	            legend: {
	                data:['金额']
	            },
	            grid: {
			        left: '3%',
			        right: '4%',
			        bottom: '3%',
			        containLabel: true
			    },
	            xAxis: {
	                // data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
	                data: [{!! "'" . implode("','",array_pluck($paymentrequests_user, 'name')) . "'" !!}]
	            },
	            yAxis: {},
	            series: [{
	                name: '金额',
	                type: 'bar',
	                // data: [5, 20, 36, 10, 10, 20]
	                data: [{{ implode(',',array_pluck($paymentrequests_user, 'sum')) }}]
	            }]
	        };

	        // 使用刚指定的配置项和数据显示图表。
	        myChart.setOption(option);
        });
    </script>
@endsection
