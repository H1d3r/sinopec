<?php

    echo $this->Html->css('bootstrap-3.0.0/css/bootstrap');
    echo $this->Html->css('jquery.treegrid');
?>
<div class="categories index">
	<h2><?php echo __('Control Panel'); ?></h2>
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
			<th><?php // echo $this->Paginator->sort('id'); ?>&nbsp;1</th>
			<!--<th><?php // echo $this->Paginator->sort('parent_id'); ?>&nbsp;2</th>-->
<!--			<th><?php // echo $this->Paginator->sort('lft'); ?>&nbsp;</th>
			<th><?php // echo $this->Paginator->sort('rght'); ?>&nbsp;</th>-->
			<!--<th><?php // echo $this->Paginator->sort('alias'); ?>&nbsp;3</th>-->
			<!--<th class="actions">-->
			
                                                            <?php 
                                                                        foreach ($roles as $key =>$role){
                                                                            echo ' <th >' .$role ." &nbsp; &nbsp; </th>";
                                                                        } 
                                                            ?>
                        
                                                           
	</tr>
	</thead>
	<tbody>
	<?php $n=1;  foreach ($acos as $aco):  //pr($aco);?>
	<tr class="treegrid-<?php echo $aco['Aco']['id']; if($aco['Aco']['parent_id'] !=0) echo "  treegrid-parent-".$aco['Aco']['parent_id'] ;?> "> 
		<td><?php echo $aco['Aco']['id'] , h($aco['Aco']['alias']); ?>&nbsp;</td>
<!--		<td>
			<?php // echo $aco['Aco']['parent_id'] ;//, $this->Html->link($aco['ParentAco']['name'], array('controller' => 'categories', 'action' => 'view', $aco['ParentAco']['id'])); ?>
		</td>-->
		<!--<td><?php echo h($aco['Aco']['lft']); ?>&nbsp;</td>-->
		<!--<td><?php echo h($aco['Aco']['rght']); ?>&nbsp;</td>-->
		
		<!--<td class="actions">-->
                                        <?php if($aco['Aco']['parent_id'] ==1 || $aco['Aco']['parent_id'] ==0){
//                                            foreach ($roles as $key =>$role){
//                                                                            echo ' <td >' .$role ." &nbsp; &nbsp; </td>";
//                                                                        } 
                                            ?>
                                         <td>&nbsp</td> 
                                         <td>&nbsp</td> 
                                         <td>&nbsp</td> 
                                         <?php
//                                                                        foreach ($roles as $key =>$role){
//                                                                            echo ' <th >' .$key ." &nbsp; &nbsp; </th>";
////                                                                        } 
                                        }else{                ?>    
                                        
                                         <?php ?>
                                        <td>&nbsp           <span class="glyphicon glyphicon-ok"></span></td> 
                                        <td>&nbsp            <span class="glyphicon glyphicon-remove"></span></td> 
                                         <td>&nbsp           <span class="glyphicon glyphicon-remove"></span></td> 
			<?php //  } // echo $this->Html->link(__('View'), array('action' => 'view', $aco['Aco']['id'])); ?>
                                        <?php  }// echo $this->Html->link(__('Edit'), array('action' => 'edit', $aco['Aco']['id'])); ?>
			<?php // echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $aco['Aco']['id']), array(), __('Are you sure you want to delete # %s?', $aco['Aco']['id'])); ?>
		<!--</td>-->
	</tr>
<?php $n++; endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
//	echo $this->Paginator->counter(array(
//	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
//	));
	?>	</p>
	<div class="paging">
	<?php
//		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
//		echo $this->Paginator->numbers(array('separator' => ''));
//		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Aco'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Aco'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php


    echo $this->Html->script('jquery.treegrid');
    echo $this->Html->script('jquery.treegrid.bootstrap3');
?>


  <script type="text/javascript">
      $(document).ready(function() {
        $('.tree').treegrid();
      });
    </script>