<form action="" method="post">
	<input type="hidden" name="_cmd_create" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<?=FormStructure::textarea('Feedback','text',null,array('class'=>'big'))?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><input type="submit" value="Send Feedback"/></td>
			</tr>
		</tfoot>
	</table>
</form>

