<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JLoader::register('ScheduleFilesHelper', JPATH_COMPONENT . '/helpers/schedulefiles.php');
$controller = JControllerLegacy::getInstance('ScheduleFiles');
$input = JFactory::getApplication()->input;
try {
    JLog::add('Controller execute this task: '.$input->getCmd('task'), JLog::DEBUG, 'schedule-files');
    $controller->execute($input->getCmd('task'));
}
catch ( Throwable $t ) {
    JLog::add("Error with controller execution: ".$t->getMessage(), JLog::WARNING, 'schedule-files');
    JLog::add("Exception in ".$t->getFile().": ".$t->getLine(), JLog::WARNING, 'schedule-files');
    throw $t;
}
$controller->redirect();
?>