<?
$page->title = 'Messages';
if(i()->CRUDController()->delete()->return){
	Page::success('Candidate form(s) deleted');
	Page::saveMessages();
	Http::redirect('/');
}

if(User::hasPrivilege('admin')){
	$page->tool->append('actor','show');
	$page->tool->readSortPage();
}else{
	#$page->tool->readSortPage(array('actor_id'=>User::actorId()));
	$page->tool->readSortPage('(actor_id__to = '.User::actorId().' or actor_id__from = '.User::actorId());
}
$page->templateOptions = array('create'=>false,'delete'=>false,'description'=>'Messages are deleted automatically after 30 days');
View::end('@pageManage');