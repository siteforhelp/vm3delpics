<?php
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined("_JEXEC") or die();

$controller = JControllerLegacy::getInstance('Vm3delpics');

$input = jFactory::getApplication()->input;//данные из url

$controller->execute($input->getCmd('task','display'));

$controller->redirect();

?>