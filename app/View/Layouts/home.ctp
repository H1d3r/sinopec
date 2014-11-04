<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'ZenCMS');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=10" />  
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style');
		echo $this->Html->css('sinopec');
		echo $this->Html->css('demo_page');
		echo $this->Html->css('demo_table_jui');
		echo $this->Html->css('demo_pages');
		echo $this->Html->css('TableTools_JUI');
		
		echo $this->Html->script('jquery');
		echo $this->Html->script('dataTables');
		echo $this->Html->script('ZeroClipboard');
		echo $this->Html->script('TableTools');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>

<div class="content">
    <div class="header">
            <div class="logo"> 
                <span class="logo_left"><img src="/img/logo.png" /></span> 
                <span class="logo_center">无纸化会议自助使用网站<?php //print($controller);?></span> 
                <span class="logo_right">
				
				<?php 
                                                    
					if ( $this->session->read( 'Auth.User' ) ) {
						 echo __('当前用户') .': '.$this->Html->link($this->session->read( 'Auth.User.username' ) ,'#'). "&nbsp;&nbsp;"; 
// /Users/view/'.$this->session->read( 'Auth.User.id' ) pr($this->session->read( 'Auth.User.username' ));
						echo $this->Html->link( __( '退出', true ), '/Users/logout' );
					}
					else {
						echo $this->Html->link( __( '登录', true ), '/Users/login' );
					}
				?>    
				
				
				</span> 
            </div>
	</div>
	<div class="nav">
		<ul>
		  <!--<li style="background:url(img/nav_bj.jpg)"><a href="download.html">软件下载</a></li>
			<li><a href="download.html">操作手册</a></li>
			<li ><a href="index.html">常见问题</a></li>
			
			<li-->
				<!--a href="admin.html">后台管理</a-->
				<?php
					echo $this->MenuBuilder->build('main-menu');
				?>
			<?php 
			
					
				                           
					//if ( $this->session->read( 'Auth.User' ) ) {
						// echo $this->Html->link(__('后台管理') ,'#'). "&nbsp;&nbsp;"; 
					//}
					
				?>  	
			</li>
            
		</ul>
	</div>
	<div class="main">
			<!-- <div class="left" >
			<?php //echo $this->MenuBuilder->build('left-menu'); ?>
					
			</div> -->
			<div class="right2">
                      <?php 
								// pr($this->params);
								 //pr($controller);
								echo $this->Session->flash('auth'); //访问权限提示
						?>
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
            </div>
        </div>
	<!-- <div class="footer">Copyright&copy;2013 北京中石化科技股份有限公司</div> -->

	
</div>
<?php //echo $this->element('sql_dump'); ?>
<script type="text/javascript" charset="utf-8">
$(function () {

        var oTable = $('#example').dataTable({
//          "sLengthMenu":"每页显示 _MENU_ 条记录",
            "iDisplayLength": 20,//每页默认显示数量
            "aLengthMenu": [[20, 25, 50, -1], [20, 25, 50, "All"]],//下拉框
            "bJQueryUI": true,
             "aaSorting": [[0,'desc']],
            "sPaginationType": "full_numbers"//分页样式
        });	



});
</script>

</body>
</html>
