<form action="" method="post">
	<input type="hidden" name="_cmd_create" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<?=FormStructure::text('First Name','name_first')?>
			</tr>
			<tr>
				<?=FormStructure::text('Last Name','name_last')?>
			</tr>
			<tr>
				<?=FormStructure::text('Email','email')?>
			</tr>
			<tr>
				<?=FormStructure::password('Password','password')?>
			</tr>
			<tr>
				<?=FormStructure::text('Display Name','display_name')?>
			</tr>
			<tr>
				<?=FormStructure::fieldColumns('agree','Agree to Terms',Form::checkbox('agree').' <a class="newTab" href="/user/tou">Terms of Use</a>')?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><input type="submit" value="Sign Up!"/></td>
			</tr>
		</tfoot>
	</table>
</form>

