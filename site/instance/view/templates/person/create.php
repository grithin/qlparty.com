<form action="" method="post">
	<input type="hidden" name="_cmd_create" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<?=FormStructure::text('First Name','name_first')?>
			</tr>
			<tr>
				<?=FormStructure::text('Middle Name','name_middle')?>
			</tr>
			<tr>
				<?=FormStructure::text('Last Name','name_last')?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><?=Form::submit('Create')?></td>
			</tr>
		</tfoot>
	</table>
</form>