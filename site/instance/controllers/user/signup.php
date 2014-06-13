<?
$page->title = 'Sign Up';

if(i()->CRUDController()->create()->return){
	Page::success('Signup form submitted');
	Page::success('Email sent to provided address');
	Page::notice('Please confirm your email address using the link in the email we sent');
	View::end('standardPage');
}
View::show('@standardFullPage,,user/signup');
