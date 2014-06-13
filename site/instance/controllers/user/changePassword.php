<?
User::required();

$page->title = 'Change Password';

if(i()->CRUDController()->update()->return){
	Page::success('Password changed');
}

View::show('@fullCurrent');
