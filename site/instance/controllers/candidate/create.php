<?
$page->title = 'Candidate Form';
if($id = i()->CRUDController()->create()->return){
	Page::success('Candidate form submitted');
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
View::end('@pageCreate');