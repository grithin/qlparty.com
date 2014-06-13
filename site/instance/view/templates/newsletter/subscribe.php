<form method="post" action="">
	<input type="hidden" name="_cmd_create" value="1"/>
	<?=Form::text('email',null,array('@placeholder'=>'Email','class'=>'input-large'))?>
	<?=Form::submit('Subscribe')?>
</form>