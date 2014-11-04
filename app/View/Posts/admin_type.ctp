<?php
	echo $this->Form->input('dcate_id',
						array(
						'type'	=> 'select',
						'label' => false,
						'div' => false,
						'class' => false,
						'empty' => '——请选择——',
						'options' => $doclist
					)	
				);
?>
