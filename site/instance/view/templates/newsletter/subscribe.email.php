<?
$url = 'http://'.$_SERVER['HTTP_HOST'].'/newsletter/subscribe?id='.$page->id.'&code='.$page->tool->code;
$unSubUrl = 'http://'.$_SERVER['HTTP_HOST'].'/newsletter/unsubscribe?id='.$page->id.'&code='.md5($page->item['time_created']);
?>
<h2>Newsletter Subscription</h2>

<div>
	You have signed up at <?=$_SERVER['HTTP_HOST']?>.  Please verify this email address by going here:<br/>
	<a href="<?=$url?>"><?=$url?></a>
	<br/>
	<br/>
	If you did not request a subscription, go here:<br/>
	<a href="<?=$unSubUrl?>"><?=$unSubUrl?></a>
	<br/>
</div>