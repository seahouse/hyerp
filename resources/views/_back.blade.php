@if (isset(Auth::user()->email) and Auth::user()->email == "admin@admin.com")
	@if ($_SERVER['REQUEST_URI'] == '/mapproval')
	@elseif ($_SERVER['REQUEST_URI'] == '/mddauth')
	@else
	<input name='ht' type="button" onclick="javascript:history.go(-1)" value="后退" class="btn"></input>
	@endif
@endif

<script type="text/javascript">
	console.log("{{ $_SERVER['REQUEST_URI'] }}");
</script>