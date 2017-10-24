<?php
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 
defined("_JEXEC") or die();

class Vm3delpicsViewDBDelete extends JViewLegacy{
	protected $items;
	protected $pagination;
	
	public function display($tpl = null){
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->addToolBar();
		parent::display($tpl);
		$this->setDocument();
	}
	
	protected function addToolbar(){
		JToolBarHelper::title(JText::_("COM_VM3DELPICS_ADMIN_DBDELETE_TITLE"));
		JToolBarHelper::deleteList(JText::_("JGLOBAL_CONFIRM_DELETE"),'dbdelete.deleteDBSelected');
		JToolBarHelper::custom('dbdelete.deleteDBAllImgs', 'deletes', 'delete-hover', JText::_("COM_VM3DELPICS_ADMIN_DELETE_ALL_BUTTON"), false);
		JToolBarHelper::help('',false,'https://joomlaforum.ru/index.php?action=profile;u=128057');
		//JToolBarHelper::preferences('com_vm3delpics');
	}
	
	protected function setDocument(){
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root(TRUE).'/administrator/components/com_vm3delpics/views/fsdelete/vm3delpics_fsdelete.css');		
	}
}
?>