<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
class ScheduleFilesViewScheduleFiles extends JViewLegacy
{

    function display($tpl = null) 
    {
        $this->items            = $this->get('items');
        $this->canDo            = JHelperContent::getActions('com_schedulefiles');
        ScheduleFilesHelper::addSubmenu('schedulefiles');
        $this->sidebar          = JHtmlSidebar::render();
        $this->addToolBar();
        // Check for errors.
        if(!empty($this->get('Errors'))):
        	$errors = $this->get('Errors');
        	if(count($errors)):
        		throw new Exception(implode("\n", $errors), 500);
				return false;
        	endif;
        endif;
        return parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolbarHelper::title(JText::_('COM_SCHEDULEFILES_NAME'));
        if ($this->canDo->get('core.create')):
            JToolBarHelper::addNew('schedulefiles.add', 'JTOOLBAR_NEW');
        endif;
        if ($this->canDo->get('core.edit')):
            JToolBarHelper::editList('schedulefiles.edit', 'JTOOLBAR_EDIT');
        endif;
        if ($this->canDo->get('core.delete')):
            JToolBarHelper::deleteList(JText::_('RSFP_ARE_YOU_SURE_DELETE'), 'schedulefiles.delete', 'JTOOLBAR_DELETE');
        endif;
        if ($this->canDo->get('core.admin')):
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_schedulefiles');
        endif;      
    }
}