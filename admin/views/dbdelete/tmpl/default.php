<?php
/**
 * @author     Boris Garkoun <borro@inbox.ru>
 * @date       24.10.17
 *
 * @copyright  Copyright (C) 2017 - 2017 https://forum.joomla.org/memberlist.php?mode=viewprofile&u=574731 . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 
defined("_JEXEC") or die();
?>
<form action="index.php?option=com_vm3delpics&view=dbdelete" method="POST" id="adminForm" name="adminForm">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="1%"><?php echo JText::_('COM_VM3DELPICS_NUM'); ?></th>
				<th width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th width="42%"><?php echo JText::_('COM_VM3DELPICS_IMG_TITLE'); ?></th>
				<th width="42%"><?php echo JText::_('COM_VM3DELPICS_IMG_URL'); ?></th>
				<th width="6%"><?php echo JText::_('COM_VM3DELPICS_IMG_PUBLISHED'); ?></th>
				<th width="7%"><?php echo JText::_('COM_VM3DELPICS_IMG_ID'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				if(!empty($this->items)){
					foreach($this->items as $i=>$row){
						?>
						<tr>
							<td><?php echo $this->pagination->getRowOffset($i); ?>
							</td>
							<td><?php echo JHtml::_('grid.id', $i, $row->virtuemart_media_id); ?>
							</td>
							<td><?php echo $row->file_title; ?>
							</td>
							<td><?php echo $row->file_url; ?>
							</td>
							<td><?php echo JHtml::_('jgrid.published',$row->published, $i,'delete.', false); ?>
							</td>
							<td><?php echo $row->virtuemart_media_id; ?>
							</td>
						</tr>
					<?php
					}
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6"> 
					<div style="float:left;"><?php echo $this->pagination->getListFooter();?></div>
					<div style="float:right;"><?php echo $this->pagination->getLimitBox();?></div>
					<div style="clear:both;"></div>
				</td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo JHtml::_('form.token'); ?>
</form>