<?
$page->title = 'Update User';
getId();
$page->tool->readCheck(CRUDPage::UPDATE);
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	die('Not authorized');
}
if(i()->CRUDController()->update()->return){
	Page::success('User Updated');
	Page::saveMessages();
	Http::redirect('read/'.$page->id);
}
View::end('@pageUpdate');