<?
getId();
$volunteerPageTool = new Model;
$volunteerPageTool->id = $page->id;
$volunteerPageTool->readCheck();
Page::$in['_id'] = $volunteerPageTool->id;
$page->title = 'Email Volunteer: '.$volunteerPageTool->item['name_first'].' '.$volunteerPageTool->item['name_last'];
if($volunteerPageTool->item['actor_id'] != User::actorId() && !User::hasPrivilege('admin')){
	if(!$volunteerPageTool->item['is_public']){
		User::notAuthorized('Form is not public');
	}
}

//don't want form prefilled with volunteer email
$page->tool->volunteer = $volunteerPageTool->item;
unset($page->item);

if(i()->CRUDController()->create()->return){
	Page::success('Email Sent');
	Page::saveMessages();
	Http::redirect('read/'.$volunteerPageTool->id);
}

View::end('@pageCreate');