<?php
/**
 *  globalsComponent.php 公共函数库
 *
 * @copyright			(C) 2011-2012 	BLINNO
 * @license				http://www.blinno.cn
 * @lastmodify			2012-10-19
 */
class globalsComponent extends Component {
/**
 *文档分类
 * @param $string
 * @return mixed
 */
	public function cate(){
		return  $sal = array(
					1=>'安装问题',
					2=>'登录问题',
					3=>'常见问题'
					//4=>'运行环境'
				);
				

	}
	
/**
 *软件分类
 * @param $string
 * @return mixed
 */
	public function soft(){
		return  $sal = array(
					4=>'通用版',
					5=>'专用版'
					
				);
	}
	
	public function soft1(){
		return  $sal = array(
					1=>'通用版',
					2=>'专用版',
					3=>'运行环境'
				);
	}
	
	/**
 *文档归类
 * @param $string
 * @return mixed
 */
	public function doc(){
		return  $sal = array(
					1=>'操作手册',
					2=>'常见问题'
					
				);
	}
}
?>