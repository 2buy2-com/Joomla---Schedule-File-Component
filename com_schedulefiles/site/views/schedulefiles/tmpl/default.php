<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
	jQuery(document).on('click', '.run-schedulefiles', function(e){
		e.preventDefault();
		jQuery.ajax(
			"index.php?option=com_schedulefiles&view=schedulefiles&extension=com_schedulefiles&format=json&task=run_file", 
			{
				data: {
					'file_id': jQuery(this).attr('data-file')
				},
			success: function(result, status, xhr){
				if(status == 'success'){
					location.reload();
				} else {
					alert('Error code: '+result.status_code);
				}
			},
			error: function() {
				console.log('AJAX call failed');
			}
		});
	});
	jQuery(document).on('change', 'input[type="radio"]', function(){
		if(jQuery('input[type="radio"]:checked').length > 0){
			jQuery('input[name="boxchecked"]').val('1');
		} else {
			jQuery('input[name="boxchecked"]').val('0');
		}
	});
	jQuery(document).on('click', '.toggle-output', function(e){
		e.preventDefault();
		var id = jQuery(this).attr('aria-controls');
		if(jQuery('#'+id).hasClass('collapse')){
			jQuery('#'+id).toggleClass('collapse').show();
		} else {
			jQuery('#'+id).toggleClass('collapse').hide();
		}
	});
</script>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<form action="index.php?option=com_schedulefiles&view=schedulefile" method="post" id="adminForm" name="adminForm">
	<div id="j-main-container" class="span10">
		<h2><?php echo JText::_('COM_SCHEDULEFILES_NAME') ?></h2>
		<p><?php echo JText::_('COM_SCHEDULEFILES_DESC') ?></p>
		<?php if(empty($this->items)): ?>
			<p><?php echo JText::_('COM_SCHEDULEFILES_EMPTY'); ?></p>
		<?php else: ?>
			<table class="table table-striped table-hover">
	            <thead>
					<tr>
						<th></th>
						<th><?php echo JText::_('COM_SCHEDULEFILES_TITLE_NAME'); ?></th>
						<th><?php echo JText::_('COM_SCHEDULEFILES_TITLE_FILENAME'); ?></th>
						<th><?php echo JText::_('COM_SCHEDULEFILES_TITLE_CREATEDBY'); ?></th>
					    <th><?php echo JText::_('COM_SCHEDULEFILES_TITLE_LASTRUN'); ?></th>
					    <th><?php echo JText::_('COM_SCHEDULEFILES_TITLE_STATUS'); ?></th>
					    <th></th>
					    <th></th>
					</tr>
				</thead>
				<tbody>
	    			<?php foreach ($this->items as $i => $row) : ?>
	    				<?php
	    					$link = 'index.php?option=com_schedulefiles&view=schedulefile&layout=edit&id='.$row->id;
	    					if($row->active):
	    						$status = 'Active';
	    					else:
	    						$status = 'Inactive';
	    					endif;
	    					$user = JFactory::getUser($row->created_by);
	    					if(empty($row->date_last_run)):
	    						$row->date_last_run = 'Task not yet run';
	    					else:
	    						$row->date_last_run = date('d M Y H:i:s', strtotime($row->date_last_run));
	    					endif;
	    				?>
	    				<tr>
	    					<td><input type="radio" name="schedulefiles" value="<?= $row->id; ?>"/></td>
	    					<td><a href="<?= $link; ?>"><?= $row->name; ?></a></td>
	    					<td><?= $row->filename; ?></td>
	    					<td><?= $user->name; ?></td>
	    					<td><?= $row->date_last_run; ?></td>
	    					<td><?= $status; ?></td>
	    					<td><a class="btn btn-primary run-schedulefiles" data-file="<?= $row->id; ?>">Run now</a></td>
	    					<?php if($row->date_last_run == 'Task not yet run'): ?>
	    						<td></td>
	    					<?php else: ?>
	    						<td> <button class="btn btn-secondary toggle-output" type="button" data-target="#fileOutput<?= $row->id; ?>" aria-expanded="false" aria-controls="fileOutput<?= $row->id; ?>">Output from file</button></td>
	    					<?php endif; ?>
	    				</tr>
	    				<?php if($row->date_last_run != 'Task not yet run'): ?>
	    					<tr id="fileOutput<?= $row->id; ?>" class="collapse" style="display:none;">
	    						<td colspan="8"><?= $row->output; ?></td>
	    					</tr>
	    				<?php endif; ?>
	    			<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>		
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
</form>