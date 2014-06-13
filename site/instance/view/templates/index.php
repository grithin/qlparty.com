<style type="text/css">
	#topics{
		padding:20px;
		margin-top:20px;
	}
	.topic{
		font-size:14pt;
		font-weight:bold;
		font-family:verdana,helvetica,arial,sans-serif;
		padding-top:15px;
	}
	.detail{
		padding-top:9px;
		font-size:12pt;
		padding-left:15px;
	}
	.line{
		font-size:18pt;
		/*color:#1B85A5;*/
		color:#FF9100;
		color:#cc8000;
		color:#1B85A5;
		color:#0a7494;
		color:#096383;
		text-shadow: 1px 1px 0 rgba(221, 243, 250, 0.2);
		text-align:center;
	}
	#stayNotified{
		font-size:10pt;
		text-align:center;
	}
	input[type="text"]{
		margin-top:10px;
	}
	.pitch{
		padding-top:40px;
	}
</style>

<div class="pitch">
	<div class="line">
		<b>The idea is simple</b>
		<br/>
		Get local candidates to <u>guarantee</u>, through contract, their honesty
	</div>
	<div id="stayNotified">
		<br/>
		Stay updated on our search for honest candidates and QL Party news
		<br/>
		<form method="post" action="/newsletter/subscribe">
			<input type="hidden" name="_cmd_create" value="1"/>
			<?=Form::text('email',null,array('@placeholder'=>'Email','class'=>'input-large'))?>
			<?=Form::submit('Subscribe')?>
		</form>
	</div>
</div>

<p>
Why not get involved in local government?  If your local government doesn't reflect your values or exmplify good government, why expect anything from government at the national level?  If you want a better national government, you have to start from the ground up.
</p>