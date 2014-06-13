<?
User::requirePrivilege('admin');

$page->title = 'Create User';
if($id = i()->CRUDController()->create()->return){
	Page::success('User Created');
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
View::end('@pageCreate');