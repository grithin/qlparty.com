<?
$page->title = 'Manage Volunteer Forms';
if(i()->CRUDController()->delete()->return){
	Page::success('Volunteer form(s) deleted');
	Page::saveMessages();
	Http::redirect(0);
}

if(User::hasPrivilege('admin')){
	$page->tool->append('actor','show');
	$page->tool->readSortPage();
}else{
	$page->tool->readSortPage(array('actor_id'=>User::actorId()));
}
View::end('@pageManage');