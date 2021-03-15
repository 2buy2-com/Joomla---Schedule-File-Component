<?php
defined('_JEXEC') or die('Restricted access');

class ScheduleFilesTableFileManager extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__scheduled_tasks_files', 'id', $db);
    }
}
?>