<?php
/**
* Shortcut method to load CRUD
* instance. Wraps ORM::load()
*/
function I($model) {
	return ORM::load($model);
}

/* DB types */
define("INT", 0);
define("STRING", 1);
define("PASSWORD", 2);
define("DATE", 3);
define("D", "ORM:D");

/**
* ORM class
*/
class ORM {
	/* PDO instance */
	public static $db = null;
	/* cache of models */
	private static $cache = array();
	/* cache of ReflectionClasses */
	private static $refl = array();
	/* last error from PDO */
	private static $error;
	/* if the Model should be inserted first */
	public $_updateFlag = false;
	
	/**
	* Query the Database using PDO
	*/
	static function query($sql, $vars=array()) {
		if(self::$db == null) {
			 self::$db = new PDO("mysql:host=localhost; dbname=rubarb","root","");
		}
		
		$statement = self::$db->prepare($sql);
		$statement->execute($vars);

		return $statement;
	}
	
	/**
	* Return the last inserted ID
	*/
	static function lastID() {
		return self::$db->lastInsertId();
	}
	
	/**
	* Return the number of rows of the last
	* query.
	*/
	static function numRows() {
		return query("SELECT FOUND_ROWS()")->fetchColumn();
	}
	
	/**
	* Return an error
	*/
	static function error($err=null) {
		if($err) {
			self::$error = $err;
		}
		
		if(!self::$db->errorCode()) {
			return false;
		}
		
		return self::$error;
	}
	
	static function fetchAll($q, $const=PDO::FETCH_ASSOC) {
		$data = array();
		while($row = $q->fetch($const)) {
			$data[] = $row;
		}
		
		return $data;
	}
	
	/**
	* Load the CRUD interface for models
	*/
	static function load($model) {
		//cache the instance
		if(!isset(self::$cache[$model])) {
			self::$cache[$model] = new CRUD($model);
		}
		
		return self::$cache[$model];
	}
	
	/**
	* Get static Parameters
	*/
	static function getParams($model) {
		if(!isset(self::$refl[$model])) {
			self::$refl[$model] = new ReflectionClass($model);
		}
		
		return self::$refl[$model]->getStaticProperties();
	}
	
	/**
	* Generate the SQL for updating the Model
	*/
	private function getUpdateSQL($params, $model) {
		//initial SQL
		$sql = "UPDATE " . $model['table'] . " SET";
		
		//update each parameter
		foreach($params as $key=>$value) {
			$sql .= " {$key} = :{$key},";
		}
		
		//remove the last comma
		$sql = substr($sql, 0, strlen($sql) - 1) . " WHERE";
		
		//for each primary key
		foreach($model['key'] as $k) {
			$sql .= " {$k} = :{$k} AND";
		}
		
		//take off the last AND
		return substr($sql, 0, strlen($sql) - 3);
	}
	
	/**
	* Generate the SQL for inserting the Model
	*/
	private function getInsertSQL($params, $model) {
		//initial SQL
		$sql = "INSERT INTO " . $model['table'] . "(";
		
		//enter the object properties
		foreach($params as $key=>$value) {
			$sql .= "{$key},";
		}
		
		//remove the last comma
		$sql = substr($sql, 0, strlen($sql) - 1) . ") VALUES(";
		
		//enter the object values
		foreach($params as $key=>$value) {
			$sql .= ":{$key},";
		}
		
		//take off the last AND
		return substr($sql, 0, strlen($sql) - 1) . ")";
	}
	
	private function getVars() {
		$vars = get_object_vars($this);
		foreach($vars as $var=>$val) {
			if(substr($var,0, 1) == "_") {
				unset($vars[$var]);
			}
		}
		
		return $vars;
	}
	
	/**
	* Update the ActiveRecord object
	*/
	public function update() {
		$params = $this->getVars();
		$model = self::getParams(get_class($this));
		
		//if update flag is true, update
		if($this->_updateFlag) {
			$sql = $this->getUpdateSQL($params, $model);
		} else {
			$sql = $this->getInsertSQL($params, $model);
			$this->_updateFlag = true;
		}
		
		self::query($sql, $params);
	}
	
	/**
	* Delete the Record
	*/
	public function remove() {
		$params = array();
		$model = self::getParams(get_class($this));
		
		$sql = "DELETE FROM " . $model['table'] . " WHERE ";
		
		//for each primary key
		foreach($model['key'] as $k) {
			$sql .= " {$k} = :{$k} AND";
			$params[$k] = $this->{$k};
		}
		
		//take off the last AND
		$sql = substr($sql, 0, strlen($sql) - 3);
		
		self::query($sql, $params);
	}
}

/**
* Class to get ActiveRecord instances
*/
class CRUD {
	/* model for CRUD */
	private $model;
	/* table linking to the model */
	private $table;
	/* array of primary keys */
	private $key;
	/* assoc array of attributes */
	private $attr;

	public function CRUD($model) {
		$this->model = $model;
		$r = ORM::getParams($model);
		$this->table = $r["table"];
		$this->key = $r["key"];
		$this->attr = $r["attr"];
	}

