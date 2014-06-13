<?
//Start database connetion
global $defaultConnectionInfo;
$db = Db::initialize($defaultConnectionInfo);

//+	start cache connection{
$cache = Cache::initialize(
	array(
		array('localhost',11211)
	));
//+	}
//if script, skip the rest
if(Config::$x['inScript']){
	return true;
}

//+	special handling of class "model" to localize to page section specific model{
class PageModelAutoloader{
	static function load($className){
		if($className == 'Model'){
			global $page;
			//load the page section model
			$type = ucwords(Tool::toCamel(implode(' ',array_slice(RequestHandler::$parsedUrlTokens,0,-1))));
			if($type){
				class_alias(ucwords(Tool::toCamel(implode(' ',array_slice(RequestHandler::$parsedUrlTokens,0,-1)))).'Model','Model',true);
			}
		}
	}
}
spl_autoload_register(array('PageModelAutoloader','load'),true,true);
//+	}


Ban::clearBans();
//+	Ban handling {
if($_GET['_clearBan']){
	Ban::clearBans();
}

Ban::initialize();
//limit page loads 
//this is a full page (or ajax request, so limit frequence)
Ban::points('page load');

//limit creates
if(Page::$in['_cmd_create']){	
	Ban::points('form create');
}
//+	}

$dbModel = DbModel::initialize();
#if(is_file($dbModel->savePath)) unlink($dbModel->savePath);

//Start session
Session::$start = false;
Session::start();
if(!Session::$started){
	//get actor id, make session
	$actorId = Db::row('client_ip',array('ip'=>Http::getIp()),'actor_id');
	if(!$actorId){
		$actorId = Db::insert('actor',array(
				'display_name' => '',
				'time_created' => i()->Time(),
				'time_updated' => i()->Time()
			));
		//set display name to the id
		Db::update('actor',array('display_name'=>'Anonymous #'.$actorId),$actorId);
		
		Db::insert('client_ip',array(
				'actor_id' => $actorId,
				'ip' => Http::getIp(),
				'time_created' => i()->Time()
			));
	}
	Session::$other = array('actor_id' => $actorId);
	Session::create();
	$_SESSION['actorId'] = $actorId;
}

//get the "data" cookie used for general, single page (non continuous) data saves
new EncryptedCookie('data');

//+	various useful functions {
function error($message){
	Page::error($message);
	View::show('standardPage');
	exit;
}

function getId($type=null){
	$id = CRUDController::getId();
	if(!$id){
		error('No id provided');
	}
	View::$json['id'] = $id;
	if(!$type){
		$type = Arrays::at(RequestHandler::$urlTokens,-2);
		if(strtolower($type) == 'read'){
			$type = Arrays::at(RequestHandler::$urlTokens,-3);
		}
		$type = strtolower($type);
	}
	View::$json['id_type'] = $type;
	return $id;
}
function badId(){
	unset(View::$json['id'],View::$json['id_type']);
	unset(Page::$data->id);
	Page::$data->tool->id = null;
	error('Id not found');
}
//+	}