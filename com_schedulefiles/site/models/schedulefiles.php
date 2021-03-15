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

class ScheduleFilesModelScheduleFiles extends JModelList
{
	public function getItems() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from($db->quoteName('#__scheduled_tasks'))
			->where($db->quoteName('delete').' = 0');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}