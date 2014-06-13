<?
///To standardize the normal use of CRUD
/**
Most simple sites have pages representing some basic CRUD operation around one primary table.

standard concerns
	write
		Defaults to all available columns
		All values but null (or empty string on url format) and true are used to write as the column value before pushing to CRUDModel for write operation
		To specify which fields, from the input or constructed, should be written to the the model.
		Although it is possible to mix write interests into the validate concern, these are different concerns and are not mixed:
			sometimes, a field requires no validation but should still be used for writing.  To mix these concerns would require the validate concern say something about the field for which no validation was required.
	read
		Which model parts to pull from the model into the page data.
	show
		Which model parts to show from the page data
	input
		How the shown model parts should be input by the user
		Can be:
			string: form method name
			array:
				full form: array('form'=>method,'options'=>)
				select array
			closure
	format
		How the model data should be formatted
	validate
		Upon input, how the model part input should be validated

Concerns: Given the two standard forms, it may be desired to use both at one crudLevel.  To do this, use one normal name, and one prefixed with '@':
		'show' => array('bob'=>function(){return 'bob';}),
		'@show'=> '-bill&-sue'
	Also, it may sometimes be desired to ignore the defaults, or to ignore the previous levels in a concern.  To do this, prefix with !:
		'!show' => 'moe&joe' // only shows moe and joe, unless so  later part adds something
	This ! can be used when it is easier to describe columns included than columns excluded

*/
class CRUDPage{
	const CREATE = 1;
	const READ = 2;
	const UPDATE = 4;
	const MANAGE = 8;
	const DELETE = 16;	
	public $lastConcern = 16;
	/**$crud concerns
		for arrays, key=>null value means to exclude, a missing key means to default, and key=>'' means to include or default
		for strings, url form, where keys starting with "-" are excluded
	*/
	
	function __construct(){
		//there is a chance these things will have been already created with another db instance, so check
		if(!$this->CRUDModel){
			$this->CRUDModel = new CRUDModel($this);
		}
		if(!$this->DbModel){
			$this->DbModel = DbModel::initialize($this->CRUDModel->db);
		}
		$columns = $this->CRUDModel->columns($this->model['table']);
		$keys = array_keys($columns);
		$this->defaultConcernData= array();
		foreach($keys as $key){
			$this->defaultConcernData[$key] = true;
		}
		krsort($this->crud);//reverse key sorted so specific rules outweight combined rules
		
		Hook::run('newCRUDPage',$this);
	}
	
	///Add a CRUD concern to the $this->crud var
	/**
	Occasionally, there are interested besides the normal CRUMD.  And, in this case, it may be desired to add that interested within
	the PageTool representing that interest
	*/
	function addConcern($concernData = null){
		$this->lastConcern = $concern = $this->lastConcern*2;
		if($concernData){
			$this->crud[$concern] = $concernData;
			krsort($this->crud);
		}
		return $concern;
	}
	
	///get multiple concerns
	function gets($concerns,$crudType=null){
		$crudType = $this->getCRUDType($crudType);
		$concerns = Arrays::stringArray($concerns);
		foreach($concerns as $concern){
			$concernsData[$concern] = $this->get($concern,$crudType);
		}
		return $concernsData;
	}
	
	
	///get a concern of the crud object
	/**
	@param	concern	the concern within one of the types within CRUMD
	*/
	function get($concern,$crudType=null,$exclusive=false){
		$crudType = $this->getCRUDType($crudType);
		
		if(!$exclusive && $this->concerns[$crudType][$concern]){
			return $this->concerns[$crudType][$concern];
		}
		//default values
		if(method_exists($this,'default_'.$concern)){
			$concernData = call_user_func(array($this,'default_'.$concern));
		}else{
			$concernData = $this->defaultConcernData;
		}
		
		//specified values
		foreach($this->crud as $type=>$config){
			if(($crudType & $type) == $crudType){//match type
				foreach(array('','@','!') as $prefix){//overloader "@" prefix
					$concernKey = $prefix.$concern;
					if($config[$concernKey]){//match concern
						if($prefix == '!'){
							$concernData = array();
						}
						if(method_exists($this,'combine_'.$concern)){
							$concernData = call_user_func(array($this,'combine_'.$concern),$config[$concernKey],$concernData);
						}else{
							$concernData = $this->combine_concern($config[$concernKey],$concernData);
						}
					}
				}
			}
		}
		if(!$exclusive){
			$this->concerns[$crudType][$concern] = $concernData;
		}
		return $concernData;
	}
	
//+	concern handling {
	function default_title(){
		foreach($this->defaultConcernData as $k=>$v){
			$titles[$k] = ucwords(preg_replace('@_@',' ',$k));
		}
		return $titles;
	}
	
