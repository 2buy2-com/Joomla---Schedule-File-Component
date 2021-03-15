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
          method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SCHEDULEFILES_DESC') ?></legend>
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
    <input type="hidden" name="task" value="schedulefile.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>