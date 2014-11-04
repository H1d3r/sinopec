<div class="softwares index">
	<h2><?php echo __('Softwares'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('version'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('systems'); ?></th>
			<th><?php echo $this->Paginator->sort('attachment_id'); ?></th>
			<th><?php echo $this->Paginator->sort('body'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($softwares as $software): ?>
	<tr>
		<td><?php echo h($software['Software']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($software['ParentSoftware']['name'], array('controller' => 'softwares', 'action' => 'view', $software['ParentSoftware']['id'])); ?>
		</td>
		<td><?php echo h($software['Software']['name']); ?>&nbsp;</td>
		<td><?php echo h($software['Software']['version']); ?>&nbsp;</td>
		<td><?php echo h($software['Software']['type']); ?>&nbsp;</td>
		<td><?php echo h($software['Software']['systems']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($software['Attachment']['name'], array('controller' => 'attachments', 'action' => 'view', $software['Attachment']['id'])); ?>
		</td>
		<td><?php echo h($software['Software']['body']); ?>&nbsp;</td>
		<td><?php echo h($software['Software']['created']); ?>&nbsp;</td>
		<td><?php echo h($software['Software']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $software['Software']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $software['Software']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $software['Software']['id']), array(), __('Are you sure you want to delete # %s?', $software['Software']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Software'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Softwares'), array('controller' => 'softwares', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Software'), array('controller' => 'softwares', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachments'), array('controller' => 'attachments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment'), array('controller' => 'attachments', 'action' => 'add')); ?> </li>
	</ul>
</div>
