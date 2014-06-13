<?
getId();
$page->tool->readCheck();
if($_GET['join']){
	$page->tool->join();
	Page::success('Joined');
	Page::saveMessages();
	Http::redirect('/event/read/'.$page->id);
}elseif($_GET['unjoin']){
	$page->tool->unjoin();
	Page::success('Unjoined');
	Page::saveMessages();
	Http::redirect('/event/read/'.$page->id);
}

$page->title = 'Event: '.htmlspecialchars($page->item['title']);
View::end('@fullCurrent');