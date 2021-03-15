<?php
defined('_JEXEC') or die('Restricted access');
class ScheduleFilesControllerFileManager extends JControllerForm
{
	public function getModel($name="Filemanager", $prefix="ScheduleFilesModel", $config = array('ignore_request' => true))
     {
         $model = parent::getModel($name, $prefix, $config);
         return $model;
     }
    public function add()
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_('index.php?option=com_schedulefiles&view=file&layout=edit&id=0', false));
	}

	public function edit($key = NULL, $urlVar = NULL)
	{
		$input = JFactory::getApplication()->input;
		$id = $input->get('file_id', 0, 'int');
		if($id == 0){
			$ids = $input->get('cid', array(), 'array');
			$id = $ids[0];
		}
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("index.php?option=com_schedulefiles&view=file&layout=edit&id=".$id, false));
	}

    public function delete()
    {
    	$app = JFactory::getApplication();
    	$input = $app->input;
		$id = $input->get('file_id');
		if(!empty($id)):
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select('COUNT(*)')
				->from($db->quoteName('#__scheduled_tasks'))
				->where($db->quoteName('filename').' = '.$db->quote($id))
				->where($db->quoteName('delete').' = 0');
			$db->setQuery($query);
			$result = $db->loadResult();
			if($result != 0){
				$app->enqueueMessage('This file is in use, so you have to remove the scheduled task first', 'error');
				return false;
			} else {
				$query = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('filename').' = '.$db->quote($id),
					$db->quoteName('delete').' = 1'
				);
				$query->delete($db->quoteName('#__scheduled_tasks'))->where($conditions);
				$db->setQery($query);
				$db->execute();
				jimport('joomla.filesystem.file');
				JFile::delete(JPATH_ROOT.'/includes/scripts/'.$id);
				$app->redirect(JRoute::_("index.php?option=com_schedulefiles&view=filemanager", false));
				//$app->enqueueMessage('File has been deleted', 'message');	
				//return true;			
			}
		endif;
    }
}
?>