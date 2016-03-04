<!DOCTYPE html>
<html>
<head>
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />	
	<link href="{{ asset("//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css") }}" rel="stylesheet">
</head>
    <body>
    	<section id="page1" data-role="page">
        	<header data-role="header"><h1>XXX</h1></header>
            <div data-role="content" class="ui-content">
            	<form method="post" id="loginform">
                	<input type="text" name="username" id="username" value="用户名" /><br>
                    <input type="password" name="password" id="password" value="密码" /><br>
                    
                    <a data-role="button" id="submit">登录</a>
                    <a data-role="button" id="quit">退出</a>
                </form>
            </div>
        </section>
    	
    
    	<div data-role="page" id="home">
        	<div data-role="header">
            	<h1>XXX</h1>
                <a href="#popupNested" data-rel="popup" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-transition="pop">菜单</a>
                <div data-role="popup" id="popupNested" data-theme="none">
                	<div data-role="collapsible-set" >
                    	<div data-role="collapsible" data-inset="false">
                        	<h2>产品</h2>
                            <ul data-role="listview">
                            	<li><a href="#">物料</a></li>
                            </ul>
                        </div>
                        <div data-role="collapsible" data-inset="false">
                            <h2>销售</h2>
                            <ul data-role="listview">
                            	<li><a href="#">订单</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div data-role="content">
                <ul id="showList" data-role="listview" data-inset="true">
                	<div id="listItem"></div>
                    <li>123</li>
                    <li>456</li>
                </ul>
                <!--<button data-inline="true" id="prevPage">上一页</button> -->
                <a href="#" data-role="button" data-inline="true" id="prevPage" onClick="prevPage()">上一页</a>
                <a href="#" data-role="button" data-inline="true" id="nextPage" onClick="nextPage()">下一页</a>
            </div>
            <div data-role="footer">
            	<h4>&copy; cfhour.com</h4>
            </div>
        </div>
        

        
        <div data-role="popup" id="menu">
        	<ul>
            	<li>abc</li>
                <li>ccc</li>
            </ul>
        </div>
    

		<script src="js/jquery-2.1.4.min.js" type="text/javascript"></script>
		<script src="js/jquery.mobile-1.4.5.min.js" type="text/javascript"></script>
        <script src="cordova.js" type="text/javascript"></script>
        
        <script type="text/javascript">
			document.addEventListener("backbutton", onBackKeyDown, false);
			
			function onBackKeyDown() {
				if ($.mobile.activePage.is('#page1')) {
					alert('onBackKeyDown');
					$.ajax({
						type:"GET",
						// url:"http://sea2730.6655.la:1234/auth/logout",
						url:"http://localhost:8000/auth/mlogout",
						error: function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status);
							alert(xhr.responseText);
							alert(ajaxOptions);
							alert(thrownError);
						},
						success: function(msg) {
							if (msg == 'success')
							{
								navigator.app.exitApp();
							}
							else
							{
								alert('mlogout error');
								navigator.app.exitApp();
							}
						},
					});
				}
				else
					navigator.app.backHistory();
			}
		</script>
        
        <script type="text/javascript">
			jQuery(document).ready(function(e) {
                // $("input[id]").bind("focus", function() {
					// if ($(this).attr("id") == 'username' || $(this).attr("id") == 'password')
						// $(this).attr("value", "");
				// });
				
				$("#submit").bind("click", function() {
					if (valid()) {
						alert($("form#loginform").serialize());
						$.ajax({
							type:"POST",
							// url:"http://sea2730.6655.la:1234/auth/mlogin",
							url:"http://localhost:8000/auth/mlogin",
							data:$("form#loginform").serialize(),
							// data: {
								// username: $("form#username").val(),
								// password: $("form#password").val()
							// },
							// dataType:"html",
							error: function(xhr, ajaxOptions, thrownError) {
								alert(xhr.status);
								alert(xhr.responseText);
								alert(ajaxOptions);
								alert(thrownError);
							},
							success: function(msg) {
								if (msg == 'success')
								{
									// this.href = $("#home");
									$.mobile.changePage($("#home"), {reloadPage: true});
								}
								else
								{
									alert('error');
									// $.mobile.changePage("xxxx2222");
								}
							},
						});
					}
				});
				
				$("#quit").bind("click", function() {
					$.ajax({
						type:"GET",
						// url:"http://sea2730.6655.la:1234/auth/logout",
						url:"http://localhost:8000/auth/mlogout",
						error: function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status);
							alert(xhr.responseText);
							alert(ajaxOptions);
							alert(thrownError);
						},
						success: function(msg) {
							if (msg == 'success')
							{
								alert(msg);
								// $.mobile.changePage($("#home"), {reloadPage: true});
							}
							else
							{
								alert('error');
								// $.mobile.changePage("xxxx2222");
							}
						},
					});
				});
            });
			
			function valid() {
				if ($("#username").attr("value") == '' || $("#password").attr("value") == '')
				{
					alert($("#username").attr("value"));
					alert($("#password").attr("value"));
					return false;
				}
				return true;
			}
		</script>
        
        <script type="text/javascript">
			var prevUrl = '';
			var nextUrl = '';
        	$("#home").bind("pagebeforeshow", function(e) {
				// getRemoteFeed("http://sea2730.6655.la:1234/items/mindex");
				getRemoteFeed("http://localhost:8000/items/mindex");
			})
			
			var getRemoteFeed = function(dataUrl) {
				$.getJSON(dataUrl, 
					function(data) {
						var listItem = '';
						$.each(data.data, function(i, field){
							listItem += '<li>'
								+ field.item_name 
								+ '</li>';
						});
						// alert(listItem);
						$("#showList").html(listItem);
						$("#showList").listview("refresh");
						prevUrl = data.prev_page_url;
						nextUrl = data.next_page_url;
						if (prevUrl == null)
							$("#prevPage").hide();
						else
							$("#prevPage").show();
						if (nextUrl == null)
							$("#nextPage").hide();
						else
							$("#nextPage").show();
					});
			};
			
			function nextPage() {
				getRemoteFeed(nextUrl);
			}
			
			function prevPage() {
				getRemoteFeed(prevUrl);
			}
        </script>
    </body>
</html>
