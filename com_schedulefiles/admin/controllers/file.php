<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_schedulefiles
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.'/libraries/schedulefilesstore.php');

/**
 * HelloWorld Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_schedulefiles
 * @since       0.0.9
 */
class ScheduleFilesControllerFile extends JControllerForm
{
    public function cancel($key = null)
    {
        $app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php?option=com_schedulefiles&view=filemanager&extension=com_schedulefiles', false));
    }

	public function save($key = null, $urlVar = null ){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication();
		$model = $this->getModel('file');
		$currentUri = (string)JUri::getInstance();
		$data  = $app->input->get('jform', array(), 'array');
		$context = "$this->option.edit.$this->context";
		$app->setUserState($context . '.data', $data);
		$form = $model->getForm($data, false);
		$fileinfo = $app->input->files->get('jform', array(), 'raw');
		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}
		$validData = $model->validate($form, $data);
		if ($validData === false)
		{
			$errors = $model->getErrors();
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			return false;
		}
		$file = $fileinfo['fileupload'];
		if ($file['error'] == 4)
		{
			$validData['error'] = null;
		} 
		else 
		{
			if ($file['error'] > 0)
			{
				$app->enqueueMessage(JText::sprintf('COM_SCHEDULEFILES_ERROR_FILEUPLOAD', $file['error']), 'warning');
				return false;
			}
			$file['name'] = JFile::makeSafe($file['name']);

			if (empty($file['name']) || strtolower(JFile::getExt($file['name'])) != 'php')
			{
				// No filename (after the name was cleaned by JFile::makeSafe)
				$app->enqueueMessage(JText::_('COM_SCHEDULEFILES_ERROR_BADFILENAME'), 'warning');
				return false;
			}
			$file['name'] = str_replace(' ', '-', $file['name']);

			$absolutePathname = JPATH_ROOT.'/includes/scripts/'.$file['name'];
			if (JFile::exists($absolutePathname))
			{
				$app->enqueueMessage(JText::_('COM_SCHEDULEFILES_ERROR_FILE_EXISTS'), 'warning');
				return false;
			}
   			if (!JFile::upload($file['tmp_name'], $absolutePathname, false, true))
   				// set allow_unsafe to true to allow upload of PHP files 
			{
				//echo '<pre>'; print_r($file); echo '</pre>'; die();
				$app->enqueueMessage(JText::_('COM_SCHEDULEFILES_ERROR_UNABLE_TO_UPLOAD_FILE'), 'warning');
				return false;
			}
		}
		$validData['created_by'] = JFactory::getUser()->get('id', 0);
		$validData['created'] = date('Y-m-d h:i:s');
		if (!$model->save($validData))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			return false;
		}
		$app->setUserState($context . '.data', null);
		$app->redirect(JRoute::_('index.php?option=com_schedulefiles&view=filemanager&extension=com_schedulefiles', false));
		return true;
    }
}