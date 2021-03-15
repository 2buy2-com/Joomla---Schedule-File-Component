<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_schedulefilsInstallerScript
{
	private function echoMessage($messageName, $type='message')
    {
        JFactory::getApplication()->enqueueMessage(JText::_($messageName), $type);
    }

    public function install($parent)
    {
        JLog::add("Schedule files installation script running.", JLog::INFO, 'schedulefiles');
        $this->echoMessage('COM_SCHEDULEFILES_INSTALL_TEXT');
    }

    public function uninstall($parent)
    {
    	JLog::add("Schedule files uninstallation script complete.", JLog::INFO, 'schedulefiles');
        $this->echoMessage('COM_SCHEDULEFILES_UNINSTALL_TEXT');
    }

    public function update($parent)
    {

    }

    public function postflight($type, $parent)
    {
        if ($type == 'install'):
            $this->echoMessage('COM_SCHEDULEFILES_INSTALL_COMPLETE');
        endif;
    }


}