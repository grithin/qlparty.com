<?

#add instance wide js and css
View::$tagPrepend = true;
View::addCss('/resource/normalize.css','/resource/bootstrap/forms.css','main.css');

View::addTopJs('main.js');

if($_SESSION['js']){
	foreach($_SESSION['js'] as $js){
		View::addTopJs($js);
	}
}
if($_SESSION['css']){
	foreach($_SESSION['css'] as $css){
		View::addCss($css);
	}
}
if($_SESSION['json']){
	View::$json = Arrays::merge(View::$json,$_SESSION['json']);
}
if($userId = User::id()){
	View::$json['userId'] = $userId;
}


#general system js
$js = array();
foreach(array('general/jquery-1.10.2.min.js','system/tools.js','system/date.js','system/debug.js','system/ui.js') as $v){
	$js[] = '/'.Config::$x['urlSystemFileToken'].'/js/'.$v;
}
call_user_func_array(array('View','addTopJs'),$js);
View::addCss('/'.Config::$x['urlSystemFileToken'].'/css/main.css');
View::$tagPrepend = false;

$page->resourceModDate = '20110717.1';
View::$json['messages'] = Page::$messages;

$page->crumbs = $page->crumbs ? $page->crumbs : array();
array_unshift($page->crumbs,array('name'=>'Home','link'=>'/'));

if(!$page->headTitle){
	$page->headTitle = $page->title;
}


if(!$page->description){
	$page->description = 'A better, honest political party';
}
if(!$page->keywords){
	$page->keywords = 'political party, quality of life, guarantee honesty, no war, anti-war, depleted uranium, pro-energy, pro-us';
}