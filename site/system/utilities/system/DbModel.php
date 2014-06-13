<?
/**
For clarity
	dt: domestic table
	dc: domestic column
	ft: foreign table
	fc: foreign column

Perhaps, at some point, make the model smaller by not shareLinking

*/
class DbModel{
	static $instances;
	///primary model
	static $primary;
	///primary model name
	static $primaryName;
	
	static function __callStatic($name,$arguments){
		return call_user_func_array(array(self::$primary,$name),$arguments);
	}
	function __call($method,$arguments){
		if(method_exists(__class__,$method)){
			return call_user_func_array(array($this,$method),$arguments);
		}elseif(method_exists($this->db,$method)){
			$sql = $this->select($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
			return call_user_func(array($this->db,$method),$sql);
		}
		Debug::throwError(__class__.' Method not found: '.$name);
	}
	private function  __construct($db,$savePath=null){
		$this->db = $db;
		$this->savePath = $savePath;
		if(!$this->savePath){
			$connectionInfo = $this->db->connectionInfo;
			$subName = $connectionInfo['database'] ? $connectionInfo['database'] : md5($connectionInfo['dsn']);
			$this->savePath = Config::$x['storageFolder'].'models/'.$this->db->name.'.'.$subName;
		}
	}
	///singleton
	static function initialize($db=null,$savePath=null){
		$db = $db ? $db : Db::$primary;
		if(self::$instances[$db->name]){
			return self::$instances[$db->name];
		}else{
			$class = __class__;
			self::$instances[$db->name] = new $class($db,$savePath);
			//set primary if no instances except this one
			if(count(self::$instances) == 1){
				self::setPrimary($db->name);
			}
			return self::$instances[$db->name];
		}
	}
	//sets primary to some named model object.  Can bypass and directly set $primary if object is not named
	static function setPrimary($name){
		self::$primaryName = $name;
		self::$primary = self::$instances[$name];//php already assigns objects by reference
	}
	///lazy loader
	function  __get($name){
		if($name == 'tables'){
			$this->loadModel();
			return $this->tables;
		}
	}
	///generate the array that represents the model and put it into static::$models
	private function model(){
		$tables = $this->db->tables();
		foreach($tables as $table){
			$this->modelTable($table);
		}
		$this->shareLinks();
	}
	private function modelTable($table){
		$db = $this->db;
		$mTable = &$this->tables[$table];
		
		$mTable['columns'] = $columns = $db->columnsInfo($table);
		$basePath = '/'.str_replace('_','/',$table).'/';
		$mTable['links'] = array();
		foreach($columns as $column=>$info){
			//check if forward relative reference
			$fowardRelative = false;
			if(substr($column,-1) == '_'){
				$fowardRelative = true;
			}
			
			//first, check for fc_ indicater
			if(preg_match('@^fc_@',$column)){
				preg_match('@(^_+)(.*)@',$column,$match);
				$parts = explode('__',substr($match[2],3));
				if($fowardRelative){
					$mTable['links'][] = array('ft'=>$table.'_'.$parts[0],'fc'=>$parts[1],'dc'=>$column);
				}else{
					$absoluteTable = self::getAbsolute($basePath,$part[0],$match[1]);
					$mTable['links'][] = array('ft'=>$absoluteTable,'fc'=>$part[1],'dc'=>$column);
				}
			}else{
				//+	Id Column referencing {
				if($column[0] != '_' && preg_match('@(.+)_id($|__)@',$column,$match)){//named column
					$mTable['links'][] = array('ft'=>$match[1],'fc'=>'id','dc'=>$column);
				}elseif(preg_match('@^(_+)id($|__)@',$column,$match)){//purely backwards relative + "id"
					$relativity = $match[1];
					$absoluteTable = self::getAbsolute($basePath,'',$relativity);
					$mTable['links'][] = array('ft'=>$absoluteTable,'fc'=>'id','dc'=>$column);
				}elseif(preg_match('@^(_+)(.*)?(_id($|__))@',$column,$match)){//relative id columns
					$relativity = $match[1];
					$relativeTable = $match[2];
					$absoluteTable = self::getAbsolute($basePath,$relativeTable,$relativity);
					$mTable['links'][] = array('ft'=>$absoluteTable,'fc'=>'id','dc'=>$column);
				}
				//+	}
			}
		}
		return $mTable;
	}
	///map domestic columns (dc) to foreign columns (fc) by using existing fc to dc map
	private function shareLinks(){
		$tables = $this->tables;///going to be modifying links, so make a copy
		foreach($tables as $name=>$table){
			foreach($table['links'] as $link){
				$this->tables[$link['ft']]['links'][] = array('ft'=>$name,'fc'=>$link['dc'],'dc'=>$link['fc']);
			}
		}
	}
	///get a non-conflicting alias for the table
	private function alias($tableName,&$acronyms){
		$acronym = Tool::acronym(tableName);
		$alias = $acronym.((int)$acronyms[$acronym]);
		$acronyms[$acronym]++;
		return $alias;
	}
	
	//basePath can be the fformatted basePath or a table name
	static function getAbsolute($basePath,$relativePath,$relativity=null){
		if($basePath[0] != '/'){
			$basePath = '/'.str_replace('_','/',$basePath).'/';
		}
		if(!$relativity){
			if(preg_match('@(^_+)(.*)@',$relativePath,$match)){
				$relativePath = $match[2];
				$relativity = $match[1];
			}
		}
		$relativePath = $basePath.str_replace('_','../',$relativity).$relativePath;
		//ensure path has ending "/"
		if(substr($relativePath,-1) != '/'){
			$relativePath .= '/';
		}
		$absoluteTable = str_replace('/','_',substr(Tool::absolutePath($relativePath),1,-1));
		return $absoluteTable;
	}
	///if model is previously constructed into file, load it, otherwise, construct it
	private function loadModel(){
		if(is_file($this->savePath)){
			require_once($this->savePath);
			$this->tables = $tables;
		}else{
			$this->model();
			$file = "<?\n".'$tables = '.var_export($this->tables,1).';';
			Files::write($this->savePath,$file);
		}
	}
	/**
	@param	$columns	in one of three forms
		dc
		ft.fc
		ft.dc.fc
			since, on occasion, a table may be doubly referenced, it is necessary to specify which refernce column to use on a join
	*/
	/** Requirements
		//General design
			tableIdentity.column aliasColumnName, ...
			from table tableIdentity
				left join table tableIdentity on tableIdentity.referenceColumn = tableIdentity.referencedColumn
			where tableIdentity.column = x
			order by tableIdentity.column
		// functionality
			generate tableIdentity
				acronym + number
			swap aliasColumnName with tableIdentity.column on where, order
		*/
	/**
	@param	where	array as passed to Db::where.  Be careful on text where, identifiers are search from within whole text
	*/
	private function select($table,$columns,$where=array(),$order=null,$limit=null){
		$mTable = $this->tables[$table];
		$links = array();
		//since the same table may be joined more than once, need alias each time
		$acronyms = array();
		
		foreach($columns as $column){
			$parts = explode('.',$column);
			if(count($parts) == 1){
				$columnIdentities[$column] = array(
						'quoted' => Db::quoteIdentity('t.'.$column),
						'unquoted' => 't.'.$column
					);
			}elseif(count($parts) >= 2){
				//+	get absolute referenced table path {
				if($parts[0][0] == '_'){//back relative table
					$referencedTable = self::getAbsolute($table,$parts[0]);
				}elseif(substr($column,-1) == '_'){//foward relative table
					$referencedTable = $table.'_'.$parts[0];
				}else{
					$referencedTable = $parts[0];
				}
				//+	}
				
				if(count($parts) == 3){
					$link = $this->findLink($table,$referencedTable,$parts[1]);
					$originalColumn = $parts[2];
				}else{
					$link = $this->findLink($table,$referencedTable);
					$originalColumn = $parts[1];
				}
				
				if(!$links[$link]){
					$links[$link] = $this->alias($mTable['links'][$link]['ft'],$acronyms);
				}
				
				$columnIdentities[$column] = array(
						'quoted' => Db::quoteIdentity($links[$link]).'.'.Db::quoteIdentity($originalColumn),
						'unquoted' => $links[$link].'.'.$originalColumn,
					);
			}
		}
		
		//+	handle where, select, and order {
		//need to replace aliases with identities.  So, attempt to identify and replace the aliases
		
		if($where){
			if(is_array($where)){
				foreach($where as $k=>$v){
					$newWhere = array('key'=>$k,'value'=>$v);
					foreach($columnIdentities as $alias => $identity){
						$pattern = '@(^|[^a-zA-Z0-9._])'.preg_quote($alias).'([^a-zA-Z0-9._]|$)@';
						if(preg_match($pattern,$k)){
							$newK = preg_replace($pattern,'$1'.$identity['unquoted'].'$2',$k);
							$newWhere = array('key'=>$newK,'value'=>$v);
							break;
						}
					}
					$newWheres[$newWhere['key']] = $newWhere['value'];
				}
			}else{
				foreach($columnIdentities as $alias => $identity){
					$pattern = '@(^|[^a-zA-Z0-9._])'.preg_quote($alias).'([^a-zA-Z0-9._]|$)@';
					$where = preg_replace($pattern,'$1'.$identity['unquoted'].'$2',$where);
				}
			}
			$where = Db::where($newWheres);
		}
		//create order and the select with aliases and identities
		foreach($columnIdentities as $alias => $identity){
			if($order){
				$order = preg_replace('@(^|[^a-zA-Z0-9.])'.preg_quote($alias).'([^a-zA-Z0-9.]|$)@','$1'.$identity['quoted'].'$2',$order);
			}
			//create select
			$select[] = $identity['quoted'].' '.Db::quote($alias);
		}
		$select = 'SELECT '.implode(', ',$select);
		if($order){
			$order = "\n ORDER BY ".$order;
		}
		//+ }
				
		//create from
		$from[] = "\nFROM ".Db::quoteIdentity($table).' t';
		foreach($links as $link=>$alias){
			$link = $mTable['links'][$link];
			$from[] = $link['ft'].' '.$alias.' on t.'.$link['dc'].' = '.$alias.'.'.$link['fc'];
		}
		$from = implode("\n\tLEFT JOIN ",$from);
		if($limit){
			$limit = "\n LIMIT ".$limit;
		}
		return $select.$from.$where.$order.$limit;
	}
	private function findLink($table,$referencedTable,$referenceColumn=null){
		#Debug::quit($table,$referencedTable,$referenceColumn,$this->tables[$table]['links']);
		foreach($this->tables[$table]['links'] as $k=>$v){
			if($v['ft'] == $referencedTable){
				if(!$referenceColumn || $referenceColumn == $v['dc']){
					return $k;
				}
			}
		}
		Debug::throwError('DbModel failed to find link',var_export(func_get_args(),1));
	}
	private function columnKey($column,$table,$columns,$where=array(),$order=null,$limit=null){
		$sql = $this->select($table,$columns,$where,$order,$limit);
		return $this->db->columnKey($sql);
	}
}