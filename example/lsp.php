<?php
class LSP {

	protected $cURL;
	protected $Token;
	protected $Fingerprint;
	protected $Server;
	protected $App;
	protected $License;
	protected $Hash;
	protected $IP;
	protected $connection;
	protected $query;
	protected $database;
  protected $query_closed = TRUE;
  protected $Branch = 'master';
	public $Status = FALSE;
	public $Update = FALSE;

	public function __construct($server,$app,$license,$hash){
		$this->Server = $server;
		$this->App = $app;
		$this->License = md5($license);
		$this->Hash = $hash;
		$this->IP = $this->get_client_ip();
		shell_exec("git fetch origin ".$this->Branch." 2>/dev/null");
		if(strpos(shell_exec("git status -sb 2>/dev/null"), 'behind') !== false){
			$this->Update = TRUE;
		}
		$this->Fingerprint = md5($_SERVER['SERVER_NAME'].$_SERVER['SERVER_SOFTWARE'].$_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_FILENAME'].$_SERVER['GATEWAY_INTERFACE'].$_SERVER['PATH']);
		$this->validate();
		if(!$this->Status){
			$this->activate();
		}
	}

	public function setBranch($branch = "master"){
		$this->Branch = $branch;
	}

	protected function get_client_ip() {
	  $ipaddress = '';
	  if(getenv('HTTP_CLIENT_IP')){
	    $ipaddress = getenv('HTTP_CLIENT_IP');
	  } elseif(getenv('HTTP_X_FORWARDED_FOR')){
	    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	  } elseif(getenv('HTTP_X_FORWARDED')){
	    $ipaddress = getenv('HTTP_X_FORWARDED');
	  } elseif(getenv('HTTP_FORWARDED_FOR')){
	    $ipaddress = getenv('HTTP_FORWARDED_FOR');
	  } elseif(getenv('HTTP_FORWARDED')){
	    $ipaddress = getenv('HTTP_FORWARDED');
	  } elseif(getenv('REMOTE_ADDR')){
	    $ipaddress = getenv('REMOTE_ADDR');
	  } else {
	    $ipaddress = 'UNKNOWN';
		}
	  return $ipaddress;
	}

	protected function validate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'api.php');
			curl_setopt($this->cURL, CURLOPT_POST, 1);
			curl_setopt($this->cURL, CURLOPT_POSTFIELDS, "app=".$this->App."&license=".$this->License."&fingerprint=".$this->Fingerprint."&ip=".$this->IP."&request=validate");
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$answer = json_decode(curl_exec($this->cURL));
			$this->Token = $answer['token'];
			curl_close($this->cURL);
			if(($this->Token.$this->Hash != '')&&(password_verify($this->Token, $this->Hash))){
				$this->Status = TRUE;
			}
		}
	}

	protected function activate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'api.php');
			curl_setopt($this->cURL, CURLOPT_POST, 1);
			curl_setopt($this->cURL, CURLOPT_POSTFIELDS, "app=".$this->App."&license=".$this->License."&fingerprint=".$this->Fingerprint."&ip=".$this->IP."&request=activate");
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$answer = json_decode(curl_exec($this->cURL));
			$this->Token = $answer['token'];
			curl_close($this->cURL);
			if(($this->Token.$this->Hash != '')&&(password_verify($this->Token, $this->Hash))){
				$this->Status = TRUE;
			}
		}
	}

	public function configdb($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8') {
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			$this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
		$this->database = $dbname;
	}

	protected function query($query) {
    if (!$this->query_closed) {
      $this->query->close();
    }
		if ($this->query = $this->connection->prepare($query)) {
      if (func_num_args() > 1) {
        $x = func_get_args();
        $args = array_slice($x, 1);
				$types = '';
        $args_ref = array();
        foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
          	$types .= $this->_gettype($args[$k]);
            $args_ref[] = &$arg;
					}
        }
				array_unshift($args_ref, $types);
        call_user_func_array(array($this->query, 'bind_param'), $args_ref);
      }
      $this->query->execute();
     	if ($this->query->errno) {
				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
     	}
      $this->query_closed = FALSE;
    } else {
      $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
  	}
		return $this;
  }

  protected function fetchAll($callback = null) {
    $params = array();
    $row = array();
    $meta = $this->query->result_metadata();
    while ($field = $meta->fetch_field()) {
      $params[] = &$row[$field->name];
    }
    call_user_func_array(array($this->query, 'bind_result'), $params);
    $result = array();
    while ($this->query->fetch()) {
      $r = array();
      foreach ($row as $key => $val) {
        $r[$key] = $val;
      }
      if ($callback != null && is_callable($callback)) {
        $value = call_user_func($callback, $r);
        if ($value == 'break') break;
      } else {
        $result[] = $r;
      }
    }
    $this->query->close();
    $this->query_closed = TRUE;
		return $result;
	}

	protected function close() {
		return $this->connection->close();
	}

	protected function _gettype($var) {
    if (is_string($var)) return 's';
    if (is_float($var)) return 'd';
    if (is_int($var)) return 'i';
    return 'b';
	}

  protected function lastInsertID() {
  	return $this->connection->insert_id;
  }

	protected function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

  protected function getTables($database){
    $tables = $this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?', $database)->fetchAll();
    $results = [];
    foreach($tables as $table){
			if(!in_array($table['TABLE_NAME'],$results)){
      	array_push($results,$table['TABLE_NAME']);
			}
    }
    return $results;
  }

	protected function getHeaders($table){
    $headers = $this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?', $table,$this->database)->fetchAll();
    $results = [];
    foreach($headers as $header){
      array_push($results,$header['COLUMN_NAME']);
    }
    return $results;
  }

  protected function create($fields, $table, $new = FALSE){
		if($new){
			$this->query('INSERT INTO '.$table.' (created,modified) VALUES (?,?)', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
			$fields['id'] = $this->lastInsertID();
		} else {
			$this->query('INSERT INTO '.$table.' (id,created,modified) VALUES (?,?,?)', $fields['id'],date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
		}
		$headers = $this->getHeaders($table);
    foreach($fields as $key => $val){
      if((in_array($key,$headers))&&($key != 'id')){
        $this->query('UPDATE '.$table.' SET `'.$key.'` = ? WHERE id = ?',$val,$fields['id']);
				set_time_limit(20);
      }
    }
    return $fields['id'];
  }

  protected function save($fields, $table){
		$id = $fields['id'];
		$headers = $this->getHeaders($table);
		foreach($fields as $key => $val){
			if((in_array($key,$headers))&&($key != 'id')){
				$this->query('UPDATE '.$table.' SET `'.$key.'` = ? WHERE id = ?',$val,$id);
				set_time_limit(20);
			}
		}
		$this->query('UPDATE '.$table.' SET `modified` = ? WHERE id = ?',date("Y-m-d H:i:s"),$id);
  }

	public function chgBranch($branch = 'master'){
		if($this->Status){
			$this->Branch = $branch;
			shell_exec("git fetch origin ".$this->Branch." 2>/dev/null");
			if(strpos(shell_exec("git status -sb 2>/dev/null"), 'behind') !== false){
				$this->Update = TRUE;
			}
		}
	}

	public function updateFiles(){
		if($this->Status){
			if($this->Update){
				shell_exec("git stash 2>/dev/null");
				shell_exec("git reset --hard origin/".$this->Branch." 2>/dev/null");
				shell_exec("git pull origin ".$this->Branch." 2>/dev/null");
			}
		}
	}

	public function createStruture($file){
		if($this->Status){
			foreach($this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?',$this->database)->fetchAll() as $fields){
				$structures[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['order'] = $fields['ORDINAL_POSITION'];
				$structures[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['type'] = $fields['COLUMN_TYPE'];
				$structures[$fields['TABLE_NAME']][$fields['ORDINAL_POSITION']] = $fields['COLUMN_NAME'];
			}
			$json = fopen($file, 'w');
			fwrite($json, json_encode($structures, JSON_PRETTY_PRINT));
			fclose($json);
		}
	}

	public function updateStructure($json){
		if($this->Status){
			$structures = json_decode(file_get_contents($json),true);
			foreach($this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?',$this->database)->fetchAll() as $fields){
				$db[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['order'] = $fields['ORDINAL_POSITION'];
				$db[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['type'] = $fields['COLUMN_TYPE'];
				$db[$fields['TABLE_NAME']][$fields['ORDINAL_POSITION']] = $fields['COLUMN_NAME'];
			}
			foreach($structures as $table_name => $table){
				if(isset($db[$table_name])){
					foreach($table as $column_name => $column){
						if(!is_int($column_name)){
							if(isset($db[$table_name][$column_name])){
								if($db[$table_name][$column_name]['type'] != $structures[$table_name][$column_name]['type']){
									$this->query('ALTER TABLE `'.$table_name.'` MODIFY `'.$column_name.'` '.$structures[$table_name][$column_name]['type']);
								}
								if($db[$table_name][$column_name]['order'] != $structures[$table_name][$column_name]['order']){
									$this->query('ALTER TABLE `'.$table_name.'` MODIFY COLUMN `'.$column_name.'` '.$structures[$table_name][$column_name]['type'].' AFTER `'.$structures[$table_name][$structures[$table_name][$column_name]['order']-1].'`');
								}
							} else {
								$this->query('ALTER TABLE `'.$table_name.'` ADD `'.$column_name.'` '.$structures[$table_name][$column_name]['type'].' AFTER `'.$structures[$table_name][$structures[$table_name][$column_name]['order']-1].'`');
							}
							set_time_limit(20);
						}
					}
				} else {
					$this->query('CREATE TABLE `'.$table_name.'` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id))');
					set_time_limit(20);
					foreach($structures[$table_name] as $col_order => $col){
						if((is_int($col_order))&&($col) != 'id'){
							$this->query('ALTER TABLE `'.$table_name.'` ADD `'.$col.'` '.$structures[$table_name][$col]['type']);
							set_time_limit(20);
						}
					}
				}
			}
		}
	}

	public function createRecords($file, $tables = []){
		if($this->Status){
			if(empty($tables)){ $tables = $this->getTables($this->database); }
			foreach($tables as $table){
				$records[$table] = $this->query('SELECT * FROM '.$table)->fetchAll();
			}
			$json = fopen($file, 'w');
			fwrite($json, json_encode($records, JSON_PRETTY_PRINT));
			fclose($json);
		}
	}

	public function insertRecords($file, $asNew = FALSE){
		if($this->Status){
			$tables=json_decode(file_get_contents($file),true);
			foreach($tables as $table => $records){
				foreach($records as $record){
					unset($record['created']);
					unset($record['modified']);
					if(!$asNew){
						if($this->query('SELECT * FROM '.$table.' WHERE id = ?', $record['id'])->numRows() < 1){
							$this->create($record, $table);
						} else {
							$this->save($record, $table);
						}
					} else {
						$this->create($record, $table, $asNew);
					}
				}
			}
		}
	}
}
