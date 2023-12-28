<?php

class Mysqlminang extends mysqli{
 	var $default_fetch_mode = 'object';
	var $last_query = null;
	var $last_result = null;
	
	protected $last_insert_id = null;
	
	protected $last_affected_rows = null;
	
	protected $statementmgr = null;
	
	function __construct($db_name=DB_NAME, $db_hostname=DB_HOSTNAME, $db_username=DB_USERNAME, $db_password=DB_PASSWORD){
	  if(!isset($db_hostname) || !isset($db_username) || !isset($db_password) || !isset($db_name)) return;
	  else{
		  parent::__construct($db_hostname, $db_username, $db_password, $db_name);
		}
	}

	function SetFetchMode($mode='assoc'){
	  $mode = strtolower($mode);
	  if($mode != 'assoc' && $mode != 'object') $mode = 'assoc';
	  $this->default_fetch_mode = $mode;
	}
	
	function affected_rows(){
	  return $this->last_affected_rows;		
	}

	function insert_id(){
	  return $this->last_insert_id;
	}

	
	function query($query, $replacements=null, $result_mode=MYSQLI_STORE_RESULT){
	  if((is_array($replacements) or is_object($replacements)) and !empty($replacements)){
	    return $this->prepared_query($query, $replacements);
	  }else{
		  $this->last_result = parent::query($query, $result_mode) or 
		  die("query error: ".$this->error."<br />Query: $query");
		  $this->last_query = $query;
		  $this->last_affected_rows = $this->affected_rows;
		  $this->last_insert_id = $this->insert_id;
		}
		return $this->last_result;
	}
	

	function prepared_query($query, $replacements=null){
	  if((!is_array($replacements) and !is_object($replacements)) or empty($replacements)) $replacements = null;
	 
	  $replacements = $this->preparse_prepared_query($query, $replacements);
	  
	  if($query != $this->last_query){ 
	  
	    $this->free_result();
		
	    unset($this->last_result, $this->statementmgr);
		
	    $this->last_result =$this->prepare($query) or die($this->errno);
		
	    $this->statementmgr = new MySQLiManager($this->last_result, $replacements);
	  }else{ 
	  	    $this->statementmgr->run_stmt($replacements);
	  }
	  $this->last_query = $query;
	  $this->last_affected_rows = $this->statementmgr->affected_rows();
 	  $this->last_insert_id = $this->statementmgr->insert_id();
	  return $this->statementmgr;
	}
	
	function preparse_prepared_query(&$query, $replacements){
	  if(!$replacements) return;
	  if(is_object($replacements)) $replacements = (array) $replacements;
	  $params = array();
	
	  $last_param_at = 0;
	
	  foreach($replacements as $k=>$v){
	    if(strpos($k,":") === false){
	      continue;
	    }else{
	    
	      while(true){
	      
	        $k_idx = strpos($query, $k);
	        if($k_idx === false) break; 
	        if(is_null($v) or $v == '') $v = 'NULL';
	        $params[$k_idx] = $v; 
	        $query = preg_replace('/'.preg_quote($k,'/').'/', '@', $query, 1); 
	      }
	    }
	  }
	 
	  foreach($replacements as $k=>$v){
	    if(strpos($k,":") !== false){
	      continue;
	    }else{
	     
	      $q_idx = strpos($query, '?');
	      if(is_null($v) or $v == '') $v = 'NULL';
	      $params[$q_idx] = $v; 
	      $query = preg_replace('/'.preg_quote('?','/').'/', '@', $query, 1); 
	    }
	  }
	
	  $query = str_replace('@','?',$query);
	
    ksort($params);
    return $params;
	}
	
	
	function free_result(){
	  if(isset($this->statementmgr)) $this->statementmgr->free_result();
	}

	function ListTables(){
		$result = $this->query('SHOW TABLES');
		while( $row = $result->fetch_row())
			$tables[] = $row[0];
		return $tables;
	}


	function ListFields($table){
		$q="SELECT * FROM `$table` LIMIT 1";
		$r = $this->FieldRow($q);
		return array_keys((array) $r);
	}

	
	function insert($table, $data=null){
		$fields='';
		$values='';
	  if(!$data) return false;
	  if(is_object($data)) $data = (array) $data;
	  $first=true;
		foreach($data as $fieldname=>$value){
			if(!$first){
				$fields.=', ';
				$values.=', ';
		  }
			$first=false;
			$hook = ":\\$fieldname/:";
			$fields.="`$fieldname`";
			$values.=$hook;
			$params[$hook] = $value;
		}
		if(!empty($fields) && !empty($values)){
		  $q="INSERT INTO `$table` ($fields) VALUES ($values)";
		  $result = $this->prepared_query($q, $params);
		}
		if($result){
			$id = $this->insert_id();
			unset($result);
			$this->free_result();
			if($id) return $id;
			else return true;
		}
		else return false;
	}

