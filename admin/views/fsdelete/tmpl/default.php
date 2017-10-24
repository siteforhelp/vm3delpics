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
<form action="index.php?option=com_vm3delpics&view=fsdelete" method="POST" id="adminForm" name="adminForm">
	<table class="table table-striped table-hover" id="fsscanres">
		<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_VM3DELPICS_NUM'); ?></th>
				<th width="5%"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th width="90%"><?php echo JText::_('COM_VM3DELPICS_IMG_URL'); ?></th>
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
							<td><?php echo JHtml::_('grid.id', $i, $row->file_url); ?>
							</td>
							<td><?php echo $row->file_url; ?>
							</td>
						</tr>
					<?php
					}
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">
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