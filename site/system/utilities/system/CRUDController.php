<?
///Create Read Update Delete general class

/**
Static b/c there appears little reason to have more than one on a single page
*/
class CRUDController{
	static function getId(){
		$id = abs(Page::$in['id']);
		if(!$id){
			$tokens = RequestHandler::$urlTokens;
			krsort($tokens);
			foreach($tokens as $token){
				$id = abs($token);
				if($id){
					break;
				}
			}
		}
		if($id){
			Page::$data->id = $id;
			Page::$data->tool->id = $id;
			return $id;
		}
	}
	function __construct($pageTool=null){
		$this->PageTool = $pageTool ? $pageTool : Page::$data->tool;
	}
	function __call($fn,$args){
		if(in_array($fn,array('create','update','delete','read'))){
			return $this->handle(array($fn),$args[0]);
		}
	}
	/**
	@param	commands	list of commands to look for in input for running (will only run one, order by priority)
	@param	default	the command to use if none of the provided were found.  Will be run regardless of whether corersponding input command found
	*/
	function handle($commands=array(),$default='read'){
		$commands = Arrays::stringArray($commands);
		
		$this->attempted = $thiis->called = array();
		foreach($commands as $command){
			if(Page::$in['_cmd_'.$command]){
				$return = $this->callFunction($command);
				if($return === null || $return === false){
					continue;
				}
				return new CRUDResult($command,$return,Page::$in['_cmd_'.$command],array('controller'=>$this));
			}
		}
		if($default && !in_array($default,$this->attempted)){
			$return = $this->callFunction($default,Page::$in['_cmd_'.$command]);
			return new CRUDResult($default,$return,null,array('controller'=>$this));
		}
		return new CRUDResult('',null);
	}
	function getFunction($command,$subcommand=null){
		if(!$subcommand){
			$subcommand = Page::$in['_cmd_'.$command];
		}
		if(method_exists($this->PageTool,$command.'_'.$subcommand)){
			return array($this->PageTool,$command.'_'.$subcommand);
		}elseif(method_exists($this->PageTool,$command)){
			return array($this->PageTool,$command);
		}elseif(isset($this->PageTool->model) && $this->PageTool->model['table'] && method_exists('CRUDModel',$command)){
			return array('CRUDModel',$command);
		}
		return false;
	}
	//callbacks applied at base for antibot behavior
	function callFunction($command,$subcommand=null,$error=false){
		$this->attempted[] = $command;
		$function = $this->getFunction($command);
		if($function){
			$this->called[] = $command;
			$return = call_user_func($function);
			return $return;
		}
		if($error){
			Page::error('Unsupported command');
		}
	}
}
/*Note, the handling of a result in a standard way would potentially require standard action names, item titles, directory structure, id parameters, etc.  So, just make it easy to handle, don't actually handle*/
class CRUDResult{
	function __construct($type,$return,$subType=null,$info=null){
		$this->type = $type;
		$this->return = $return;
		$this->subType = $subType;
		if($info){
			$this->attempted = $info['controller']->attempted;
			$this->called = $info['controller']->called;
		}
		if($type){
			$this->type = $return;
		}
	}
}