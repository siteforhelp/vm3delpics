<?php
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined("_JEXEC") or die();

class Vm3delpicsViewFSDelete extends JViewLegacy{
	protected $items;
	protected $pagination;
	
	public function display($tpl = null){
		$this->items = $this->get('Items');//список файлов
		$input = JFactory::getApplication()->input;
		$itemsCount = count($this->items); //полное количество записей
		//$itemsOnPage = 10; //сколько записей выводится на странице
		$this->pagination = $this->get('Pagination');
		//$this->pagination = new JPagination( $itemsCount, $input->getInt( 'start', 0 ), $itemsOnPage );
		$this->addToolBar();
		parent::display($tpl);
        $this->setDocument();		
	}
	
	protected function addToolbar(){
		JToolBarHelper::title(JText::_("COM_VM3DELPICS_ADMIN_FSDELETE_TITLE"));
		JToolBarHelper::deleteList(JText::_("COM_VM3DELPICS_ADMIN_DELETE_SELECTED_Q"),'fsdelete.deleteFSSelected', JText::_("COM_VM3DELPICS_ADMIN_DELETE_SELECTED"));
		JToolBarHelper::custom('fsdelete.deleteFSAllFiles', 'deletes', 'delete-hover', JText::_("COM_VM3DELPICS_ADMIN_DELETE_ALL_FS_BUTTON"), false);
		JToolBarHelper::help('',false,'https://joomlaforum.ru/index.php?action=profile;u=128057');
		//JToolBarHelper::preferences('com_vm3delpics');
	}
    protected function setDocument(){
		//JHTML::_('behavior.modal');
        $document = JFactory::getDocument();
        $document->addScript(JUri::root(TRUE).'/administrator/components/com_vm3delpics/views/fsdelete/vm3delpics_fsdelete.js');
		$document->addStyleSheet(JUri::root(TRUE).'/administrator/components/com_vm3delpics/views/fsdelete/vm3delpics_fsdelete.css');		
		$constants = array();
		$constants['slow_responce'] = JText::_("COM_VM3DELPICS_ADMIN_RESPONCE_TOO_SLOW");
		$constants['delete_selected'] = JText::_("COM_VM3DELPICS_ADMIN_DELETE_SELECTED_Q");
		$constants['sure_all'] = JText::_("COM_VM3DELPICS_ADMIN_SURE_ALL");
		$document->addScriptOptions('com_vm3delpics', $constants);		
    }
}
	
?>