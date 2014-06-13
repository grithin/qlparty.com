<?
getId();
$page->tool->readCheck();
$page->title = 'Volunteer: '.$page->item['name_first'].' '.$page->item['name_last'];
if($page->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	if(!$page->item['is_public']){
		User::notAuthorized('Form is not public');
	}
	$page->tool->append('-email','show');
}
View::end('@fullCurrent');