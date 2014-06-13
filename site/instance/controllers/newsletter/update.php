<?
User::requirePrivilege('create newsletter');
$page->title = 'Update Post';
getId();
$page->tool->readCheck(CRUDPage::UPDATE);
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	die('Not authorized');
}
if(i()->CRUDController()->update()->return){
	Page::success('Post updated');
	Page::saveMessages();
	Http::redirect('read/'.$page->id);
}
View::end('@pageUpdate');