<?
$page->title = 'Volunteer Form';
if($id = i()->CRUDController()->create()->return){
	Page::success('Volunteer form submitted');
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
View::end('@pageCreate');