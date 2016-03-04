<!DOCTYPE html>
<html>
<head>
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />	
</head>
<body>
	<!-- Start of first page: Entrance -->
	<div data-role="page" id="entrance">
		<div data-role="header">
			<h1>审批</h1>
		</div>
		<div data-role="main" class="ui-content">
			<p>
				<a href="#" class="ui-btn ui-btn-inline">待我审批</a><a href="#" class="ui-btn ui-btn-inline">我发起的</a>
			</p>
			<p>
				<a href="#createreimbursement" class="ui-btn ui-btn-inline">报销</a><a href="#" class="ui-btn ui-btn-inline">请款</a>
			</p>
		</div>
		<div data-role="footer"></div>
	</div>
	<!-- /page -->


	<!-- Start of Second page: create reimbursement -->
	<div data-role="page" id="createreimbursement">
		<div data-role="header">
			<h1>创建报销</h1>
		</div>
		<div data-role="main" class="ui-content">
			{{-- {!! Form::open(array('url' => 'approval/reimbursements')) !!} --}}
			<form method="POST" action="approval/reimbursements">
				{!! csrf_field() !!}
				<div data-role="fieldcontain">
					<label for="date">申请日期:</label>
					<input type="date" name="date" id="date" >
				</div>
				<div data-role="fieldcontain">
					<label for="number">报销编号:</label>
					<input type="text" name="number" id="number" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="amount">报销金额:</label>
					<input type="text" name="amount" id="amount" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="customer_id">客户:</label>
					<input type="text" name="customer_id" id="customer_id" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="contacts">客户联系人:</label>
					<input type="text" name="contacts" id="contacts" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="contactspost">客户联系人职务:</label>
					<input type="text" name="contactspost" id="contactspost" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="order_id">明细说明:</label>
					<input type="text" name="order_id" id="order_id" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="datego">出差去日:</label>
					<input type="date" name="datego" id="datego" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="dateback">出差回日:</label>
					<input type="date" name="dateback" id="dateback" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="mealamount">伙食补贴:</label>
					<input type="text" name="mealamount" id="mealamount" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="ticketamount">车船费:</label>
					<input type="text" name="ticketamount" id="ticketamount" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="stayamount">住宿费:</label>
					<input type="text" name="stayamount" id="stayamount" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="otheramount">其他费用:</label>
					<input type="text" name="otheramount" id="otheramount" value="" />
				</div>

				<div data-role="fieldcontain">
					<input type="submit" name="submit" value="提交"  />
				</div>
			</form>
			{{-- {!! Form::close() !!} --}}
		</div>
		<div data-role="footer"></div>
	</div>
	<!-- /page -->


	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/jquery.mobile-1.4.5.min.js"></script>
</body>
</html>