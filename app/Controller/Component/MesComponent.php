<?php
class MesComponent extends Component {

	public function Getmenus(){
		$menus	= array(
			1 => array('lab'=>'软件下载','url'=>''),
			2 => array('lab'=>'操作手册','url'=>''),
			3 => array('lab'=>'常见问题','url'=>''),
			4 => array('lab'=>'后台管理','url'=>'')
		);
		return  $menus;
	}
	public function Getlist(){
		$menus	= array(
				1=>array(
					3=>array('lab'=>'通用版','url'=>'/Proitems/admin'),
					4=>array('lab'=>'专用版','url'=>'/Procates/admin'),
					5=>array('lab'=>'运行环境','url'=>'/Prostatuses/admin'),
					6=>array('lab'=>'证书下载','url'=>'/Prostatuses/admin')
				),
				2 => array(
					3=>array('lab'=>'通用版','url'=>'/Proitems/admin'),
					4=>array('lab'=>'专用版','url'=>'/Procates/admin'),
					5=>array('lab'=>'运行环境','url'=>'/Prostatuses/admin')
				),
				3 => array(	
					1=>array('lab'=>'安装问题','url'=>'/Prologs/index'),
					2=>array('lab'=>'登录问题','url'=>'/admin/Prologs/index'),
					13=>array('lab'=>'常见问题','url'=>'/Myproitems/admin')
				)
		);
		return  $menus;
	}
}
?>