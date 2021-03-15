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

require_once(JPATH_COMPONENT_ADMINISTRATOR.'/libraries/schedulefileshandler.php');

class ScheduleFiles 
{
	public $id;
	public $name;
	public $filename;
	public $frequency;
	public $day;
	public $hour;
	public $active;
	public $date_modified;
	public $date_last_run;
	public $created_by;
	public $modified_by;
	public $executed_by;

	function __construct($name, $filename, $frequency, $day, $hour, $active, $date_last_run, $created_by, $id = null, $executed_by = null, $date_modified = null, $modified_by = null)
	{
		if($name):
			$this->name = $name;
			$this->filename = $filename;
			$this->frequency = $frequency;
			$this->day = $day;
			$this->hour = $hour;
			$this->active = $active;
			$this->date_modified = $date_modified;
			$this->date_last_run = $date_last_run;
			$this->created_by = $created_by;
			$this->modified_by = $modified_by;
			$this->executed_by = $executed_by;
		endif;
		if($id):
			$this->id = (int) $id;
		endif;
	}

	/**
	* Parse the Schedule File row from an associative array	
	*/
	public static function fromRow($row)
	{
		return new ScheduleFiles($row['name'], $row['filename'], $row['frequency'], $row['day'], $row['hour'], $row['active'], $row['date_last_run'], $row['created_by'], $row['id'], $row['exected_by'], $row['date_modified'], $row['modified_by']);
	}

	public function toDataModel()
	{
		if (!$this->validate()):
        	throw new Exception("Cannot safely convert to data model, incorrect field types.");
        else:
        	$result = array(
        		"name"		=> $this->name,
        		"filename"	=> $this->filename,
        		"frequency"	=> $this->frequency,
        		"day"		=> $this->day,
        		"hour"		=> $this->hour,
        		"active"	=> $this->active,
        		"date_modified"	=> $this->date_modified,
        		"date_last_run"	=> $this->date_last_run,
        		"created_by"	=> $this->created_by,
        		"modified_by"	=> $this->modified_by,
        		"executed_by"	=> $this->executed_by
        	);
        	if(0 < $this->id):
        		$result['id'] = $this->id;
        	endif;
        	return (object) $result;
        endif;
	}

	private function validate()
	{
		$valid = !$this->filename || !empty($this->filename);
		if($this->id):
			$valid = $valid && is_int($this->id);
			$valid = $valid && is_int($this->active);
		endif;
		return $valid;
	}
}

interface FileStore
{
	public function createSchedule($data);
	public function updateSchedule($data);
}


class ScheduleFilesStore implements FileStore
{
	public function createSchedule($data)
	{
		$handler = new ScheduleFilesHandler;
		$model = ScheduleFiles::fromRow($data);
		$model->created_by = JFactory::getUser()->id;
		$db = JFactory::getDbo();
		$db->insertObject($handler->table['schedulefiles'], $model);
		$db->insertObject($handler->table['bk_schedulefiles'], $model);
		$db->setQuery("SELECT * FROM ".$handler->table['schedulefiles']." WHERE filename = '".$data->filename."'");
		return ScheduleFiles::fromRow($db->loadAssoc());
	}

	public function updateSchedule($data)
	{
		$handler = new ScheduleFilesHandler;
		$model = ScheduleFiles::fromRow($data);
		$model->modified_by = JFactory::getUser()->id;
		$model->date_modified = date('Y-m-d H:i:s');
		$db = JFactory::getDbo();
		$db->updateObject($handler->table['schedulefiles'], $model, 'id');
		$db->updateObject($handler->table['bk_schedulefiles'], $model, 'id');
		return $data;
	}

	private function uniqueSchedule($data)
	{
		$handler = new ScheduleFilesHandler;
		$db = JFactory::getDbo();
		$unique = (object) array('name' => true, 'filename' => true);
		$query = "SELECT COUNT(id) FROM ".$handler->table['schedulefiles']." WHERE name = ".$db->quote($data->name)." AND active = 1";
		if($data->id):
			$query .= " AND id = ".$data->id;
		endif;
		$db->setQuery($query);
		$unique->name = $this->db->loadRow()[0];
		$query = "SELECT COUNT(id) FROM ".$handler->table['schedulefiles']." WHERE filename = ".$db->quote($data->filename)." AND active = 1";
		if($data->id):
			$query .= " AND id != ".$data->id;
		endif;
		$db->setQuery($query);
		$unique->filename = $db->loadRow()[0];
		return $unique;
	}
}