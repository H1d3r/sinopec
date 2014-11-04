<div class="softwares form">
<?php echo $this->Form->create('Software'); ?>
	<fieldset>
		<legend><?php echo __('Edit Software'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('parent_id');
		echo $this->Form->input('name');
		echo $this->Form->input('version');
		echo $this->Form->input('type');
		echo $this->Form->input('systems');
		echo $this->Form->input('attachment_id');
		echo $this->Form->input('body');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Software.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Software.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Softwares'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Softwares'), array('controller' => 'softwares', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Software'), array('controller' => 'softwares', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attachments'), array('controller' => 'attachments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attachment'), array('controller' => 'attachments', 'action' => 'add')); ?> </li>
	</ul>
</div>
