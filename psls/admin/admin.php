<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>系统安装</title>
	<link rel="stylesheet" type="text/css" href="/psls/ui/jquery-easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="/psls/ui/jquery-easyui/themes/icon.css">
	<script type="text/javascript" src="/psls/ui/jquery-easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/psls/ui/jquery-easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="/psls/ui/jquery-easyui/src/jquery.parser.js"></script>
	<script type="text/javascript" src="/psls/ui/jquery-easyui/bootstrap-carousel.js"></script>
	<script type="text/javascript" src="/psls/ui/bootstrap/js/bootstrap.js"></script>
	<script>
	   var msg={
	   }
		var install=<?php 
				echo json_encode(parse_ini_file("../setup.ini"));
		?>;
		var module=<?php 
				echo json_encode(parse_ini_file("../database/module.ini"));
		?>;
		function installation(){

			var server=$('#server')[0].value;
			var username=$('#username')[0].value;
			var password=$('#password')[0].value;
			var dbname=$('#dbname')[0].value;
			$.post("/psls/admin/install.php",{
				server:server,
				username:username,
				password:password,
				dbname:dbname
			},function(data){
				$('#log').html(data);
			});	
		}
		$(function(){
			if(install.setup=="1"){
				$('#btn1').attr("disabled","true");
			}
			var html="";
			for(prop in module){
				html+="<tr><td>"+prop+"</td><td>"+module[prop]+"</td><td><button onclick='installMod(\""+module[prop]+"\")'>安装</button></td><td><button onclick='showDetail(\""+module[prop]+"\")'>详情</button></td><td id='"+prop+"' width=40></td></tr>";
				
			}
			$('#modu').html(html);
		});
		function installMod(url){
			var server=$('#server')[0].value;
			var username=$('#username')[0].value;
			var password=$('#password')[0].value;
			var dbname=$('#dbname')[0].value;
			$.post("/psls/admin/installModule.php",{
				server:server,
				username:username,
				password:password,
				dbname:dbname,
				path:url
			},function(data){
				msg[url]=data;
			});	
		}
		function showDetail(url){
			$('#log1').html(msg[url]);
		}	
	</script> 
</head>
<body>
系统安装

<table>
<tr><td>服务器地址</td><td><input id="server" name="server" type="text" value="localhost"/></td></tr>
<tr><td>数据库名称</td><td><input id="dbname" name="dbname" type="text" value="psls"/></td></tr>
<tr><td>用户名</td><td><input id="username" name="username" type="text" value="root"/></td></tr>
<tr><td>密码</td><td><input id="password" name="password" type="password" value=""/></td></tr>
<tr><td>安装</td><td><button id="btn1" onclick="installation()">安装</button></td></tr>
</table>
<div id="log"></div>
<table id="modu" border="1">
</table>
<div id="log1"></div>

</body>
</html>