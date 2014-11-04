<div class="arosAcos view">
<h2><?php echo __('Aros Aco'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($arosAco['ArosAco']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Aro'); ?></dt>
		<dd>
			<?php echo $this->Html->link($arosAco['Aro']['id'], array('controller' => 'aros', 'action' => 'view', $arosAco['Aro']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Aco'); ?></dt>
		<dd>
			<?php echo $this->Html->link($arosAco['Aco']['id'], array('controller' => 'acos', 'action' => 'view', $arosAco['Aco']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Create'); ?></dt>
		<dd>
			<?php echo h($arosAco['ArosAco']['_create']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Read'); ?></dt>
		<dd>
			<?php echo h($arosAco['ArosAco']['_read']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Update'); ?></dt>
		<dd>
			<?php echo h($arosAco['ArosAco']['_update']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Delete'); ?></dt>
		<dd>
			<?php echo h($arosAco['ArosAco']['_delete']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Aros Aco'), array('action' => 'edit', $arosAco['ArosAco']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Aros Aco'), array('action' => 'delete', $arosAco['ArosAco']['id']), array(), __('Are you sure you want to delete # %s?', $arosAco['ArosAco']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Aros Acos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Aros Aco'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Aros'), array('controller' => 'aros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Aro'), array('controller' => 'aros', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Acos'), array('controller' => 'acos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Aco'), array('controller' => 'acos', 'action' => 'add')); ?> </li>
	</ul>
</div>
