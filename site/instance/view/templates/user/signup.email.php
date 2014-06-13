<?
$url = 'http://'.$_SERVER['HTTP_HOST'].'/user/emailVerify?id='.$page->userId.'&code='.$page->code;
?>
<h2>User Signup</h2>

<div>
	Dear <?=$page->name?>,<br/>
	You have signed up at <?=$_SERVER['HTTP_HOST']?>.  Please verify this email address by going here:<br/>
	<a href="<?=$url?>"><?=$url?></a>
</div>