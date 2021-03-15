<?php
defined('_JEXEC') or die('Restricted access');

require(JPATH_COMPONENT_ADMINISTRATOR.'/libraries/schedulefileshandler.php');

class ScheduleFilesViewScheduleFiles extends JViewLegacy
{
	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$file_id = $input->get('file_id', 0, 'INTEGER');
		$model = $this->getModel();
		if(0 < $file_id):
			$class = new ScheduleFilesHandler($file_id);
			$class->execute_file($file_id);
		else:
			$class = new ScheduleFilesHandler;
			$class->execute_files();
		endif;
	}
}