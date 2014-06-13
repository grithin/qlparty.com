<?
getId();
if(!User::hasPrivilege('admin')){
	$page->tool->append('-referrer','show');
}
$page->tool->readCheck();
if($page->item['user.id.id']){
	Http::redirect('/user/read/'.$page->item['user.id.id']);
}
$page->title = 'Visitor';
View::end('@pageRead');