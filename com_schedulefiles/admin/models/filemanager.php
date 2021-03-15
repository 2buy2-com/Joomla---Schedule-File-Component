<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class ScheduleFilesModelFilemanager extends JModelList
{	
	public function getItem()
	{
		jimport('joomla.filesystem.folder');
		$fileList = JFolder::files(
			JPATH_ROOT.'/includes/scripts', 
			'.', 
			false, 
			false, 
			array(
				'.git',
				'.svn',
				'CVS',
				'.DS_Store',
				'__MACOSX'
			)
		);
		return $fileList;
	}
}