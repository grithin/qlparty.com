<?
getId();
$page->tool->readCheck();
$page->title = 'Candidate: '.$page->item['name_first'].' '.$page->item['name_last'];
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	$page->tool->append('-email','show');
}
View::end('@pageRead');