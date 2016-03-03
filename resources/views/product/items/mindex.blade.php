<!DOCTYPE html>
<!--
    Licensed to the Apache Software Foundation (ASF) under one
    or more contributor license agreements.  See the NOTICE file
    distributed with this work for additional information
    regarding copyright ownership.  The ASF licenses this file
    to you under the Apache License, Version 2.0 (the
    "License"); you may not use this file except in compliance
    with the License.  You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing,
    software distributed under the License is distributed on an
    "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
     KIND, either express or implied.  See the License for the
    specific language governing permissions and limitations
    under the License.
-->
<html>
    <head>
        <!--
        Customize this policy to fit your own app's needs. For more guidance, see:
            https://github.com/apache/cordova-plugin-whitelist/blob/master/README.md#content-security-policy
        Some notes:
            * gap: is required only on iOS (when using UIWebView) and is needed for JS->native communication
            * https://ssl.gstatic.com is required only on Android and is needed for TalkBack to function properly
            * Disables use of inline scripts in order to mitigate risk of XSS vulnerabilities. To change this:
                * Enable inline JS: add 'unsafe-inline' to default-src
        -->
        <meta http-equiv="Content-Security-Policy" content="default-src 'self' data: gap: https://ssl.gstatic.com 'unsafe-eval'; style-src 'self' 'unsafe-inline'; media-src *">
        <meta name="format-detection" content="telephone=no">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
<!--        <link rel="stylesheet" type="text/css" href="css/index.css"> -->
        <title>Hello World</title>
        <link href="css/jquery.mobile-1.4.5.min.css" rel="stylesheet" type="text/css">  
        
<!--        <style type="text/css">
			#submit {
				float:right; margin:10px;
			}
		</style> -->
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
        
<!--        <div data-role="page" id="about">
        	<div data-role="header" data-add-back-btn="true">
            	<h1>About CFHour</h1>
            </div>
            <div data-role="content">
            	<p>CFHour is a weekly podcast primarily focused on
                	ColdFusion development, but brings you news and
                    updates about all things 'web'.</p>
                <p>Join your hosts Dave Ferguson, Scott Stroz and their
                	producer Matt Gifford for the latest information,
                    live shows and guest interviews.</p>
                <p><a href="http://www.cfhour.com" data-role="button">Visit www.cfhour.com</a></p>
            </div>
            <div data-role="footer" data-position="fixed">
            	<h4>&copy; cfhour.com</h4>
            </div>
        </div> -->
        
        <div data-role="popup" id="menu">
        	<ul>
            	<li>abc</li>
                <li>ccc</li>
            </ul>
        </div>
    
<!--    	<div>
        	<h1>Hello, world</h1>
            <div id="t1"></div>
        </div> -->
<!--        <div class="app">
            <h1>Apache Cordova</h1>
            <div id="deviceready" class="blink">
                <p class="event listening">Connecting to Device</p>
                <p class="event received">Device is Ready</p>
            </div>
        </div> -->
<!--        <script type="text/javascript" src="js/index.js"></script> -->
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
