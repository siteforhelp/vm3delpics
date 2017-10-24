<?php 
defined('_JEXEC') or die('Restricted access');

class com_vm3delpicsInstallerScript{
	 public function preflight($type, $parent){		
		$db = JFactory::getDbo();
        $query = $db->getQuery(TRUE);
        $query="SHOW INDEXES FROM #__virtuemart_product_medias WHERE column_name = 'virtuemart_media_id' AND Seq_in_index = 1";
		$db->setQuery($query);
        if (!$db->loadAssocList()){
			$query = "ALTER TABLE #__virtuemart_product_medias ADD INDEX idx_vm_product_medias_media_id(`virtuemart_media_id`)";
			$db->setQuery($query);
			$db->execute();
			return 1;
		}
		return 0;
	}
}
?>
