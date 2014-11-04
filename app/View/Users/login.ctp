<!--div class="users form">
<?php
           // echo $this->Form->create('User', array('action' => 'login'));
            //echo $this->Form->inputs(array(
               // 'legend' => __('Login'),
               // 'username',
                //'password'
           // ));
            //echo $this->Form->end('Login');

?>	
</div-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>登录页</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->script(array('jquery','jquery-ui-tabs','dataTables','ZeroClipboard','zebra_datepicker'));
		echo $this->Html->css('style');

	?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#alias").focus();
	 });
	 function jc()
		{	var alias = jQuery("#username").val();
			var pwd = jQuery("#password").val();
			if(alias == ""){
				alert("请输入用户名!");
				jQuery("#alias").focus();
				return false;
			}
			if(pwd == ""){
				alert("请输入密码!");
				jQuery("#pwd").focus();
				return false;
			}
			return true;
		}
	

</script>
</head>

<body>
<div class="login">
	<div class="login_content">
        <div class="login_top">
            <?php echo Configure::read('website_name')?>
        </div>
	<div class="login_center">
        </div>
    	<div class="login_bottom">
            <div class="login_left">
                <img src="/img/login_img.png" />
            </div>
            <div class="login_right">
                <h4>用户登录</h4>
		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php echo $this->Form->create('User',array('div'=>false,'name'=>'form1','action'=>'login')); ?>
                   <tr>
                        <td class="logo_center_1">用户名：</td>
                        <td class="logo_center_2"><?php echo $this->Form->input('username', array('label'=>false,'id'=>'username','div'=>false, 'style'=>'width:120px'))?></td>
                    </tr>
                    <tr>
                        <td class="logo_center_1">密&nbsp;&nbsp;码：</td>
                        <td class="logo_center_2"><?php echo $this->Form->input('password', array('label'=>false,'id'=>'password','div'=>false, 'style'=>'width:120px'))?></td>
                    </tr>
                  
                    <tr>
                        <td class="logo_center_1"></td>
                        <td class="logo_center_2"><input type="submit"  id="login" value="登 录" /></td>
                    </tr>
					<?php
					  /* echo $this->Form->create('User', array('action' => 'login'));
					   echo $this->Form->inputs(array(
							'legend' => __('登录'),
							'username',
							'password'
						));
						echo $this->Form->end('登录');*/
					?>	

                </table>
                <!--div class="login_right_bottom">版权所有：北京科技股份有限公司</div-->
                
            </div>
    	</div>
	</div>
</div>
</body>
</html>
