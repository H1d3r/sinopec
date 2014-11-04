<div class="softwares view">
<h2><?php echo __('Software'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($software['Software']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parent Software'); ?></dt>
		<dd>
			<?php echo $this->Html->link($software['ParentSoftware']['name'], array('controller' => 'softwares', 'action' => 'view', $software['ParentSoftware']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($software['Software']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Version'); ?></dt>
		<dd>
			<?php echo h($software['Software']['version']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($software['Software']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Systems'); ?></dt>
		<dd>
			<?php echo h($software['Software']['systems']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Attachment'); ?></dt>
		<dd>
			<?php echo $this->Html->link($software['Attachment']['name'], array('controller' => 'attachments', 'action' => 'view', $software['Attachment']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Body'); ?></dt>
		<dd>
			<?php echo h($software['Software']['body']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($software['Software']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($software['Software']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Software'), array('action' => 'edit', $software['Software']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Software'), array('action' => 'delete', $software['Software']['id']), array(), __('Are you sure you want to delete # %s?', $software['Software']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Softwares'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Software'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Softwares'), array('controller' => 'softwares', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Software'), array('controller' => 'softwares', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachments'), array('controller' => 'attachments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment'), array('controller' => 'attachments', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Softwares'); ?></h3>
	<?php if (!empty($software['ChildSoftware'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Version'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Systems'); ?></th>
		<th><?php echo __('Attachment Id'); ?></th>
		<th><?php echo __('Body'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($software['ChildSoftware'] as $childSoftware): ?>
		<tr>
			<td><?php echo $childSoftware['id']; ?></td>
			<td><?php echo $childSoftware['parent_id']; ?></td>
			<td><?php echo $childSoftware['name']; ?></td>
			<td><?php echo $childSoftware['version']; ?></td>
			<td><?php echo $childSoftware['type']; ?></td>
			<td><?php echo $childSoftware['systems']; ?></td>
			<td><?php echo $childSoftware['attachment_id']; ?></td>
			<td><?php echo $childSoftware['body']; ?></td>
			<td><?php echo $childSoftware['created']; ?></td>
			<td><?php echo $childSoftware['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'softwares', 'action' => 'view', $childSoftware['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'softwares', 'action' => 'edit', $childSoftware['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'softwares', 'action' => 'delete', $childSoftware['id']), array(), __('Are you sure you want to delete # %s?', $childSoftware['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Child Software'), array('controller' => 'softwares', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
