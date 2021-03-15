<?php
defined('_JEXEC') or die('Restricted access');
?>
	<script type="text/javascript">
		jQuery(document).on('change', 'input[type="radio"]', function(){
			if(jQuery('input[type="radio"]:checked').length > 0){
				jQuery('input[name="boxchecked"]').val('1');
			} else {
				jQuery('input[name="boxchecked"]').val('0');
			}
		});
	</script>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <form action="index.php?option=com_schedulefiles&view=filemanager" method="post" id="adminForm" name="adminForm">
	    <div id="j-main-container" class="span10">
	        <h2><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_NAME') ?></h2>
	        <p><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_DESC') ?></p>   
	        <?php if(!empty($this->item)): ?>
	        	<table class="table table-striped table-hover">
		            <thead>
						<tr>
							<th></th>
							<th><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_FILENAME') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->item as $i => $row): ?>
							<tr>
								<td><input type="radio" name="file_id" value="<?= $row; ?>"/></td>
								<td><?= $row; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
	        <?php else: ?>
	        	<p><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_EMPTY') ?></p>  
	        <?php endif; ?>   
	    </div>
	    <input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>