	/**
	* Get an active record instance
	*/
	public function get() {
		$sql = "SELECT * FROM " . $this->table . " WHERE";
		
		//for each primary key
		foreach($this->key as $k) {
			$sql .= " {$k} = ? AND";
		}
		
		$sql = substr($sql, 0, strlen($sql) - 3) . "LIMIT 1";
		
		$q = ORM::query($sql, func_get_args());
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if(!$data) {
			return false;
		}
		
		$ins = new $this->model();
		$ins->_updateFlag = true;
		
		foreach($data as $key=>$value) {
			$ins->{$key} = $value;
		}
		
		return $ins;
	}
	
	/**
	* Quick INSERT method
	*/
	public function create() {
		$sql = "INSERT INTO " . $this->table."(";
		$params = func_get_args();
		
		$i = 0;
		$plength = count($params);
		
		foreach($this->attr as $key=>$value) {
			$sql .= "{$key},";
			if($i >= $plength-1) break;
			$i++;
		}
		
		$sql = substr($sql, 0, strlen($sql) - 1) . ") VALUES(";
		
		for($i = 0; $i < $plength; $i++) {
			//if entering DEFAULT
			if($params[$i] == D) {
				$sql .= "DEFAULT,";
				array_splice($params, $i--, 1);
				$plength = count($params);
			} else {
				$sql .= "?,";
			}
		}
		
		$sql = substr($sql, 0, strlen($sql) - 1) . ")";
		
		$q = ORM::query($sql, $params);
		
		//if error
		if($q->errorCode() != "00000") {
			ORM::error($q->errorInfo());
			return false;
		}
		
		$id = ORM::lastID();
		
		//if one key and was generated, return instance
		if(count($this->key) == 1 && $id) {
			return $this->get($id);
		}
		
		return $this;
	}
	
	/**
	* Get Many results using an array for the WHERE clause
	* Returns and Array of instances
	*/
	public function getMany($where, $join="AND") {
		$sql = "SELECT * FROM " . $this->table . " WHERE";
		
		//for each where item
		foreach($where as $attr=>$val) {
			$sql .= " {$attr} = :{$attr} {$join}";
		}
		
		$sql = substr($sql, 0, strlen($sql) - strlen($join));
		$q = ORM::query($sql, $where);
		
		$inslist = array();
		
		//loop over the results
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			//create a model instance
			$ins = new $this->model();
			$ins->_updateFlag = true;
			
			//fill out the properties
			foreach($row as $key=>$value) {
				$ins->{$key} = $value;
			}
			
			//add to the list
			$inslist[] = $ins;
		}
		
		return new Results($inslist);
	}
	
	/**
	* Join tables together and return the results
	*/
	public function join($model, $where, $join="AND") {
		$sql = "SELECT * FROM " . $this->table;
		
		foreach($model as $from => $to) {
			$fd = explode('.', $from);
			$td = explode('.', $to);
			
			//if table not specified assume current
			if(count($fd) == 1) {
				$fd = array($this->table, $from);
			}
			
			$sql .= " INNER JOIN {$td[0]} ON {$fd[0]}.{$fd[1]} = {$to}";
		}
		
		$sql .= " WHERE";
		
		//for each where item
		foreach($where as $attr=>$val) {
			$fd = explode('.', $attr);
			
			//if table not specified assume current
			if(count($fd) == 1) {
				$fd = array($this->table, $attr);
			}
			
			$sql .= " {$fd[0]}.{$fd[1]} = :{$fd[1]} {$join}";
			
			//rename the parameters
			unset($where[$attr]);
			$where[$fd[1]] = $val;
		}
		
		$sql = substr($sql, 0, strlen($sql) - strlen($join));
		$q = ORM::query($sql, $where);
		$data = array();
		
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
		
		return new Results($data);
	}
}

/**
* Results of more than one Object
*/
class Results implements Iterator {
	private $data;
	private $i;
	
	public function Results($data) {
		$this->data = $data;
		$i = 0;
	}
	
	/**
	* Filter the attributes
	*/
	public function select($attr) {
		$attr = array_map('trim',explode(",",$attr));
		
		//loop over every record
		foreach($this->data as $i=>$row) {
			
			foreach($row as $key => $value) {
				if(!in_array($key, $attr)) {
					//remove the attribute if it hasn't been selected
					if(is_array($row)) {
						unset($this->data[$i][$key]);
					} else if(is_object($row)) {
						unset($this->data[$i]->{$key});
					}
				}
			}
		}
		
		return $this;
	}
	
	/**
	* Convert to JSON
	*/
	public function toJSON() {
		return json_encode($this->data);
	}
	
	/**
	* Return the data
	*/
	public function result() {
		return $this->data;
	}
	
	/**
	* Return the amount of data returned
	*/
	public function count() {
		return count($this->data);
	}
	
	/**
	* Iterator Interfaces
	*/
	public function rewind() {
		$this->i = 0;
	}
	
	public function key() {
		return $this->i;
	}
	
	public function valid() {
		return $this->i < count($this->data);
	}
	
	public function current() {
		return $this->data[$this->i];
	}
	
	public function next() {
		$this->i++;
	}
}
?>