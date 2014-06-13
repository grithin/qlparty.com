<?
getId();
if($_GET['code']){
	if($page->tool->verify()){
		Page::success('Unsubscribed '.$page->item['email']);
		Page::saveMessages();
		Http::redirect('/');
	}
	Page::error('Could not verify with the information provided');
	View::end('@blank');
}