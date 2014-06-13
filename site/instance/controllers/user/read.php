<?
getId();
$page->tool->readCheck();
$page->title = 'User: '.$page->item['display_name'];
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	$page->tool->append('-email&-password&-name_first&-name_last','show');
}
View::end('@pageRead');