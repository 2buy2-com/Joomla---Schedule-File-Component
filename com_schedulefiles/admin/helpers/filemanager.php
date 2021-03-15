<?php 
defined('_JEXEC') or die('Restricted access');

abstract class FileManagerHelper extends JHelperContent
{
    public static function addSubMenu($submenu)
    {
        $document = JFactory::getDocument();
        JHtmlSidebar::addEntry(
            JText::_('COM_SCHEDULEFILES_LIST_MENU'),
            'index.php?option=com_schedulefiles',
            $submenu=='schedulefiles');
        JHtmlSidebar::addEntry(
            JText::_('COM_SCHEDULEFILES_FILES_MENU'),
            'index.php?option=com_schedulefiles&view=filemanager&extension=com_schedulefiles',
            $submenu=='filemanager');
        if ($submenu=='schedulefiles'):
            $document->setTitle(JText::_('Scheduled Tasks'));
        elseif($submenu == 'filemanager'):
            $document->setTitle(JText::_('Scheduled Task Files'));
        endif;
    }
}