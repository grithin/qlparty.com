<?
if($_GET['code']){
	if($page->tool->verify()){
		Page::success('Email Verified');
		Page::saveMessages();
		Http::redirect('/');
	}
	Page::error('Could not verify with the information provided');
	View::end('@blank');
}elseif(i()->CRUDController()->create()->return){
	Page::success('Submission Successful');
	Page::notice('Please verify your email by clicking on the link provided in the email sent');
	View::end('@blank');
}else{
	View::end('@standard,,newsletter/subscribe');
}

