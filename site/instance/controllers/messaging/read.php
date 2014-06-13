<?
getId();
$page->tool->readCheck();
$page->title = 'Message: '.htmlspecialchars($page->item['title']);
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	$page->tool->append('-email','show');
}
View::end('@pageRead');