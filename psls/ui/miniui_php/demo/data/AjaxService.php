<?php

require_once('invoke.php');
function SearchEmployees(){
	$key = request("key");
	$pageIndex = request("pageIndex");
	$pageSize = request("pageSize");
	
	$sortField = request("sortField");
    $sortOrder = request("sortOrder");
	
	// require_once('TestDB.php');
	// $testDB = new TestDB();
	// $resultData = $testDB->SearchEmploye($key,$pageIndex,$pageSize,$sortField,$sortOrder);
	echo '{"total":13,"data":[{"id":"1","account":"a","password":"d7afde3e7059cd0a0fe09eec4b0008cd","name":"","role_ids":"","status":"2","cdate":"0000-00-00 00:00:00","edate":"0000-00-00 00:00:00"},{"id":"2","account":"b","password":"d7afde3e7059cd0a0fe09eec4b0008cd","name":"a","role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:01:05"},{"id":"3","account":"c","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:39"},{"id":"4","account":"d","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:40"},{"id":"5","account":"e","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:42"},{"id":"6","account":"f","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:43"},{"id":"7","account":"g","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:44"},{"id":"8","account":"h","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:45"},{"id":"9","account":"i","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:48"},{"id":"10","account":"j","password":null,"name":null,"role_ids":"2","status":"0","cdate":"0000-00-00 00:00:00","edate":"2012-09-13 23:03:49"}]}';
	// writeJSON($resultData);
};
function SaveEmployees(){
	$json = request("data");
	$rows = php_json_decode($json);
	require_once('TestDB.php');
	$testDB = new TestDB();
	foreach ($rows as $row){
		$id = $row["id"] != null?$row["id"]:"";
   		$state = $row["_state"] != null?$row["_state"]:"";
		if($state == "added" || $id == ""){ //新增：id为空，或_state为added
			$row["createtime"] = date("Y-m-d   h:i:s");
			$testDB->InsertEmployee($row);
		}
		else if ($state == "removed" || $state == "deleted")
    {
			$testDB->DeleteEmployee($id);
    }
		else if ($state == "modified" || $state)  //更新：_state为空或modified
    {
			$testDB->UpdateEmployee($row);
    }
	}  
};
function RemoveEmployees(){
	$idStr = request("id");
	if (empty($idStr)) return;
    $ids = explode(',',$idStr);
	for ($i = 0, $l = count($ids); $i < $l; $i++)
    {
        $id = $ids[$i];
		require_once('TestDB.php');
		$testDB = new TestDB();
		$testDB->DeleteEmployee($id);
    }
}
function GetEmployee(){
	$id = $_GET["id"];
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetEmployee($id);
	writeJSON($data);
};
function GetDepartments(){
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetDepartments();
	writeJSON($data);
};
function GetPositions(){
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetPositions();
	writeJSON($data);
}
function GetEducationals(){
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetEducationals();
	writeJSON($data);
}
function GetPositionsByDepartmenId(){
	$id = request("id");
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetPositionsByDepartmenId($id);
	writeJSON($data);
}
function GetDepartmentEmployees(){
	$dept_id = request("dept_id");
	$pageIndex = request("pageIndex");
    $pageSize = request("pageSize");
	
	require_once('TestDB.php');
	$testDB = new TestDB();
	$data = $testDB->GetDepartmentEmployees($dept_id,$pageIndex,$pageSize);
	writeJSON($data);
}
function SaveDepartment(){
	$departmentsStr = request("departments");
	$departments = php_json_decode($departmentsStr);
	require_once('TestDB.php');
	$testDB = new TestDB();
	foreach ($departments as $d){
		$data = $testDB->UpdateDepartment($d);
	}
}
function FilterCountrys(){
	$key = request("key");
	$value = request("value");
	
	$values = explode(",",$value);
	$valueMap = array();
	for($i=0;$i<count($values);$i++){
		$id = $values[$i];
		$valueMap[$id] = true;
	}
	$path = "countrys.txt";
	$content = substr(file_get_contents($path), 3);
	$data = json_decode($content,true);
	for($i=count($data)-1;$i>=0;$i--){
		$o = $data[$i];
		$id = $o["id"];
		if ($valueMap[$id] !== null){
			array_splice($data,$i,1);
		}
	}
	
	$result = array();
	for($i=0;$i<count($data);$i++){
		$o = $data[$i];
		$text = $o["text"];
		
		if(empty($key) || strripos($text,$key) !== false){
			array_push($result,$o);
		}
	}
	
	writeJSON($result);
}
function FilterCountrys2(){
	$key = request("key");
	$path = "countrys.txt";
	$content = substr(file_get_contents($path), 3); 
	$data = json_decode($content,true);
	$result = array();
	for($i=0;$i<count($data);$i++){
		$o = $data[$i];
		$text = $o["text"];
		
		if(empty($key)|| $key=="" || strripos($text,$key) !== false){
			array_push($result,$o);
		}
	}
	
	writeJSON($result);
}

?>