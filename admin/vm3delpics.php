<?php
defined("_JEXEC") or die();

$controller = JControllerLegacy::getInstance('Vm3delpics');

$input = jFactory::getApplication()->input;//данные из url

$controller->execute($input->getCmd('task','display'));

$controller->redirect();

?>