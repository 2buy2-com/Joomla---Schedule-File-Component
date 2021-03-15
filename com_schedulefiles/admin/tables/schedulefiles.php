<?php
defined('_JEXEC') or die('Restricted access');

class ScheduleFilesTableScheduleFiles extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__scheduled_tasks', 'id', $db);
    }
}
?>