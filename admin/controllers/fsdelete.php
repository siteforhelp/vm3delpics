<?php 
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 
defined("_JEXEC") or die();

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
if (!class_exists('VmImage')) require(VMPATH_ADMIN.DS.'helpers'.DS.'image.php');
	
class Vm3delpicsControllerFSDelete extends JControllerAdmin{

	protected $view_list = 'fsdelete';//вид, который будет загружаться после удаления фотографии(й)

	public function getModel($name='FSDelete', $prefix='Vm3delpicsModel', $config=array()){//$name - имя модели
		return parent::getModel($name, $prefix, $config);
	}

	public function deleteFSSelected(){//функция удаления выделенных файлов из ФС
		$files = JFactory::getApplication()->input->post->getAray('cid');
		$i = 0;$cnt = 0;
		foreach ($files as $value ){
			$i++;
			//var_dump(JPATH_SITE.DS.$value);
			if(unlink(JPATH_SITE.DS.$value))$cnt++;
		}
		//exit();
		$this->setRedirect(JRoute::_('index.php?option=com_vm3delpics&view=fsdelete', false));
		return "Было удалено $cnt файлов из $i в задании";
	}

	public function deleteFSAllFiles(){//функция удаления всех найденных непривязанных к БД файлов
		// проверить, может они уже где-то сохранены в переменных среды
        $files = JFactory::getApplication()->input->post->getAray('cid');
		$i = 0;$cnt = 0;
		foreach ($files as $value){
			$i++;
			if(unlink(JPATH_SITE.DS.$value['file_url']))$cnt++;
		}
		exit();
		$this->setRedirect(JRoute::_('index.php?option=com_vm3delpics&view=fsdelete', false));
		return "Было удалено $cnt файлов из $i в задании";
	}

}

?>