	///so as to provide some special handling of database fields
	function default_input(){
		foreach($this->CRUDModel->columns[$this->model['table']] as $column => $info){
			switch($info['type']){
				case 'int':
					$inputs[$column] = array('form'=>'text','options'=>array($column));
				break;
				case 'datetime':
					$inputs[$column] = array('form'=>'text','options'=>array($column,null,array('class'=>'datetime')));
				break;
				case 'text':
					if(!$info['limit'] || $info['limit'] >= 1000){
						$inputs[$column] = array('form'=>'textarea','options'=>array($column));
					}else{
						$inputs[$column] = array('form'=>'text','options'=>array($column));
					}
					
				break;
			}
		}
		return $inputs;
	}
	function default_validate(){
		return array();
	}
	
	///input is a more comlpicated array, so this to handle
	function combine_input($new,$existing){
		$concernData = $this->combine_concern($new,$existing);
		foreach($concernData as $k=>&$v){
			if(is_array($v)){
				//assume array is select array
				if(!$v['form']){
					$v = array('form' => 'select', 'options' => array($k,$v));
				}
			}elseif(is_string($v)){
				$v = array('form'=>$v);
			}
		}
		return $concernData;
	}
	
	///standard way to combine concerns
	function combine_concern($new,$existing){
		if(is_string($new)){
			$new = Http::parseQuery($new);
			foreach($new as $k=>$v){
				if($k[0] == '-'){
					unset($existing[substr($k,1)],$new[$k]);
				}elseif(!$v){
					$new[$k] = true;///this disallows empty strings for value on write w/ url format, but enables use of url format concern on write
				}
			}
		}else{#only on array b/c url values are either present or not null
			foreach($new as $k=>$v){
				if($v === null){
					unset($existing[$k],$new[$k]);
				}
			}
		}
		//created array maintians overwritten arrays key placement, so first order, then overwrite
		$ordered = Arrays::merge($new,$existing);
		return Arrays::merge($ordered,$new);
	}
//+	}
	///apply formatting to all fields within item
	/**
	@param	formatOnlyFields	if you don't want to apply formatting to entire object, pass array of fields to format
	*/
	function formatItem($crudType=null,$formatOnlyFields=null){
		$crudType = $this->getCRUDType($crudType);
		$format = $this->get('format',$crudType);
		if($formatOnlyFields){
			foreach($formatOnlyFields as $field){
				$this->formattedItem[$field] = self::format($format[$field],$this->item[$field],$this->item);
			}
		}else{
			foreach($this->item as $k => $v){
				$this->formattedItem[$k] = self::format($format[$k],$v,$this->item);
			}
		}
		return $this->formattedItem;
	}
	///apply format config to value
	static function format($format,$value,$item=null){
		if($format !== null){
			if(is_array($format)){
				return $format[$value];
			}elseif(is_a($format,'closure')){
				return $format($value,$item);
			}else{
				return $value;
			}
		}
		return $value;
	}

	function create($handler=null){
		$this->prepareWrite(CRUDPage::CREATE);
		$handler = $handler ? $handler : array($this->CRUDModel,'create');
		return call_user_func($handler);
	}
	
	///pulls the validater and write concern and appies them.
	function update($handler=null){
		$this->prepareWrite(CRUDPage::UPDATE);
		$handler = $handler ? $handler : array($this->CRUDModel,'update');
		return call_user_func($handler);
	}
	function prepareWrite($concern){
		$fields = $this->gets(array('validate','write'),$concern);
		$this->validaters = $fields['validate'];
		foreach($fields['write'] as $k=>$v){
			if(is_a($v,'closure')){
				Page::$in[$k] = $v(Page::$in[$k]);
			}elseif($v !== true){
				Page::$in[$k] = $v;
			}
			$this->model['columns'][] = $k;
		}
	}
	
