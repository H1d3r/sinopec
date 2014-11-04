<h2>Register your account</h2>
  
<form method="POST" action="<?=$this->here; ?>">
  
                    <?php
                                echo $this->Form->input('username');
                                echo $this->Form->input('password');
                                echo $this->Form->input('group_id');
	?>
  
<?php echo $this->Form->end(__('Register')); ?>
</form>