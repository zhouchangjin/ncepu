<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_c extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->m_data=array();
	}
	
	public function path(){
		$directory="application/models";
		$files = array();
		if(is_dir($directory)) {
			if($dh = opendir($directory)) {
				while(($file = readdir($dh)) !== false) {
					if($file != '.' && $file != '..') {
						if(is_dir($directory.'/'.$file))
						$files[] = $file;  // array push 的另一种写法
						
					}
				}
				closedir($dh);
			}
		}
		echo json_encode($files);
	}
	public function detail($modelpath,$modelname){
		
		$path='application/views/'.$modelpath.'/'.$modelname.'_detail_v.html';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		$this->m_data['tableName']=$this->$tmp->getTableMetadata();
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_detail.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['controllerClassName']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
		
				$flag=fwrite($fp,$line);
			}
		}
		fclose($fp1);
		fclose($fp);
		
		echo "生成detail页";
		
	}
	public function readonlyView($modelpath,$modelname){
		
		$path='application/views/'.$modelpath.'/readonly_'.$modelname.'_v.html';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		$this->m_data['tableName']=$this->$tmp->getTableMetadata();
		echo "数据库表列 ：".json_encode($this->m_data['fields'])."<br/>";
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		echo '生成路径：<a href="/psls/'.$modelpath.'/'.$modelname.'_c/readonly" >查看列表页面</a> 在新窗口里弹出页面，请点击这里<button onclick="showTab(\'/psls/'.$modelpath.'/'.$modelname.'_c/readonly\',\'查看视图\')">打开页面</button>';
		echo "当前路径是:  ".$path."<br/>";
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_readonly_view.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['controllerClassName']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
		
				$flag=fwrite($fp,$line);
			}
		}
		fclose($fp1);
		fclose($fp);
		
		echo "</body></html>";
		
	}
	public function view($modelpath,$modelname){
		$path='application/views/'.$modelpath.'/'.$modelname.'_v.html';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		$this->m_data['tableName']=$this->$tmp->getTableMetadata();
		echo "数据库表列 ：".json_encode($this->m_data['fields'])."<br/>";
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		echo '生成路径：<a href="/psls/'.$modelpath.'/'.$modelname.'_c" >查看页面</a> 在新窗口里弹出页面，请点击这里<button onclick="showTab(\'/psls/'.$modelpath.'/'.$modelname.'_c\',\'查看视图\')">打开页面</button>';
		echo "当前路径是:  ".$path."<br/>";
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_view.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['controllerClassName']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
		
				$flag=fwrite($fp,$line);
			}
		}
		fclose($fp1);
		fclose($fp);
		
		echo "</body></html>";

	}
	public function editview($modelpath,$modelname){
		$path='application/views/'.$modelpath.'/'.$modelname.'_edit_v.html';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_edit.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['controllerClassName']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
				$flag=fwrite($fp,$line);
			}
		}
		echo '在视图页面的新建按钮查看页面效果';
		fclose($fp1);
		fclose($fp);
	}
	
	public function addview($modelpath,$modelname){
		$path='application/views/'.$modelpath.'/'.$modelname.'_add_v.html';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_add.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['controllerClassName']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
				$flag=fwrite($fp,$line);
			}
		}
		echo '在视图页面的新建按钮查看页面效果';
		fclose($fp1);
		fclose($fp);
	}
	
	public function controller($modelpath,$modelname){
		$path='application/controllers/'.$modelpath.'/'.$modelname.'_c.php';
		$this->load->model(''.$modelpath.'/'.$modelname.'_m');
		$tmp=$modelname.'_m';
		$this->m_data['fields']=$this->$tmp->getFieldsMetadata();
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
		echo "<html><body>";
		echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		echo "当前路径是:  ".$path."<br/>";
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_controller.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['className']=$modelname."_c";
		$this->m_data['path']=$modelpath;
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
		
				$flag=fwrite($fp,$line);
			}
		}
		fclose($fp1);
		fclose($fp);
		echo "</head></body></html>";
	} 
	
	public function model($modelpath,$modelname){
		$path='application/models/'.$modelpath.'/'.$modelname.'_m.php';
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
		echo "<html><body>";
		echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		echo "当前路径是:  ".$path."<br/>";
		$fp = fopen($path, "w");
		$fp1 =fopen("application/controllers/template/template_model.txt","r");
		$this->m_data['table']=$modelname;
		$this->m_data['className']=$modelname."_m";
		while(!feof($fp1)){
			$line=fgets($fp1);
			$line=$this->process($line);
			if($fp){
				
				$flag=fwrite($fp,$line);
			}	
		}
	    fclose($fp1);
		fclose($fp);
		echo "</head></body></html>";

	}
	
	function process($line){
		if(strstr($line,"%TABLE_NAME%")){
			echo "替换表名：".$this->m_data['table']."<br/>";
			$line= str_replace("%TABLE_NAME%",$this->m_data['table'],$line);
		}
		if(strstr($line,"%TABLE_COMMENT%")){
			echo "替换表名：".$this->m_data['tableName']."<br/>";
			$line= str_replace("%TABLE_COMMENT%",$this->m_data['tableName'],$line);
		}
		if(strstr($line,"%CLASS_NAME%")){
			echo "替换类名：".$this->m_data['table']."<br/>";
			$line= str_replace("%CLASS_NAME%",ucfirst($this->m_data['className']),$line);
		}
		if(strstr($line,"%MODEL_PATH%")){
			echo "替换路径：".$this->m_data['path']."<br/>";
			$line= str_replace("%MODEL_PATH%",$this->m_data['path'],$line);
		}
		if(strstr($line,"%EASYUI_GRID_FIELD%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				  $name=str_replace("_id","",$this->m_data['fields'][$i]->name);
			      $tmpLine=$tmpLine.chr(0x09).chr(0x09).chr(0x09).chr(0x09).chr(0x09).'{field:"'.$name.'",title:"'.$this->m_data['fields'][$i]->comment.'",formatter:function(a,b,c){
			      		if(typeof dictionary !="undefined"){
			      			var v=dictionary["'.$this->m_data['fields'][$i]->name.'"]["dictionary"][ b.'.$this->m_data['fields'][$i]->name.'];
			      		    if(typeof v !="undefined"){
			      					return v["name"];
			      			}
			      		}
			      		return b.'.$name.';
			      		
					}}';
			      if($i<$length-1) {
			      	$tmpLine=$tmpLine.',';
			      }
			      $tmpLine=$tmpLine.chr(0x0A);
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%ADD_INPUT_ITEM%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				$dictionary=$field->dictionary;
				$type=$field->type;
				$typeStr='';
				if($field->name=="cdate"){
					continue;
				}
				if($field->name=="id" && $type=='bigint'){
					continue;
				}
				$tmpLine.='<tr><td align="right">';
				if($dictionary==0){
					if($type=="bigint"){
						$typeStr="<select id='".$field->name."' name='".$field->name."' table='".str_replace("_id","",$field->name)."' col='name' idname='id' style='width:200px' ></select>";
					}else if($type=="varchar"){
						$typeStr="<input id='".$field->name."' style='width:200px;' name='".$field->name."' type='text'/>";
					}else if($type=="tinytext" || $type=="text"){
						$typeStr="<textarea id='".$field->name."' style='width:300px;height:200px' name='".$field->name."'></textarea>";
					}else if($type=="timestamp"){
						$typeStr="<input class='easyui-datetimebox'  id='".$field->name."' style='width:200px' name='".$field->name."'/>";
					}else if($type=="int"){
						$typeStr="<input class='easyui-numberspinner' data-options='increment:1' id='".$field->name."' style='width:200px' name='".$field->name."'/>";
					}else if($type=="float"){
						$typeStr="<input id='".$field->name."' style='width:200px;' name='".$field->name."' type='text'/>";
					}
				}else{
					
					$typeStr="<select id='".$field->name."' name='".$field->name."' table='".$this->m_data['table']."' style='width:200px' ></select>";
					
				}
				$tmpLine.=$field->comment.':</td><td>'.$typeStr.'</td>';
				$tmpLine.='</tr>'.chr(0x0A);
			}
			
			$line=$tmpLine;
		}
		if(strstr($line,"%EDIT_INPUT_ITEM%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				$type=$field->type;
				$dictionary=$field->dictionary;
				$typeStr='';
				if($field->name=="id" || $field->name=="cdate"){
					continue;
				}
				$tmpLine.='<tr><td align="right">';
				if($dictionary==0){
					if($type=="bigint"){
						$typeStr="<select id='".$field->name."' name='".$field->name."' table='".str_replace("_id","",$field->name)."' col='name' idname='id' style='width:200px' ></select>";
					}else if($type=="varchar"){
						$typeStr="<input id='".$field->name."' style='width:200px;' name='".$field->name."' value='{*\$obj['".$field->name."']*}' type='text'/>";
					}else if($type=="tinytext" || $type=="text"){
						$typeStr="<textarea id='".$field->name."' style='width:300px;height:200px' name='".$field->name."'>{*\$obj['".$field->name."']*}</textarea>";
					}else if($type=="timestamp"){
						$typeStr="<input class='easyui-datetimebox'  id='".$field->name."' style='width:200px' name='".$field->name."' value='{*\$obj['".$field->name."']*}'/>";
					}else if($type=="int"){
						$typeStr="<input class='easyui-numberspinner' data-options='increment:1' id='".$field->name."' style='width:200px' name='".$field->name."' value='{*\$obj['".$field->name."']*}'/>";
					}else if($type=="float"){
						$typeStr="<input id='".$field->name."' style='width:200px;' name='".$field->name."' type='text'  value='{*\$obj['".$field->name."']*}'/>";
					}
					
				}else{
					
					$typeStr="<select id='".$field->name."' name='".$field->name."' table='".$this->m_data['table']."' style='width:200px' ></select>";
					
				}
				
				$tmpLine.=$field->comment.':</td><td>'.$typeStr.'</td>';
				$tmpLine.='</tr>'.chr(0x0A);;
			}
				
			$line=$tmpLine;
		}
		if(strstr($line,"%SAVE_PARAMS%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				if($field->name=="cdate"){
					continue;
				}
				if($field->name=="id" && $field->type=='bigint'){
					continue;
				}
				$tmpLine.=$field->name.':input_'.$field->name;
				if($i<$length-1) {
					$tmpLine=$tmpLine.','.'';
				}
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%INPUT_PARAMS%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				if($field->name=="cdate"){
					continue;
				}else if($field->name=="id" && $field->type=='bigint'){
					continue;
				}else if($field->type=="timestamp"){
					$tmpLine.='var input_'.$field->name.'= $("input[name='.$field->name.']").val();';
				}else{
					$tmpLine.='var input_'.$field->name.'= $("#'.$field->name.'").val();';
				}
				
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%POST_PARAMS%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				if($field->name=="cdate"){
					continue;
				}
				if($field->name=="id" && $field->type=='bigint'){
					continue;
				}
				$tmpLine.='if($this->input->post("'.$field->name.'")){';
				$tmpLine.='$data["'.$field->name.'"]=$this->input->post("'.$field->name.'");}';
				$tmpLine.=chr(0x0A);
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%GRID_INIT_ITEMS%")){ /// not yet used
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				$dictionary=$field->dictionary;
				if($dictionary==0){
                    continue;
				}else{
					$tmpLine.='loadDictionary("'.$field->name.'","'.$this->m_data['tableName'].'");';
					$tmpLine.=chr(0x0A);
				}
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%ADD_INIT_SELECT_ITEMS%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				$dictionary=$field->dictionary;
				if($dictionary==0){
					if($field->name=="id" || $field->name=="cdate"){
						continue;
					}
					if($field->type=="bigint"){
						$tmpLine.='list("'.$field->name.'");';
						$tmpLine.=chr(0x0A);
					}
				}else{
					$tmpLine.='dictionary("'.$field->name.'");';
					$tmpLine.=chr(0x0A);
				}
								
				
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%EDIT_INIT_SELECT_ITEMS%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				$dictionary=$field->dictionary;
				if($dictionary==0){
					if($field->name=="id" || $field->name=="cdate"){
						continue;
					}
					if($field->type=="bigint"){
						$setline="\$('#".$field->name."').attr('value','{*\$obj[\"".$field->name."\"]*}');";
						$tmpLine.='list("'.$field->name.'",function(){'
								.chr(0x0A).$setline.'});';
						$tmpLine.=chr(0x0A);
					}
				}else{
					$setline="\$('#".$field->name."').attr('value','{*\$obj[\"".$field->name."\"]*}');";
					$tmpLine.='dictionary("'.$field->name.'",function(){'.chr(0x0A).$setline.'});';
					$tmpLine.=chr(0x0A);
				}
				
		
			}
			$line=$tmpLine;
		}
		if(strstr($line,"%DETAIL_INFO%")){
			$tmpLine='';
			$length=count($this->m_data['fields']);
			$tdnum=0;
			for($i=0;$i<$length;$i++){
				$field=$this->m_data['fields'][$i];
				if($field->name=="id" || $field->name=="cdate"){
					continue;
				}
				if($tdnum%2==0){
					$tmpLine.='<tr>';
				}
				$tmpLine.='<td class="label">';
				$tmpLine.=$field->comment.':';
				$tmpLine.='</td><td>{*$obj["'.$field->name.'"]*}</td>';
				if($field->type=="text" || $field->type=="tinytext"){
					$tdnum=0;
				}else{
					$tdnum++;
				}
				if($tdnum%2==0){
					$tmpLine.='</tr>'.chr(0x0A);
				}
			}
			$line=$tmpLine;
		}
		//echo urlencode(json_encode($value));
		return $line;
	}
}