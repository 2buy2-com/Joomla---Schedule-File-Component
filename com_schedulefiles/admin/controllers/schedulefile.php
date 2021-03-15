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
class ScheduleFilesControllerScheduleFile extends JControllerForm
{
    public function cancel($key = null)
    {
        $app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php?option=com_schedulefiles', false));
    }

	public function save($key = null, $urlVar = null ){
		$data = $this->input->post->get('jform', array(), 'array');
		$task = $this->getTask();
		$context = "$this->option.edit.$this->context";
		$result = false;
		$id = $this->input->getInt('id');
		if($id):
			// update
			$store = new ScheduleFilesStore;
			$result = $store->updateSchedule($data);
		else:
			$store = new ScheduleFilesStore;
			$result = $store->createSchedule($data);
		endif;
		if ($result):
            $this->setRedirect(
                JRoute::_( 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false )
            );
        endif;
        return $result;
    }
}