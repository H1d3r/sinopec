<?php
App::uses('AppController', 'Controller');
/**
 * Softwares Controller
 *
 * @property Software $Software
 * @property PaginatorComponent $Paginator
 */
class SoftwaresController extends AppController {


/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','globals');
	public $uses =array('Post','Attachment','Software'); //使用的模型

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Software->recursive = 0;
		$this->set('softwares', $this->Paginator->paginate());
	}

	//程序管理首页
	public function admin_index() {
		$relist=$this->Software->find("all");
		$slist = $this->globals->soft1();
		//pr($Postlist);
		//pr($relist);
		 $this->set(compact('relist','slist'));
		
	}
	//程序页面里的普通软件添加
	public function admin_add() {
		$softlist = $this->globals->soft1();
		$lists = $this->Software->find('list',array(
					'conditions'=>array('Software.parent_id'=>0,'Software.ios'=>0),
					'recursive'=>-1
				)); 
				
				//pr($lists);
		//$users = $this->Post->User->find('list');
		$this->set(compact('softlist','lists'));
	}
	
	
	//程序页面里的ios软件添加
	public function admin_addios() {
		$softlist = $this->globals->soft1();
		$lists = $this->Software->find('list',array(
					'conditions'=>array('Software.parent_id'=>0,'Software.ios'=>1),
					'recursive'=>-1
				)); 
				
				//pr($lists);
		//$users = $this->Post->User->find('list');
		$this->set(compact('softlist','lists'));
	}
	
	
	//程序页面里ajax
	public function ajax() {
	      $this->layout='ajax';
	      $data=$this->request->data;
	       $lists = $this->Software->find('all',array(
	           'conditions'=>array('Software.id'=>$data['id']),
	           'recursive'=>-1
	       ));
	       $type=$lists[0]['Software']['type'];
	       echo $type;
	      die;
	}
	//程序管理的普通软件修改页面
	public function admin_edit($id = null) {
		$sql="SELECT
				softwares.id,
				softwares.parent_id,
				softwares.`name`,
				softwares.version,
				softwares.type,
				softwares.systems,
				softwares.attachment_id,
				softwares.body,
				softwares.created,
				softwares.modified,
				softwares.size
				FROM
				softwares where softwares.id=$id";
		$Softlist =$this->Software->query($sql);
		$parent_id=$Softlist[0]['softwares']['parent_id'];
		//pr($parent_id);
		$sql1="SELECT
				softwares.id,
				softwares.parent_id,
				softwares.`name`,
				softwares.version,
				softwares.type,
				softwares.systems,
				softwares.attachment_id,
				softwares.body,
				softwares.created,
				softwares.modified,
				softwares.size
				FROM
				softwares where softwares.id=$parent_id";
		$Softlist1 =$this->Software->query($sql1);
		$lists = $this->Software->find('list',array(
					'conditions'=>array('Software.parent_id'=>0),
					'recursive'=>-1
		)); 
		// pr($Post);
		$soft = $this->globals->soft1();
		//pr($Softlist);
		$this->set(compact('Softlist','id','soft','lists','Softlist1'));
	}
	
	//程序管理的ios软件修改页面
	public function admin_iosedit($id = null) {
		$sql="SELECT
				softwares.id,
				softwares.parent_id,
				softwares.`name`,
				softwares.version,
				softwares.type,
				softwares.systems,
				softwares.attachment_id,
				softwares.body,
				softwares.created,
				softwares.modified,
				softwares.size
				FROM
				softwares where softwares.id=$id";
		$Softlist =$this->Software->query($sql);
		$parent_id=$Softlist[0]['softwares']['parent_id'];
		//pr($parent_id);
		$sql1="SELECT
				softwares.id,
				softwares.parent_id,
				softwares.`name`,
				softwares.version,
				softwares.type,
				softwares.systems,
				softwares.attachment_id,
				softwares.body,
				softwares.created,
				softwares.modified,
				softwares.size
				FROM
				softwares where softwares.id=$parent_id";
		$Softlist1 =$this->Software->query($sql1);
		$lists = $this->Software->find('list',array(
					'conditions'=>array('Software.parent_id'=>0),
					'recursive'=>-1
		)); 
		// pr($Softlist);
		$soft = $this->globals->soft1();
		 //pr($id);
		$this->set(compact('Softlist','id','soft','lists','Softlist1'));
	}
	//添加数据库的操作
	public function admin_pmsave() {
		$this->autoRender = false;
		$user = $this->Auth->user();
		$data=$this->request->data;
		if(!isset($this->data['isCheck']) || $data['Software']['psoft_id']==""){
			$data['Software']['psoft_id']=0;
		}else{
			$data['Software']['psoft_id']=$data['Software']['psoft_id'];
			$type = $this->Software->find('first',array(
				'conditions'=>array('id'=>$data['Software']['psoft_id']),
				'recursive'=>-1
				));
			// pr($type);
			$data['Software']['soft_id'] = $type['Software']['type'];
		}
		$img=$data['Software']['images_url'][0];
		//pr($img);die;
		//文件名称
		$name=$img['name'];
		$type=$img['type'];
		$size=$img['size']/(1024*1024);
		$size1=round($size,1);
		$size2=$size1."MB";
		$title=$data['name'];
		if(isset($data['id'])){
			$sql="SELECT
				softwares.`name`
				FROM
				softwares where softwares.`name`='$title' and softwares.id <>".$data['id'];
		}else{
			$sql="SELECT
				softwares.`name`
				FROM
				softwares where softwares.`name`='$title'";
			}
		$Soft = $this->Software->query($sql);
	
		if(!empty($Soft)) {
		
				//echo 1111111111111;
				?>
					<script type="text/javascript" charset="utf-8">
					alert('程序名已存在');
					window.history.go(-1);
					</script>
				<?php
				die;
			}
			
	//添加ios软件到数据库开始位置
		if(isset($data['Software']['typeipa']) ==1){
			//pr($data);die;
				$img=$data['Software']['images_url'][0];
				//pr($img);die;
				//文件名称
				$name=$img['name'];
				$type=$img['type'];
				$size=$img['size']/(1024*1024);
				$size1=round($size,1);
				$size2=$size1."MB";
				$title=$data['name'];
					//修改ios软件的操作并且img['name']为空
				if(isset($data['id']) && $img['name']==""){
					$pbody = array(
								'Software'=>array(
									'id'=>$data['id'],
									//'parent_id'=>$data['Software']['psoft_id'],
									'name'=>$data['name'],
									'version'=>$data['version'],
									'type'=>$data['Software']['soft_id'],
									'systems'=>$data['systems'],
									'body'=>$data['body']
									
									//'size'=>$size2
								)
					);
								//pr($pbody);die;
							//$this->Software->create();
					$repbody = $this->Software->save($pbody);		
					if($repbody){
							$this->Session->setFlash(__('操作成功！'));
							$this->redirect('/admin/Softwares/index');	  
					}else{ 
						
							$this->Session->setFlash(__('操作失败！'));
							$this->redirect('/admin/Softwares/iosedit');		 
					}
					die;
				}
				//获取扩展名
				$plist=$data['Software']['plist'];
				$plistname=$plist['name'];
				//临时文件名
				$tmp_name=$img['tmp_name'];
				$ext = substr($name, (strrpos($name, '.') + 1));//找到扩展名
				$ext = strtolower($ext);
				$arr=array('ipa');
				//获取关于plist的文件
				$ptmp_name=$plist['tmp_name'];
				$plistext = substr($plistname, (strrpos($plistname, '.') + 1));//找到扩展名
				$plistext = strtolower($plistext);
				$plistarr=array('plist');
				$name1=explode(".",$name);
				//去掉后缀名的名称
				$name2=$name1[0];
				$userfiles =  WWW_ROOT."files".DS."userfiles"; 
				$n_dir = $userfiles.DS.h($name);
				$n_dir1 = $userfiles.DS.h($plistname);
				//如果是没有id的话就走if
				$_ids = isset($data['id']) ? $data['id'] :0;
				 if($_ids ==0){
				 echo 222;
						if(!in_array($ext,$arr)){
								?>
									<script type="text/javascript" charset="utf-8">
									alert('上传IPA的文件类型不正确！');
										window.history.go(-1);
									</script>
								<?php			
								die;	
							}
							
						if(!in_array($plistext,$plistarr)){
								?>
									<script type="text/javascript" charset="utf-8">
									alert('上传PLIST的文件类型不正确！');
									window.history.go(-1);
									</script>
								<?php			
								die;	
							}
							$nameAttachment= $this->Attachment->find('all',array(
										'conditions'=>array('Attachment.name'=>$name2),
										'recursive'=>-1
									));
									
							$plistAttachment= $this->Attachment->find('all',array(
										'conditions'=>array('Attachment.plists'=>$plistname),
										'recursive'=>-1
							));
				}else{
					echo 111;
						$plistsoftt= $this->Software->find('all',array(
										'conditions'=>array('Software.id'=>$data['id']),
										'recursive'=>-1
							));
							//pr($plistsoftt);die;
							$nameAttachment= $this->Attachment->find('all',array(
										'conditions'=>array('Attachment.name'=>$name2,'Attachment.id <>'=>$plistsoftt[0]['Software']['attachment_id']),
										'recursive'=>-1
									));
									
							$plistAttachment= $this->Attachment->find('all',array(
										'conditions'=>array('Attachment.plists'=>$plistname,'Attachment.id <>'=>$plistsoftt[0]['Software']['attachment_id']),
										'recursive'=>-1
							));
					
				}
				
				if(empty($nameAttachment) && empty($plistAttachment)){
						
						//上传ipa文件
						$move_file = move_uploaded_file($tmp_name, $n_dir);
						//上传ios文件
						$plistmove_file = move_uploaded_file($ptmp_name, $n_dir1);
						//pr($move_file);die;
					if($move_file || $plistmove_file ){	
						if(isset($data['id'])){
								$id=$data['id'];
								$sql1="SELECT
									softwares.id,
									softwares.parent_id,
									softwares.`name`,
									softwares.version,
									softwares.type,
									softwares.systems,
									softwares.attachment_id,
									softwares.body,
									softwares.created,
									softwares.modified,
									softwares.size
									FROM
									softwares where softwares.id=$id";
								$repost=$this->Software->query($sql1);
								$imgid=$repost[0]['softwares']['attachment_id'];
							
								$Attachment= $this->Attachment->find('all',array(
									'conditions'=>array('Attachment.id'=>$imgid),
									'recursive'=>-1
								));
								//pr($Attachment);die;
								//unlink($userfiles.DS.$Attachment[0]['Attachment']['path']);
								//if($Attachment[0]['Attachment']['type']=='ipa'){
									//unlink($userfiles.DS.$Attachment[0]['Attachment']['plists']);
								//}
								$postbody = array(
									'Attachment'=>array(
											'id'=>$imgid,
											'name'=>$name2,
											'type'=>$ext,
											'path'=>$name,
											'plists'=>$plistname
										)
								);
								//$this->Attachment->create();
								$relt = $this->Attachment->save($postbody);
								//pr($relt);die;
								if($relt){
										$pbody = array(
											'Software'=>array(
												'id'=>$data['id'],
												//'parent_id'=>$data['Software']['psoft_id'],
												'name'=>$data['name'],
												'version'=>$data['version'],
												'type'=>$data['Software']['soft_id'],
												'systems'=>$data['systems'],
												"attachment_id"=>$relt['Attachment']['id'],
												'body'=>$data['body'],
												'size'=>$size2
											)
										);
								}
					
						}else{
							$postbody = array(
									'Attachment'=>array(
										'name'=>$name2,
										'type'=>$ext,
										'path'=>$name,
										'plists'=>$plistname
									)
								);
							$this->Attachment->create();
							$relt = $this->Attachment->save($postbody);
							//pr($relt);die;
							if($relt){
									$pbody = array(
										'Software'=>array(
												'parent_id'=>$data['Software']['psoft_id'],
												'name'=>$data['name'],
												'version'=>$data['version'],
												'type'=>$data['Software']['soft_id'],
												'systems'=>$data['systems'],
												"attachment_id"=>$relt['Attachment']['id'],
												'body'=>$data['body'],
												'size'=>$size2,
												'ios'=>1
											)
										);
							}
									$this->Software->create();
									
						}
				$repbody = $this->Software->save($pbody);
					if($repbody){
							// $this->Session->setFlash('添加成功！');
							$this->Session->setFlash(__('操作成功！'));
							$this->redirect('/admin/Softwares/index', null, false);
							//$this->redirect(array('controller' => 'Softwares', 'action' => 'admin_index'));
					}else{
												$this->Session->setFlash(__('操作失败！'));
											 exit;
											 //$this->redirect('/admin/Softwares/padd', null, false);
					} 
				}
			}else{
				?>
					<script type="text/javascript" charset="utf-8">
					alert('文件名不能重复！');
					window.history.go(-1);
					</script>
				<?php			
				die;	
						
			}
		}
		//添加ios软件到数据库结束位置
		//普通文件的修改的操作并且img['name']为空
		if(isset($data['id']) && $img['name']==""){
				$pbody = array(
							'Software'=>array(
								'id'=>$data['id'],
								//'parent_id'=>$data['Software']['psoft_id'],
								'name'=>$data['name'],
								'version'=>$data['version'],
								'type'=>$data['Software']['soft_id'],
								'systems'=>$data['systems'],
								'body'=>$data['body']
								//'size'=>$size2
							)
					);
								//pr($pbody);die;
							//$this->Software->create();
				$repbody = $this->Software->save($pbody);
							
				if($repbody){
							$this->Session->setFlash(__('操作成功！'));
							// $this->Session->setFlash('操作成功！');
							$this->redirect('/admin/Softwares/index');
								  
								 
								  
				}else{ 
							$this->Session->setFlash(__('操作失败！'));
							//$this->Session->setFlash('操作成功！');
							 $this->redirect('/admin/Softwares/edit');
								 
				}
					die;
		}
			
			//die;
		//临时文件名
		$tmp_name=$img['tmp_name'];
		//获取扩展名
		$ext = substr($name, (strrpos($name, '.') + 1));//找到扩展名
		$ext = strtolower($ext);
		$arr=array('zip','rar','tar','7z','ipa','cer','apk');
		if(!in_array($ext,$arr)){
			?>
					<script type="text/javascript" charset="utf-8">
					alert('文件类型不正确！');
					window.history.go(-1);
					</script>
			<?php			
			die;	
		}
		//把文件名改成时间格式的
		$sFileName = date("YmdHis").rand(100, 200).".".$ext;
		$name1=explode(".",$sFileName);
		//去掉后缀名的名称
		$name2=$name1[0];
		$userfiles =  WWW_ROOT."files".DS."userfiles"; 
		$n_dir = $userfiles.DS.h($sFileName);
		$move_file = move_uploaded_file($tmp_name, $n_dir);
		if($move_file){
				if(isset($data['id'])){
					$id=$data['id'];
					$sql1="SELECT
							softwares.id,
							softwares.parent_id,
							softwares.`name`,
							softwares.version,
							softwares.type,
							softwares.systems,
							softwares.attachment_id,
							softwares.body,
							softwares.created,
							softwares.modified,
							softwares.size
							FROM
							softwares where softwares.id=$id";
						$repost=$this->Software->query($sql1);
						$imgid=$repost[0]['softwares']['attachment_id'];
					
						$Attachment= $this->Attachment->find('all',array(
						    'conditions'=>array('Attachment.id'=>$imgid),
						    'recursive'=>-1
						));
						//pr($Attachment);die;
						unlink($userfiles.DS.$Attachment[0]['Attachment']['path']);
							$postbody = array(
								'Attachment'=>array(
									'id'=>$imgid,
									'name'=>$name2,
									'type'=>$ext,
									'path'=>$sFileName
								)
							);
							$this->Attachment->create();
							$relt = $this->Attachment->save($postbody);
							//pr($relt);die;
							if($relt){
									$pbody = array(
										'Software'=>array(
											'id'=>$data['id'],
											//'parent_id'=>$data['Software']['psoft_id'],
											'name'=>$data['name'],
											'version'=>$data['version'],
											'type'=>$data['Software']['soft_id'],
											'systems'=>$data['systems'],
											"attachment_id"=>$relt['Attachment']['id'],
											'body'=>$data['body'],
											'size'=>$size2
										)
									);
							}
				
				}else{
							$postbody = array(
								'Attachment'=>array(
									'name'=>$name2,
									'type'=>$ext,
									'path'=>$sFileName
								)
							);
							$this->Attachment->create();
							$relt = $this->Attachment->save($postbody);
							//pr($relt);die;
							if($relt){
									$pbody = array(
										'Software'=>array(
											'parent_id'=>$data['Software']['psoft_id'],
											'name'=>$data['name'],
											'version'=>$data['version'],
											'type'=>$data['Software']['soft_id'],
											'systems'=>$data['systems'],
											"attachment_id"=>$relt['Attachment']['id'],
											'body'=>$data['body'],
											'size'=>$size2
										)
									);
							}
							$this->Software->create();
					}
							
							$repbody = $this->Software->save($pbody);
							 if($repbody){
								// $this->Session->setFlash('添加成功！');
								$this->Session->setFlash(__('操作成功！'));
								 $this->redirect('/admin/Softwares/index', null, false);
								 //$this->redirect(array('controller' => 'Softwares', 'action' => 'admin_index'));
									
								  
							}else{
									$this->Session->setFlash(__('操作失败！'));	
								 //$this->Session->setFlash('添加失败！');
								 exit;
								 //$this->redirect('/admin/Softwares/padd', null, false);
							} 
		}
	}
	
	
	
	/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
					$this->Software->id = $id;
					$sql1="SELECT
							softwares.id,
							softwares.parent_id,
							softwares.`name`,
							softwares.version,
							softwares.type,
							softwares.systems,
							softwares.attachment_id,
							softwares.body,
							softwares.created,
							softwares.modified,
							softwares.size
							FROM
							softwares where softwares.id=$id";
						$repost=$this->Software->query($sql1);
						//pr($repost);die;
						if($repost[0]['softwares']['parent_id'] == 0){
							
								$parentlist= $this->Software->find('all',array(
									'conditions'=>array('Software.parent_id'=>$repost[0]['softwares']['id']),
									'recursive'=>-1
								));
								//pr($parentlist);die;;
								if($parentlist){
								
									$this->Session->setFlash(__('包含下级，不能删除'));
								return	$this->redirect('/admin/Softwares/index', null, false);
									
								}
						
						}
						$imgid=$repost[0]['softwares']['attachment_id'];
						$Attachment= $this->Attachment->find('all',array(
						    'conditions'=>array('Attachment.id'=>$imgid),
						    'recursive'=>-1
						));
						//pr($Attachment);die;
						$userfiles=WWW_ROOT."files".DS."userfiles";
						unlink($userfiles.DS.$Attachment[0]['Attachment']['path']);
						if($Attachment[0]['Attachment']['type']=='ipa'){
									unlink($userfiles.DS.$Attachment[0]['Attachment']['plists']);
								}
						$aa=$this->Attachment->delete($Attachment[0]['Attachment']['id']);
								
						if (!$this->Software->exists()) {
							throw new NotFoundException(__('Invalid software'));
						}
						//$this->request->allowMethod('post', 'delete');
						if ($this->Software->delete()) {
							$this->Session->setFlash(__('删除成功！'));
						} else {
							$this->Session->setFlash(__('删除失败，请再试一次！'));
						}
						return  $this->redirect('/admin/Softwares/index', null, false);
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Software->exists($id)) {
			throw new NotFoundException(__('Invalid software'));
		}
		$options = array('conditions' => array('Software.' . $this->Software->primaryKey => $id));
		$this->set('software', $this->Software->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Software->create();
			if ($this->Software->save($this->request->data)) {
				$this->Session->setFlash(__('The software has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The software could not be saved. Please, try again.'));
			}
		}
		$parentSoftwares = $this->Software->ParentSoftware->find('list');
		$attachments = $this->Software->Attachment->find('list');
		$this->set(compact('parentSoftwares', 'attachments'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Software->exists($id)) {
			throw new NotFoundException(__('Invalid software'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Software->save($this->request->data)) {
				$this->Session->setFlash(__('The software has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The software could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Software.' . $this->Software->primaryKey => $id));
			$this->request->data = $this->Software->find('first', $options);
		}
		$parentSoftwares = $this->Software->ParentSoftware->find('list');
		$attachments = $this->Software->Attachment->find('list');
		$this->set(compact('parentSoftwares', 'attachments'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Software->id = $id;
		if (!$this->Software->exists()) {
			throw new NotFoundException(__('Invalid software'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Software->delete()) {
			$this->Session->setFlash(__('The software has been deleted.'));
		} else {
			$this->Session->setFlash(__('The software could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
