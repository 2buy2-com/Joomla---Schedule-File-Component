<?php
defined('_JEXEC') or die('Restricted access');
class ScheduleFilesControllerScheduleFiles extends JControllerAdmin
{
    
     public function getModel($name="ScheduleFiles", $prefix="ScheduleFilesModel", $config = array('ignore_request' => true))
     {
         $model = parent::getModel($name, $prefix, $config);
         return $model;
     }

    public function add()
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_('index.php?option=com_schedulefiles&view=schedulefile&layout=edit&id=0', false));
	}

	public function edit()
	{
		$input = JFactory::getApplication()->input;
		$id = $input->get('schedulefiles', 0, 'int');
		if($id == 0){
			$ids = $input->get('cid', array(), 'array');
			$id = $ids[0];
		}
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("index.php?option=com_schedulefiles&view=schedulefile&layout=edit&id=".$id, false));
	}

	public function cancel()
    {
        $app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php?option=com_schedulefiles', false));
    }

    public function delete()
    {
    	require_once(JPATH_COMPONENT_ADMINISTRATOR.'/libraries/schedulefileshandler.php');
    	$app = JFactory::getApplication();
    	$input = $app->input;
		$id = $input->get('schedulefiles', 0, 'int');
		if(!empty($id)):
			$handler = new ScheduleFilesHandler;
			$update = new stdClass();
			$update->id = $id;
			$update->delete = 1;
			JFactory::getDbo()->updateObject($handler->table['schedulefiles'], $update, 'id');
			JFactory::getDbo()->updateObject($handler->table['bk_schedulefiles'], $update, 'id');
			$app->redirect(JRoute::_('index.php?option=com_schedulefiles', false));
		endif;
    }
}
?>