<?
$page->title = 'Send Message';
if($id = i()->CRUDController()->create()->return){
	Page::success('Message Sent');
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
$page->tool->read(abs(Page::$in['actor_id__to']));
View::end('@pageCreate');