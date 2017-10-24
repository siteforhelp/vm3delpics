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
	
class Vm3delpicsControllerDBDelete extends JControllerAdmin{

	protected $view_list = 'dbdelete';//вид, который будет загружаться после удаления фотографии(й)
	
	public function getModel($name='DBDelete', $prefix='Vm3delpicsModel', $config=array()){//$name - имя модели
		return parent::getModel($name, $prefix, $config);
	}

	public function deleteDBSelected(){//функция удаления выделенных фотографий
		$vm_media = new VmImage;
		$model = $this->getModel();
		$files = $model->getDBSelectedFiles();
		foreach ($files as $value ){
            if($value['file_url']) {
                $path_url = JPATH_SITE.DS.$value['file_url'];
                $res = unlink($path_url);
				list($vm_media->file_name, $vm_media->file_extension) = explode(".",strrchr($value['file_url'], '/'));
				$path_url_thumb = JPATH_SITE.DS.str_replace('/product/'
					, '/product/resized/'
					, str_replace($vm_media->file_name.$vm_media->file_extension, $vm_media->createThumbName().$vm_media->file_extension,$value['file_url']));
				$res2 = unlink($path_url_thumb);
            }
            if($value['file_url_thumb']){
				$path_url_thumb2 = JPATH_SITE.DS.$value['file_url_thumb'];
				if($path_url_thumb2 != $path_url_thumb) $res2 = unlink($path_url_thumb2);
            }
		}
		$some_return = $model->deleteDBSelected();
		$this->setRedirect(JRoute::_('index.php?option=com_vm3delpics&view=dbdelete', false));
		return $some_return;
	}

	public function deleteDBAllImgs(){//функция удаления всех найденных ненужных фотографий
		$model = $this->getModel();
        $files = $model->getDBAllFiles();
        if(count($files)>0){
            foreach ($files as $value ){
                if($value->file_url) {
                    $path_url = JPATH_SITE.DS.$value->file_url;
                    if(file_exists($path_url)) unlink($path_url);
                }
                if($value->file_url_thumb){
                    $path_url_thumb = JPATH_SITE.DS.$value->file_url_thumb;
                }
                else{
                    $path_url_thumb = JPATH_SITE.DS.str_replace('/product/'
                            , '/product/resized/'
                            , $value->file_url);
                }
                if ($path_url_thumb)
                    if(file_exists($path_url_thumb)) unlink($path_url_thumb);
            }
		    $some_return = $model->deleteDBAllImgs($files);
        }
		$this->setRedirect(JRoute::_('index.php?option=com_vm3delpics&view=dbdelete', false));
		return $some_return;
	}
}

?>