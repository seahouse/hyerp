@if (Auth::user()->email == "admin@admin.com")
	@if ($_SERVER['REQUEST_URI'] == '/mapproval')
	@else
	<input name='ht' type="button" onclick="javascript:history.go(-1)" value="后退" class="btn"></input>
	@endif
@endif