	private function array_list($data,$segementCombine,$segmentValue,$implodeVal=', ')
	{
		$a = array_keys($data);
		$b = $data;
		$c = array_combine($a, $b);
		foreach($c as $key=> $value){
			$result[]=$key.$segementCombine.$segmentValue.$value.$segmentValue;
		}
		$updatefields= implode ($implodeVal, $result);
		return $updatefields;
	}
	
	function update($table, $data=null, $where_clause, $replacements=null){
		$str2="";
		$updatefields= $this->array_list($data,"=","'");
		$Wherefields= $this->array_list($where_clause,"=","'");
		if(!empty($where_clause))
		{

			$where_clause="Where ".$Wherefields;
		}
		$QueryDump="update `$table` set ".$updatefields." ".$where_clause;		
		$this->prepared_query($QueryDump);
		
		$affected_rows = $this->affected_rows();
		$this->free_result();
		return $affected_rows;
	}
	

	public function delete($table, $whereQuery){
	  $Wherefields= $this->array_list($whereQuery,"=","'");
		if(!empty($whereQuery))
		{
			$whereQuery="Where ".$Wherefields;
		}
		$QueryDump="Delete from `$table` ".$whereQuery;
		
		$this->query($QueryDump);
		$this->free_result();
   		return $this->affected_rows();
	  	
	}
	
	
	function FieldOneRow($query=null, $replacements=null, $fetch_mode=null){
		if(!$fetch_mode) $fetch_mode = $this->default_fetch_mode;
		if($fetch_mode != 'object') $fetch_mode = 'assoc';
		$func = "fetch_$fetch_mode";
		if($query){
		  if($replacements)
		    $result = $this->prepared_query($query, $replacements);
		  else
		    $result = $this->query($query);
		  if(is_object($result)) $one = $result->$func();
		  else return $result;
		  unset($result);
		  $this->free_result();
	    return $one;
		}return false;
	}

  function FieldRow($query=null, $replacements=null, $mode=null){
    return $this->FieldOneRow($query, $replacements, $mode);
  }	

 
	function get($query=null, $replacements=null, $fetch_mode=null){
	  $rows = array();
	  if(!$fetch_mode) $fetch_mode = $this->default_fetch_mode;
		if($fetch_mode != 'object') $fetch_mode = 'assoc';
		$func = "fetch_$fetch_mode";
		if($query){
		  if($replacements){
		    $func.='s';
		    $result = $this->prepared_query($query, $replacements);
		    if(is_object($result)) $rows = $result->$func();
		    else return $result;
		  }else{
		    $result = $this->query($query);
		    if(is_object($result)){
			    while($row=$result->$func())
			      $rows[]=$row;
		    }else return $result;
		  }
	  }
	  $this->free_result();
		return $rows;
	}

 
	function ListColoums($colname, $query){
	  $tmp = array();
		if($rows = $this->get($query, null, 'assoc'))
			foreach($rows as $r)
				$tmp[] = $r[$colname];
		return $tmp;
	}
	
	
	function GetOneRow($query=null, $replacements=null){
	  return $this->FieldOneRow($query, $replacements, "object");
	}
	
	function GetAllRows($query=null, $replacements=null){
	  return $this->get($query, $replacements, "object");
	}
	
	
	function _escape_string($string){
		return $this->real_escape_string(addslashes($string));
	}
	
	
	function clean($string){
	  return $this->_escape_string($string);
	}
	
	
	function closeMySQLiManager(){
	  unset($this->statementmgr);
	  $this->statementmgr = null;
	}
	
	
	function UnsetMySQLi(){
	  @$this->free_result();
	  $this->closeMySQLiManager();
	  unset($this->last_result);
	  $this->last_result = null;
	}
	
	function __destruct(){
	  $this->UnsetMySQLi();
	  $this->close();	  
	}

	
	public function prep($query){
		$this->sql = $query;		
	}

	
	public function bind($hook, $value){
		$this->dbBind[$hook] = $this->_escape_string($value);
	}
	
	
	public function run(){
		if(empty($this->sql)) return false;
		if(is_array($this->dbBind)) 
    	foreach($this->dbBind as $hook=>$value)
      	$this->sql = str_replace($hook, "'$value'", $this->sql); 
    $this->query($this->sql); 
    $this->dbBind = array(); 
    $this->sql = "";
    return $this->affected();
	}
	
