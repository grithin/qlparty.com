<?
$page->title = 'Update Candidate Form';
getId();
$page->tool->readCheck(CRUDPage::UPDATE);
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	die('Not authorized');
}
if(i()->CRUDController()->update()->return){
	Page::success('Candidate form updated');
	Page::saveMessages();
	Http::redirect('read/'.$page->id);
}
View::end('@pageUpdate');