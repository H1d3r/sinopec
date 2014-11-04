<?php

    echo $this->Html->css('jquery.treegrid');
?>
<div class="categories index">
	<h2><?php echo __('Categories'); ?></h2>
<!--<table class="tree">
        <tr class="treegrid-1">
          <td>Root node</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-2 treegrid-parent-1">
          <td>Node 1-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-3 treegrid-parent-1">
          <td>Node 1-2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-4 treegrid-parent-3">
          <td>Node 1-2-1</td><td>Additional info</td>
        </tr>
      </table>	  	  -->
	<table cellpadding="0" cellspacing="0" class="tree">
	<thead>
	<tr >
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
			<th><?php echo $this->Paginator->sort('lft'); ?></th>
			<th><?php echo $this->Paginator->sort('rght'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $n=1;  foreach ($categories as $category): ?>
	<tr class="treegrid-<?php echo$category['Category']['id']; if($category['Category']['parent_id'] !=0) echo "  treegrid-parent-".$category['Category']['parent_id'] ;?> "> 
		<td><?php echo h($category['Category']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $category['Category']['parent_id'] , $this->Html->link($category['ParentCategory']['name'], array('controller' => 'categories', 'action' => 'view', $category['ParentCategory']['id'])); ?>
		</td>
		<td><?php echo h($category['Category']['lft']); ?>&nbsp;</td>
		<td><?php echo h($category['Category']['rght']); ?>&nbsp;</td>
		<td><?php echo h($category['Category']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $category['Category']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $category['Category']['id']), array(), __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?>
		</td>
	</tr>
<?php $n++; endforeach; ?>
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
		<li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php
    echo $this->Html->script('jquery.treegrid');
?>


  <script type="text/javascript">
      $(document).ready(function() {
        $('.tree').treegrid();
      });
    </script>