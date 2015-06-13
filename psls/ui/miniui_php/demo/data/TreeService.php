<?php
require_once('invoke.php');

function LoadTree(){
	
	$myconn = createDB();
	
	$sql = "select * from plus_file order by updatedate";
	$result=mysql_query($sql,$myconn);
	$data = array();
	
	while($row=mysql_fetch_array($result))
	{
		array_push($data,$row);
	}
	
	writeJSON($data);
}

function LoadNodes(){
	$id = $_GET["id"];
	if(empty($id) || $id == null){
		$id = "-1";
	}
	
	$myconn = createDB();
	
	$sql = "select * from plus_file where pid = '" . $id . "' order by updatedate";
	$result=mysql_query($sql,$myconn);
	$data = array();
	
	while($row=mysql_fetch_array($result))
	{
		
		$nodeId = $row["id"];
		
		$sql2 = "select * from plus_file where pid = '" . $nodeId . "' order by updatedate";
		$result2=mysql_query($sql2,$myconn);
		$node = mysql_fetch_array($result2);
		if($node){
			$row["isLeaf"] = false;
			$row["expanded"] = false;
		}
		
		array_push($data,$row);
	}
	
	writeJSON($data);
}
?>