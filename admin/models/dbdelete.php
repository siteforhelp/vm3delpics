<?php
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 
defined("_JEXEC") or die();

class Vm3delpicsModelDBDelete extends JModelList {

	protected function getListQuery(){//то, что отображается в виде по умолчанию
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('file_title,file_url,published,virtuemart_media_id');
		$query->from('#__virtuemart_medias');
		$query->where('virtuemart_media_id NOT IN(SELECT virtuemart_media_id FROM #__virtuemart_product_medias) AND file_type = \'product\'');
		return $query;
	}

	public function getDBSelectedFiles(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$ids = JFactory::getApplication()->input->post->get('cid');
		$conditions = 'virtuemart_media_id IN ('.implode(',', $ids).')';
		$query->select('file_url, file_url_thumb');
		$query->from($db->quoteName('#__virtuemart_medias'));
		$query->where($conditions);
		$db->setQuery($query);
        return $db->loadAssocList();
	}

    public function getDBAllFiles(){
		$db = JFactory::getDbo();
		$query = 'SELECT virtuemart_media_id, file_url, file_url_thumb
			FROM `#__virtuemart_medias`
			WHERE file_type="product"';
		$db->setQuery($query);
		$items = $db->loadObjectList('virtuemart_media_id');
		$query = 'SELECT virtuemart_media_id
			FROM `#__virtuemart_product_medias`';
		$db->setQuery($query);
		$ids = $db->loadObjectList('virtuemart_media_id');
		foreach ($items as $k=>$v){
			if (isset($ids[$k])) {
				unset($items[$k]);
			}
		}
		return $items;
    }

	public function deleteDBSelected(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$ids = JFactory::getApplication()->input->post->get('cid');
		$conditions = 'virtuemart_media_id IN ('.implode(',', $ids).')';
		$query->delete($db->quoteName('#__virtuemart_medias'));
		$query->where($conditions);
		$db->setQuery($query);
		return $db->execute();
	}

    public function deleteDBAllImgs($files){
		$db = JFactory::getDbo();
		$ids = array_keys($files);
        $db->setQuery("DELETE m FROM `#__virtuemart_medias` AS `m` 
		WHERE m.virtuemart_media_id IN (".implode(',', $ids).')');
        return $db->execute();
	}
}
?>