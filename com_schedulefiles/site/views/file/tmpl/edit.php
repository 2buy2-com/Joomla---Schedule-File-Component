<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_schedulefiles
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
?>



<form action="<?php echo JRoute::_('index.php?option=com_schedulefiles&view=file&layout=edit&id='.(int) $this->item->id); ?>"
          method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <?php if($this->item->id == 0): ?>
                <legend><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_FILENAME_NEW') ?></legend>
            <?php else: ?>
                <legend><?php echo JText::_('COM_SCHEDULEFILES_FILEMANAGER_EDIT') ?></legend>
            <?php endif; ?>
            <div class="row-fluid">
                <?php foreach($this->form->getFieldset() as $field): ?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $field->label; ?></div>
                        <div class="controls"><?php echo $field->input; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="file.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>