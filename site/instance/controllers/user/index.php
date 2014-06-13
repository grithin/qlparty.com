<?
User::required();

$page->title = 'User Home';

View::show('@standardFullPage,,user/index');
