<?
class CRUDModel{
	function __construct($pageTool,$db=null){
		$this->PageTool = $pageTool;
		$this->db = $db ? $db : Db::$primary;
	}
	function columns($table){
		if(!$this->columns[$table]){
			$this->columns[$table] = Db::columnsInfo($table);
		}
		return $this->columns[$table];
	}
	//determine various filters and validators based on database columns
	function handleColumns(){
		$columns = self::columns($this->PageTool->model['table']);
		$usedColumns = $this->PageTool->model['columns'] ? $this->PageTool->model['columns'] : array_keys($columns);
		
		//create validation and deal with special columns
		foreach($usedColumns as $column){
			//special columns
			if($column == 'time_created'){
				Page::$in[$column] = i()->Time('now',Config::$x['timezone'])->datetime();
			}elseif($column == 'time_updated'){
				Page::$in[$column] = i()->Time('now',Config::$x['timezone'])->datetime();
			}elseif($column == 'id'){
				$validaters[$column][] = 'f:toString';
				$validaters[$column][] = '?!v:filled';
				$validaters[$column][] = '!v:existsInTable|'.$this->PageTool->model['table'];
			}else{
				$validaters[$column][] = 'f:toString';
				if(!$columns[$column]['nullable']){
					//column must be present
					$validaters[$column][] = '!v:exists';
				}else{
					//column may not be present.  Only validate if present
					$validaters[$column][] = '?!v:filled';
				}
				switch($columns[$column]['type']){
					case 'datetime':
						$validaters[$column][] = '!v:date';
						$validaters[$column][] = 'f:toDatetime';
					break;
					case 'date':
						$validaters[$column][] = '!v:date';
						$validaters[$column][] = 'f:toDatetime';
					break;
					case 'text':
						if($columns[$column]['limit']){
							$validaters[$column][] = '!v:lengthRange|0,'.$columns[$column]['limit'][0];
						}
					break;
					case 'int':
						$validaters[$column][] = 'f:trim';
						$validaters[$column][] = '!v:isInteger';
					break;
					case 'decimal':
					case 'float':
						$validaters[$column][] = 'f:trim';
						$validaters[$column][] = '!v:isFloat';
					break;
				}
			}
		}
		$this->usedColumns = $usedColumns;
		$this->validaters = $validaters;
	}
	
	function validate(){
		$this->handleColumns();
		if(method_exists($this->PageTool,'validate')){
			$this->PageTool->validate();
		}
		//CRUD standard validaters come after due to them being just the requisite validaters for entering db; input might be changed to fit requisite by PageTool validaters.
		if($this->validaters){
			Page::filterAndValidate($this->validaters);
		}
		return !Page::errors();
	}
	
	//only run db changer functions if $this->PageTool->model['table'] available
	function create(){
		if($this->validate()){
			$this->insert = Arrays::extract($this->usedColumns,Page::$in,$x=null,false);
			unset($this->insert['id']);
			$this->PageTool->id = $id = Db::insert($this->PageTool->model['table'],$this->insert);
			return $id;
		}
	}
	function update(){
		if($this->validate()){
			$this->update = Arrays::extract($this->usedColumns,Page::$in,$x=null,false);
			unset($this->update['id']);
			Db::update($this->PageTool->model['table'],$this->update,$this->PageTool->id);
			return true;
		}
	}
	///standardized to return id
	function delete(){
		if(Db::delete($this->PageTool->model['table'],$this->PageTool->id)){
			return $this->PageTool->id;
		}
	}
	function read(){
		if(Page::$data->item = Db::row($this->PageTool->model['table'],$this->PageTool->id)){
			return true;
		}
		if(Config::$x['CRUDbadIdCallback']){
			call_user_func(Config::$x['CRUDbadIdCallback']);
		}
		
	}
}
