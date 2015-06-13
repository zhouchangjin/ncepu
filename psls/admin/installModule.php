<?php
$server=$_POST["server"];
$dbname=$_POST["dbname"];
$username=$_POST["username"];
$password=$_POST["password"];
$path=$_POST["path"];
$con = mysql_connect($server,$username,$password);
if(!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbname, $con);
$sql=file_get_contents("../database/".$path);
$sqlarray= explode(";",$sql);
for($i=0;$i<sizeof($sqlarray);$i++){
	$sqlt=$sqlarray[$i];
	mysql_query("set names utf8");
	$result=mysql_query($sqlt);
	echo $sqlt.'<br/>';
	if(!$result){
		echo '<br/>'.mysql_error();
	}
}
mysql_close($con);
echo '<br>all finished';
?>