<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
class ScheduleFilesViewFile extends JViewLegacy
{
    protected $form = null;
    protected $canDo;
    public function display($tpl = null)
    {
        $this->form = $this->get('form');
        $this->item = $this->get('item');
        $this->canDo = JHelperContent::getActions('com_schedulefiles', 'file', $this->item->id);
        // Check for errors.
        if (count($errors = $this->get('Errors'))):
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        endif;
        $this->addToolBar();
        $this->setDocument();
        parent::display($tpl);
    }
    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        // Hide Joomla Administrator Main menu
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? JText::_('COM_SCHEDULEFILES_NEW')
                                     : JText::_('COM_SCHEDULEFILES_EDIT'), 'file');
        
        if ($isNew):
            // For new records, check the create permission.
            if ($this->canDo->get('core.create')):
                JToolBarHelper::save('file.save', 'JTOOLBAR_SAVE');
            endif;
            JToolBarHelper::cancel('file.cancel', 'JTOOLBAR_CANCEL');
        else:
            if ($this->canDo->get('core.edit')):
                // We can save the new record
                JToolBarHelper::save('file.save', 'JTOOLBAR_SAVE');
                // We can save this record, but check the create permission to see
            endif;
            JToolBarHelper::cancel('file.cancel', 'JTOOLBAR_CLOSE');
        endif;
    }
    protected function setDocument() 
    {
        $isNew = ($this->item->id == 0);
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_SCHEDULEFILES_NEW')
                                   : JText::_('COM_SCHEDULEFILES_EDIT'));
        //$document->addScript(JURI::root() . "/administrator/components/com_schedulefiles/views/helloworld/submitbutton.js");
        JText::script('COM_SCHEDULEFILES_ERROR_UNACCEPTABLE');
    }
}