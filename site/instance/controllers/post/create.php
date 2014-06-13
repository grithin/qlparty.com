<?
$page->title = 'Create Post';
if($id = i()->CRUDController()->create()->return){
	Page::success('Post Created');
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
View::end('@pageCreate');