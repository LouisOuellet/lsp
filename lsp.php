<?php
class LSP {

	private $cURL;
	private $Token;
	private $Fingerprint;
	private $Server;
	private $App;
	private $License;
	private $Hash;
	private $connection;
	private $query;
	private $database;
  private $show_errors = TRUE;
  private $query_closed = TRUE;
	public $Status = FALSE;
	public $Update = FALSE;

	public function __construct($server,$app,$license,$hash){
		$this->Server = $server;
		$this->App = $app;
		$this->License = md5($license);
		$this->Hash = $hash;
		if(strpos(shell_exec("git status -sb"), 'behind') !== false){
			$this->Update = TRUE;
		}
		$this->Fingerprint = md5($_SERVER['SERVER_ADDR'].$_SERVER['SERVER_NAME'].$_SERVER['SERVER_SOFTWARE'].$_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_FILENAME'].$_SERVER['GATEWAY_INTERFACE'].$_SERVER['PATH']);
		$this->validate();
		if(!$this->Status){
			$this->activate();
		}
	}

	public function validate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'?app='.$this->App.'&license='.$this->License.'&fingerprint='.$this->Fingerprint.'&action=validate');
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$this->Token = curl_exec($this->cURL);
			curl_close($this->cURL);
			if(($this->Token.$this->Hash != '')&&(password_verify($this->Token, $this->Hash))){
				$this->Status = TRUE;
			}
		}
	}

	public function activate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'?app='.$this->App.'&license='.$this->License.'&fingerprint='.$this->Fingerprint.'&action=activate');
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$this->Token = curl_exec($this->cURL);
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

	private function query($query) {
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

  private function fetchAll($callback = null) {
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

	private function close() {
		return $this->connection->close();
	}

	private function error($error) {
    if ($this->show_errors) {
      exit($error);
    }
  }

	private function _gettype($var) {
    if (is_string($var)) return 's';
    if (is_float($var)) return 'd';
    if (is_int($var)) return 'i';
    return 'b';
	}

	public function update($branch = "master"){
		if($this->Status){
			if($this->Update){
				shell_exec("git pull origin $branch");
			}
		}
	}

	public function create($file){
		if($this->Status){
			foreach($this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?',$this->database)->fetchAll() as $fields){
				$structures[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['order'] = $fields['ORDINAL_POSITION'];
				$structures[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['type'] = $fields['COLUMN_TYPE'];
				$structures[$fields['TABLE_NAME']][$fields['ORDINAL_POSITION']] = $fields['COLUMN_NAME'];
			}
			$json = fopen($file, 'w');
			fwrite($json, json_encode($structures));
			fclose($json);
		}
	}

	public function updatedb($json){
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
						}
					}
				} else {
					$this->query('CREATE TABLE `'.$table_name.'` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id))');
					foreach($structures[$table_name] as $col_order => $col){
						if((is_int($col_order))&&($col) != 'id'){
							$this->query('ALTER TABLE `'.$table_name.'` ADD `'.$col.'` '.$structures[$table_name][$col]['type']);
						}
					}
				}
			}
		}
	}
}
