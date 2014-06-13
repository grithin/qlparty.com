<?
if($_SESSION['userId']){
	Http::redirect('/');
}

$page->title = 'login';
if(i()->CRUDController()->update()->return){
	$url = $_COOKIE['url'] ? $_COOKIE['url'] : '/user/';
	Cookie::remove('url');
	Http::redirect($url);
}

View::show('@fullCurrent');