	///handles single or multiple delete
	function delete($handler=null){
		$handler = $handler ? $handler : array($this->CRUDModel,'delete');
		if(is_array(Page::$in['delete'])){
			foreach(Page::$in['delete'] as $delete){
				$this->id = $delete;
				$returns[] = call_user_func($handler);
			}
			return $returns;
		}else{
			$this->id = $delete;
			return call_user_func($handler);
		}
	}
	
	function readCheck($crudType=null){
		$crudType = $this->getCRUDType($crudType);
		$this->read($crudType);
		if($this->item){
			return true;
		}
		badId();
	}
	function read($crudType=null,$where=null,$order=null,$limit=null,$pageBy=50){
		$columns = array_keys($this->get('read',$crudType));
		if(!$where){
			$where = array('id' => $this->id);
		}
		$this->item = $this->data = $this->DbModel->row($this->model['table'],$columns,$where,$order,$limit);
	}
	function reads($crudType=null){
		$columns = array_keys($this->get('read',$crudType));
		$this->items = $this->DbModel->rows($this->model['table'],$columns,$where,$order,$limit);
	}
	function readSortPage($where=null,$defaultSort=null,$pageBy=50,$allowedSort=null){
		$columns = array_keys($this->get('read',self::MANAGE));
		if(!$allowedSort){
			$allowedSort = array_keys($this->get('show',self::MANAGE));
		}
		if(!$defaultSort){
			if(in_array('time_created',$allowedSort)){
				$defaultSort = '-time_created';
			}elseif(in_array('id',$allowedSort)){
				$defaultSort = '-id';
			}else{
				$defaultSort = $allowedSort[0];
			}
		}
		$sort = SortPage::sort($allowedSort,null,$defaultSort,false);
		Page::$data->sort = $sort['sort'];
		$sql = $this->DbModel->select($this->model['table'],$columns,$where,implode(', ',$sort['orders']));
		$result = SortPage::page($sql,$pageBy);
		Page::$data->paging = $result['info'];
		$this->items = $result['rows'];
	}
	function validate(){
		if($this->validaters){
			Page::filterAndValidate($this->validaters);
		}
	}
	
	///append to the $crud.  You can append a null, or a -key to remove
	function append($part,$concern,$crudType=null){
		$crudType = $this->getCRUDType($crudType);
		$concernData = &$this->crud[$crudType][$concern];
		if($concernData){
			$part = $this->standardizeConcernData($part);
			$concernData = $this->standardizeConcernData($concernData);
			$concernData = Arrays::merge($concernData,$part);
		}else{
			$concernData = $part;
		}
		return $concernData;
	}
	function standardizeConcernData($concernData){
		if(is_string($concernData)){
			$concernData = Http::parseQuery($concernData);
			$standard = array();
			foreach($concernData as $k=>$v){
				if($k[0] == '-'){
					$standard[substr($k,1)] = null;
				}else{
					$standard[$k] = $v;
				}
			}
			return $standard;
		}
		return $concernData;
	}
	///determine which CRUD type should be used
	function getCRUDType($crudType=null,$default = CRUDPage::READ){
		if(!is_int($crudType)){
			if(is_string($crudType)){
				$crudType = strtolower($crudType);
				$types = array('c'=>self::CREATE,'r'=>self::READ,'u'=>self::UPDATE,'m'=>self::MANAGE,'d'=>self::DELETE);
				$crudType = $types[$crudType[0]];
			}else{
				$page = strtolower(end(RequestHandler::$parsedUrlTokens));
				$types = array('create'=>self::CREATE,'read'=>self::READ,'update'=>self::UPDATE,'manage'=>self::MANAGE,'delete'=>self::DELETE);
				$crudType = $types[$page];
			}
		}
		if(!$crudType){
			$crudType = $default;
		}
		return $crudType;
	}
}