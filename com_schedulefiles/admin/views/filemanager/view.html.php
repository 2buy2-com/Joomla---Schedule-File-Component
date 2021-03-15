<?php

defined('_JEXEC') or die('Restricted access');

class ScheduleFilesViewFileManager extends JViewLegacy
{

    protected $form = null;
    protected $canDo;

	public function display($tpl = null) 
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/filemanager.php');
        $this->form = $this->get('form');
        $this->item = $this->get('item');
        FileManagerHelper::addSubmenu('filemanager');
        $this->sidebar = JHtmlSidebar::render();
        if(isset($this->item->id)):
            $this->canDo = JHelperContent::getActions('com_schedulefiles', 'filemanager', $this->item->id);
        else:
            $this->canDo = JHelperContent::getActions('com_schedulefiles', 'filemanager', 0);
        endif;
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
        JToolbarHelper::preferences('com_schedulefiles');        
        $input = JFactory::getApplication()->input;
        // Hide Joomla Administrator Main menu
        $input->set('hidemainmenu', true);

        switch($input->get('id')){
            case null:
                $isNew = 0;
                break;
            default:
                $isNew = 1;
                break;
        }
        JToolBarHelper::title($isNew ? JText::_('COM_SCHEDULEFILES_FILEMANAGER_FILENAME_NEW')
                                     : JText::_('COM_SCHEDULEFILES_FILES_MENU'), 'filemanager');
        if ($isNew):
            if ($this->canDo->get('core.create')):
                JToolBarHelper::save('filemanager.save', 'JTOOLBAR_SAVE');
            endif;
            JToolBarHelper::cancel('filemanager.cancel', 'JTOOLBAR_CANCEL');
        else:
            if ($this->canDo->get('core.create')):
                JToolBarHelper::addNew('filemanager.add', 'JTOOLBAR_NEW');
            endif;
            if ($this->canDo->get('core.delete')):
                JToolBarHelper::deleteList(JText::_('RSFP_ARE_YOU_SURE_DELETE'), 'filemanager.delete', 'JTOOLBAR_DELETE');
            endif;
            if ($this->canDo->get('core.admin')):
                JToolBarHelper::divider();
                JToolBarHelper::preferences('com_schedulefiles');
            endif;
        endif;
    }
    
    protected function setDocument() 
    {
        if(isset($this->item->id)):
            $isNew = ($this->item->id == 0);
        else:
            $isNew = 1;
        endif;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_SCHEDULEFILES_FILEMANAGER_FILENAME_NEW')
                                   : JText::_('COM_SCHEDULEFILES_FILES_MENU'));
        JText::script('COM_SCHEDULEFILES_ERROR_UNACCEPTABLE');
    }
}