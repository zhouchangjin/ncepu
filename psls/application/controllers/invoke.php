<?php
ob_start();
//========================================
// function createDB(){
// 	$conn=mysql_connect("localhost","root","root");
// 	mysql_query("set names 'utf8'");
// 	mysql_select_db("plusoft_test",$conn);
// 	return $conn;
// }
//========================================
// $methodName = $_GET["method"];
// if($methodName != null){
// 	eval("\$method = ".$methodName.";");
	
// 	if($method != null) $method();
// }
function request($name){
	$value = $_GET[$name];
	if($value == null){
		//var_dump("post is doing");
		$value = $_POST[$name];
	}
	return $value;
}
function writeJSON($obj){	
	
	if(is_string($obj)) {
		ob_end_clean();
		print_r($obj);
	}else {
		$json = json_encode($obj);		
		ob_end_clean();
		print_r($json);
	}
}
function gbk2utf8($data){
	if(is_array($data)){
		return array_map('gbk2utf8', $data);
	}
	return iconv('gbk','utf-8',$data);
}
function php_json_decode($str){
	
	//stripslashes() 函数删除由 addslashes() 函数添加的反斜杠。
	$stripStr = stripslashes($str);//
	//json_decode接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
	$json = json_decode($stripStr,true);
	return $json;
}

?>