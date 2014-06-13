<?	View::addBottomJs('user/login.js');?>
<form action="/user/login" method="post" id="login">
	<input type="hidden" name="_cmd_update" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<?=FormStructure::text('Email','email')?>
			</tr>
			<tr>
				<?=FormStructure::password('Password','password')?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2">
					<?=Form::submit('Log in')?> |
					<?=Form::submit('Sign Up')?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
</div>
<span class="bottom"></span>


<script type="text/javascript">
	$$('*[name="email"]')[0].focus()
</script>