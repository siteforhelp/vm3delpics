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
	
	public function read(){
        try{
            $data = $this->input->get('data', null, 'array');
			$from = (int)$data["from"];
			$read_cnt = (int)$data["read_cnt"];
            $result = $this->getModel('FSDelete')->select_files($from, $read_cnt);
			try{
				if(!json_encode($result)) throw new Exception('There was an error while json_encode($result)');
				echo new JResponseJson($result);
			}
			catch(Exception $e){
				echo new JResponseJson($e->getMessage());
			}
			
        }
        catch(Exception $e){
            echo new JResponseJson($e);
        }
	}

	public function deleteFSSelected(){//функция удаления выделенных файлов из ФС
		$data = $this->input->get('data', null, 'array');
		$files = $data["files"];
		$deleted = array();
		foreach ($files as $value ){
			if(unlink(JPATH_SITE.DS.$value))$deleted[] = $value;
		}
		try{
				echo new JResponseJson($deleted);
		}
		catch(Exception $e){
				echo new JResponseJson($e->getMessage());
		}
	}
}

?>