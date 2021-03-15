<?php
defined('_JEXEC') or die('Restricted access');

class ScheduleFilesHandler {
	
	private $dir = 'includes/scripts/';
	public $table = array(
		'users'				=> '#__users',
		'schedulefiles' 	=> '#__scheduled_tasks',
		'bk_schedulefiles'	=> 'backup_scheduledtasks',
		'status'			=> '#__scheduled_tasks_status',
		'bk_status'			=> 'backup_scheduledtasksstatus'
	);
	private $http_code_array = array(
		100 => "Continue",
		101 => "Switching Protocols",
		200 => "OK",
		201 => "Created",
		202 => "Accepted",
		203 => "Non-Authoritative Information",
		204 => "No Content",
		205 => "Reset Content",
		206 => "Partial Content",
		300 => "Multiple Choices",
		301 => "Moved Permanently",
		302 => "Found",
		303 => "See Other",
		304 => "Not Modified",
		305 => "Use Proxy",
		306 => "(Unused)",
		307 => "Temporary Redirect",
		400 => "Bad Request",
		401 => "Unauthorized",
		402 => "Payment Required",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Method Not Allowed",
		406 => "Not Acceptable",
		407 => "Proxy Authentication Required",
		408 => "Request Timeout",
		409 => "Conflict",
		410 => "Gone",
		411 => "Length Required",
		412 => "Precondition Failed",
		413 => "Request Entity Too Large",
		414 => "Request-URI Too Long",
		415 => "Unsupported Media Type",
		416 => "Requested Range Not Satisfiable",
		417 => "Expectation Failed",
		500 => "Internal Server Error",
		501 => "Not Implemented",
		502 => "Bad Gateway",
		503 => "Service Unavailable",
		504 => "Gateway Timeout",
		505 => "HTTP Version Not Supported"
	);

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

	function __construct($id = null)
	{
		if(!is_null($id)):
			$this->loadRecord($id);
		endif;
	}

	private function loadRecord(int $id)
	{
		if(0 < $id):
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select('*')
				->from($db->quoteName($this->table['schedulefiles']))
				->where($db->quoteName('id').' = '.$id);
			$db->setQuery($query);
			$result = $db->loadObject();
			$this->name = $result->name;
			$this->filename = $result->filename;
			$this->frequency = $result->frequency;
			$this->day = $result->day;
			$this->active = $result->active;
			$this->date_modified = $result->date_modified;
			$this->date_last_run = $result->date_last_run;
			$this->created_by = $result->created_by;
			$this->modified_by = $result->modified_by;
			$this->executed_by = $result->executed_by; 
		endif;
	}

	private function getAllActiveScheduleFiles()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from($db->quoteName($this->table['schedulefiles']))
			->where($db->quoteName('active').' = 1')
			->where($db->quoteName('delete').' = 0');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	private function filterTasks($id, $date_last_run, $freq)
	{
		$response = false;
		$date = new DateTime($date_last_run);
		$now = new DateTime();
		$diff = $now->diff($date);
		switch($freq){
			case 'daily':
				if($diff->days >= 1):
					$response = true;
				endif;
				break;
			case 'weekly':
				if($diff->days >= 7):
					$response = true;
				endif;
				break;
			case 'monthly':
				if($diff->m >= 1):
					$response = true;
				endif;
				break;
			default:
				$response = true;
		}
		return $response;
	}

	private function eligible_tasks()
	{
		$allTasks = $this->getAllActiveScheduleFiles();
		$filesToRun = array();
		foreach($allTasks as $task):
			if(empty($task->date_last_run) || $this->filterTasks($task->id, $task->date_last_run, $task->frequency)):
				array_push($filesToRun, $task->id);
			endif;
		endforeach;
		return $filesToRun;
	}

	public function execute_files()
	{
		$files = $this->eligible_tasks();
		foreach($files as $file):
			$this->execute_file($file);
		endforeach;
	}

	public function execute_file($id)
	{
		$this->id = $id;
		try 
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName('filename'))
				->from($db->quoteName($this->table['schedulefiles']))
				->where($db->quoteName('id').' = '.$this->id);
			$db->setQuery($query);
			$this->filename = $db->loadResult();
			if(!empty($this->filename)):
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, JUri::root().$this->dir.$this->filename);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				if($output = curl_exec($ch)):
					$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					$status_update = $this->schedule_status_update($this->id, $http_code, $this->http_code_array[$http_code], $output);
					return $status_update;
				endif;
				curl_close($ch);
			endif;		
		} catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$results = null;
		}
	}

	/**
	* Provide update on status of the scheduler task (if executed or not)
	* @var $id INT ID of the row of scheduled_task
	* @var $http_code INT Type of update (HTTP Response)
	* @var $http_code_text STRING Type of update (HTTP Response)
	*/
	private function schedule_status_update(int $id, int $http_code, string $http_code_text, string $output)
	{
		$this->id = $id;

		$insert = new stdClass();
		$insert->task_id = $id;
		$insert->http_code = $http_code;
		$insert->http_code_text = $http_code_text;
		JFactory::getDbo()->insertObject($this->table['status'], $insert);

		$update = new stdClass();
		$update->id = $id;
		if(!JFactory::getUser()->guest):
			$update->executed_by = JFactory::getUser()->id;
		endif;
		$update->date_last_run = date('Y-m-d H:i:s');
		$update->output = $output;
		JFactory::getDbo()->updateObject($this->table['schedulefiles'], $update, 'id');

		$response = (object) array(
			'status_code'	=> $http_code,
			'id'			=> $id
		);
		print_r(json_encode($response));	
	}

}