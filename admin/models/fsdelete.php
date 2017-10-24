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

class Vm3delpicsModelFSDelete extends JModelList {
    public $vm_media;
	
	//функция получения массива с именами файлов, которые не задействованы в БД
	//входные параметры:
	// $read_cnt - число файлов, которое должно быть прочитано в этот раз
	public function select_files($from, $read_cnt){
		$cache = JFactory::getCache('somegroup', '');
 		if (!$common_list = $cache->get('comvm3delpics_files_array')){
			// выполняем действия и сохраняем результат в $common_list
            $prod_dir_path = JPATH_ROOT.DS.'images/stories/virtuemart/product/';
            $resized_dir_path = JPATH_ROOT.DS.'images/stories/virtuemart/product/resized/';
			$list_product = array_slice(scandir($prod_dir_path), 2);
            foreach ($list_product as $key => $value) {
                if(is_dir($prod_dir_path . $value)) unset($list_product[$key]);
            }
            $list_resized = array_slice(scandir($resized_dir_path),2);
            foreach ($list_resized as $key => $value) {
                if(is_dir($resized_dir_path . $value)) unset($list_resized[$key]);
                $list_resized[$key]=DS.'resized'.DS.$list_resized[$key];
            }
			$common_list = array_merge($list_product,$list_resized);
			$cache->store($common_list, 'comvm3delpics_files_array');// сохраняем $common_list в кэше
		}
		$files = array_slice($common_list, $from, $read_cnt);
        $returned_values["from"] = $from;
		$returned_values["read_cnt"] = $read_cnt;
		$returned_values["files"] = $this->check_this_out($files);
		$returned_values["go"] = (count($common_list)>$from+$read_cnt)?1:0;
		return $returned_values;
	}
	//функция проверки наличия упоминания файлов в БД
	public function check_this_out($files){
		$prod_cnt = 0;
		$rszd_cnt = 0;
		foreach($files as $value){
			if(mb_strpos($value, DS.'resized'.DS) === false ){
				$prod_cnt++;
				if($prod_cnt == 1) $prod_select = "SELECT x.file_url FROM( SELECT 'images/stories/virtuemart/product/$value' as file_url";
				else $prod_select .="
			UNION SELECT 'images/stories/virtuemart/product/$value' as file_url";
			}
			elseif(mb_strpos($value, DS.'resized'.DS)>=0){
				$value = substr($value, 9);
                $suppzd_full_image_name = $this->get_product_path($value);
                $rszd_cnt++;
				if($rszd_cnt == 1)
					if($prod_cnt) $rszd_select = " UNION SELECT y.file_url_thumb as file_url FROM( 
			SELECT 'images/stories/virtuemart/product/resized/$value' as file_url_thumb, 'images/stories/virtuemart/product/$suppzd_full_image_name' as file_url";
					else $rszd_select = "SELECT y.file_url_thumb as file_url FROM( 
			SELECT 'images/stories/virtuemart/product/resized/$value' as file_url_thumb, 'images/stories/virtuemart/product/$suppzd_full_image_name' as file_url";
				else $rszd_select.="
			UNION SELECT 'images/stories/virtuemart/product/resized/$value' as file_url_thumb, 'images/stories/virtuemart/product/$suppzd_full_image_name' as file_url";
			}
		}
		if($prod_cnt) $prod_select.=")x 
			LEFT JOIN #__virtuemart_medias m ON m.file_url = x.file_url AND m.file_type = 'product'
			WHERE 
				m.virtuemart_media_id IS NULL";
		else $prod_select = '';
		if($rszd_cnt) $rszd_select .=") y
			LEFT JOIN #__virtuemart_medias m ON (m.file_url = y.file_url OR m.file_url_thumb = y.file_url_thumb) AND m.file_type = 'product'
			WHERE 
				m.virtuemart_media_id IS NULL";
		else $rszd_select="";
		$union_select = $prod_select.$rszd_select;
        $db = JFactory::getDbo();
		$db->setQuery($union_select);
        return $db->loadAssocList();
		//return $files;
	}
	
	//функция получения названия детального изображения по имени превью
	public function get_product_path($file){
        $this->vm_media = new VmImage();
		list($this->vm_media->file_name, $this->vm_media->file_extension) = explode(".",$file);
		$product_name = $this->vm_media->createThumbName();
        if(strlen($product_name)-strlen($this->vm_media->file_name)>0){
            $suffix = substr($product_name, -(strlen($product_name)-strlen($this->vm_media->file_name)));
            if (strstr($this->vm_media->file_name,$suffix,true)) return strstr($this->vm_media->file_name,$suffix,true).'.'. $this->vm_media->file_extension;
            else return $file;
        }
        else{
            return $file;
        }
	}
/*	
	protected function getListQuery(){//то, что отображается в виде по умолчанию
		$product_files = $this->select_files(JPATH_ROOT.DS.'images/stories/virtuemart/product/');
		$var = $this->select_files(JPATH_ROOT.DS.'images/stories/virtuemart/product/resized/');
		$resized_files = array_combine($var, array_map([$this,"get_product_path"],$var));
		$a = 1;
		foreach($product_files as $value){
			$value = 'images/stories/virtuemart/product/'.$value;
			if($a == 1 ) $select_from_product = "SELECT file_url FROM( SELECT '$value' as file_url";
			else $select_from_product .="
	UNION SELECT '$value' as file_url";
			$a++;
		}
		$select_from_product.=")x 
		LEFT JOIN #__virtuemart_medias m ON m.file_url = x.file_url AND m.file_type = 'product'
		WHERE 
			m.virtuemart_product_id IS NULL";
			// NOT EXISTS(SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_type = 'product' AND file_url = x.file_url)";
		$a = 1;
		foreach($resized_files as $key=>$value){
			$value = 'images/stories/virtuemart/product/'.$value;
			$key = 'images/stories/virtuemart/product/resized/'.$key;
			if($a == 1) $select_from_resized = 
			" UNION SELECT file_url_thumb as file_url FROM( 
	SELECT '$key' as file_url_thumb, '$value' as file_url";
			else $select_from_resized.="
	UNION SELECT '$key' as file_url_thumb, '$value' as file_url";
			$a++;
		}
		$select_from_resized .=") y
	LEFT JOIN #__virtuemart_medias m ON m.file_type = 'product' AND (m.file_url = y.file_url OR m.file_url_thumb = y.file_url_thumb)
	WHERE 
		m.virtuemart_media_id IS NULL";
		//NOT EXISTS(SELECT virtuemart_media_id FROM #__virtuemart_medias WHERE file_type = 'product' AND (file_url = y.file_url OR file_url_thumb = y.file_url_thumb))";
		$union_select = $select_from_product.$select_from_resized;
		return $union_select;
	}

	public function getFSAllFiles(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(TRUE);
        $query=$this->getListQuery();
		$db->setQuery($query);
        return $db->loadAssocList();
    }
*/
}
?>