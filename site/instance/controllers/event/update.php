<?
getId();
$page->tool->readCheck(CRUDPage::UPDATE);
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	die('Not authorized');
}
if(i()->CRUDController()->update()->return){
	Page::success();
	Page::saveMessages();
	Http::redirect('read/'.$page->id);
}
ViewTool::datetimeIncludes();
View::end('@pageUpdate');