	public function affected(){
	  return $this->affected_rows();
  }

	
	public function db_insert_id(){
		return $this->insert_id();
	}
	
	public function iksquery($query){
	  return $this->query();
	}
	
	
  function Convert_Date_Time($phpdate=null){
	  if($phpdate) $phpdate = time();
	  return date( 'Y-m-d H:i:s', $phpdate );
  }

  
  function Convert_Unix_Time($mysqldate){
	  return strtotime($mysqldate);
  }

 
  function Pure_HTML($html, $textarea=0){
	 
	  if(!$textarea) $html = nl2br($html);
	
	  $html = html_entity_decode($html);
	  return $html;
  }
   
}

class MySQLiManager{
  var $stmt;
  protected $orig_query;
  protected $query;
  protected $params;
  protected $param_indexes;
  protected $typestring;
  protected $fieldnames;
  protected $assoc;
  
  function __construct(mysqli_stmt $stmt, $replace=null){
    $this->stmt = $stmt;
    $this->run_stmt($replace);
  }
  
 
  function run_stmt($replace=null){
    if($replace and (is_array($replace) or is_object($replace))){
      if(is_object($replace)) $replace = (array) $replace;
      $this->params = $replace;
      $this->bind_params();
    }
    $this->execute();
  }
  

  function bind_params(){
    array_unshift($this->params, $this->getPreparedTypeString($this->params));
    call_user_func_array(array($this->stmt, 'bind_param'), $this->refValues($this->params));
  }
  
 
  function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0){
      $refs = array();
      foreach($arr as $key => $value) $refs[$key] = &$arr[$key];
      return $refs;
    }
    return $arr;
  }

	
	function getPreparedTypeString($saParams){
    $sRetval = '';
   
    if ((!is_array($saParams) and !is_object($saParams)) or empty($saParams))
      return $sRetval;
    
  
    foreach ($saParams as $Param){
      if (is_int($Param) && $Param < 2147483647) $sRetval .= 'i';
      else if(is_double($Param) or is_float($Param) or (is_int($Param) and $Param >= 2147483647)) $sRetval .= 'd';
      else if (is_string($Param)) $sRetval .= 's';
      else $sRetval .= 'b';
    }
    $this->typestring = $sRetval;
    return $sRetval;
  }
  
  function execute(){
    $this->free_result();
    return $this->stmt->execute();
  }
  
  function store_result(){
    return $this->stmt->store_result();
  }
  
  function free_result(){
    return $this->stmt->free_result();
  }
  
  function result_metadata(){
    return $this->stmt->result_metadata();
  }
  
 
  function bind_result(){
    unset($this->fieldnames, $this->assoc, $arr, $fieldnames);
    $this->store_result(); 
    $meta = $this->result_metadata();
    if($meta){
      $count = 1; 
      while($field = $meta->fetch_field()){
        $fieldnames[$count] = &$arr[$field->name]; 
        $count++;
      }
      call_user_func_array(array($this->stmt, "bind_result"), $fieldnames);
    }else{
      $fieldnames = false;
      $arr = false;
    }
    $this->fieldnames =& $fieldnames;
    $this->assoc =& $arr;
    return $arr;
  }
  
  function fetch(){
    return $this->stmt->fetch();
  }
  
 
  function fetch_object(){
    $this->bind_result();
    $obj = $this->assoc;
    if(!$this->fetch()) return false;
    else return (object) $obj;
  }
 
  function fetch_objects(){
    $this->bind_result();
    $arr = $this->assoc;
    $copy = create_function('$a', 'return $a;');
    $results = array();
    while($this->fetch())
      $results[] = (object) array_map($copy, $arr);
    return $results;
  }
  
 
  function fetch_array(){
    $this->bind_result(); 
    $arr = $this->assoc;
    if(!$this->fetch()) return false;
    else return $arr;
  }
  
 
  function fetch_assoc(){
    return $this->fetch_array();
  }
  
 
  function fetch_arrays(){
    $this->bind_result();
    $arr = $this->assoc;
    $copy = create_function('$a', 'return $a;');
    $results = array();
    while($this->fetch()){
      $results[] = array_map($copy, $arr);
    }
    return $results;
  }
  
 
  function fetch_assocs(){
    return $this->fetch_arrays();
  }
  
 
  function affected_rows(){
    return $this->stmt->affected_rows;
  }
  
 
  function insert_id(){
    return $this->stmt->insert_id;
  }
 
  function __destruct(){
    @$this->stmt->free_result();
    $this->stmt->close();
  }
  
}

?>
