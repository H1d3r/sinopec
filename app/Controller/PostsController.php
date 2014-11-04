<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','globals');
	public $uses =array('Post','Attachment','Software'); //使用的模型
/*
 * 添加一些例外，以便AuthComponent将使我们能够创造一些组和用户。
 * 
 */
                    public function beforeFilter() {
                        parent::beforeFilter();
                        $this->Auth->allow('index');
                    }        

/*
 * 我们要在PostsController中增加规则，允许作者创建posts并且防止其他作者对其post做改动。 
 * 
 * 我们现在重写了 AppController 的 isAuthorized() 方法并且在父类中已核准用户后再进行内部检查，
 * 如果他不是,只允许他访问add动作, 并有条件访问edit 和 delete动作. 
 * 最后在 Post 模型中调用 isOwnedBy() 来告诉用户是否有权限来编辑post.
 * 一般的好做法是，以尽可能多的把逻辑挪到模型中去实现。
 */                    
                    public function isAuthorized($user) {
                        // All registered users can add posts
//                        if ($this->action === 'add') {
//                            return true;
//                        }

                        // The owner of a post can edit and delete it
                        if (in_array($this->action, array('add','edit', 'delete'))) {
                            $postId = $this->request->params['pass'][0];
                            if ($this->Post->isOwnedBy($postId, $user['id'])) {
                                return true;
                            }
                        }

                        return parent::isAuthorized($user);
                    }



/**
 * index method
 *
 * @return void
 */
	
/**
 * index method
 *
 * @return void
 */
 //首页
 public function index() {
		$this->Post->recursive = 0;
		$this->set('posts', $this->Paginator->paginate());
		
	}	
	
	
	
	
 //资源管理首页
	public function admin_index() {
		$sql="SELECT
				posts.id,
				posts.title,
				posts.type,
				posts.doctype,
				attachments.type,
				posts.created,
				attachments.id,
				posts.attachment_id
				FROM
				posts
				INNER JOIN attachments ON posts.attachment_id = attachments.id";
		$Postlist = $this->Post->query($sql);
		$softlist = $this->globals->soft();
		$catelist = $this->globals->cate();
        $this->set(compact('Postlist','softlist','catelist'));
		
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
		$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
		$this->set('post', $this->Post->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
 //资源管理页面的添加
	public function admin_add() {
		$catelist = $this->globals->cate();
		$doclist = $this->globals->doc();
		/*if ($this->request->is('post')) {
			$this->Post->create();
             $this->request->data['Post']['user_id'] = $this->Auth->user('id'); //Added this line
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
			}
		}*/
		//$users = $this->Post->User->find('list');
		
		$this->set(compact('users','catelist','doclist'));
	}
	
	
	
//添加进数据库的操作
	public function admin_msave() {
		$this->autoRender = false;
		 $user = $this->Auth->user();
		$data=$this->request->data;
		$img=$data['Post']['images_url'][0];
		//文件名称
		//pr($data);die;
		$name=$img['name'];
		$type=$img['type'];
		$size=$img['size']/1024;
		$size1=round($size);
		$size2=$size1."KB";
		$title=$data['title'];
		//pr($size);die;
		if(isset($data['id'])){
			$sql="SELECT
					posts.id,
					posts.user_id,
					posts.title,
					posts.body,
					posts.type,
					posts.image_id,
					posts.attachment_id,
					posts.created,
					posts.modified,
					posts.doctype,
					posts.size
					FROM
					posts where title='$title'and posts.id <>".$data['id'];
		}else{
				$sql="SELECT
					posts.id,
					posts.user_id,
					posts.title,
					posts.body,
					posts.type,
					posts.image_id,
					posts.attachment_id,
					posts.created,
					posts.modified,
					posts.doctype,
					posts.size
					FROM
					posts where title='$title'";
			
			}
			
			$posts = $this->Post->query($sql);
	
			if(!empty($posts)) {
				?>
					<script type="text/javascript" charset="utf-8">
					alert('文件名已存在');
					window.history.go(-1);
					</script>
				<?php
				die;
			}
		
			//修改的操作并且img['name']为空
				if(isset($data['id']) && $img['name']==""){
					$pbody = array(
								'Post'=>array(
									'id'=>$data['id'],
									'title'=>$data['title'],
									'body'=>$data['body'],
									'type'=>$data['dcate_id'],
									'doctype'=>$data['Post']['doc_id']
									)
								);
								//pr($pbody);die;
							$this->Post->create();
							$repbody = $this->Post->save($pbody);
						if($repbody){
								 $this->Session->setFlash('操作成功！');
								  $this->redirect('/admin/Posts/index', null, false);
								 
								  
						}else{
								 $this->Session->setFlash('操作失败！');
								 $this->redirect('/admin/Posts/edit', null, false);
						}
					
				}
			
			
			
		//临时文件名
		$tmp_name=$img['tmp_name'];
		//获取扩展名
		$ext = substr($name, (strrpos($name, '.') + 1));//找到扩展名
		$ext = strtolower($ext);
		if($img['name']!=""){
				$arr=array('ppt','pdf','doc','xls','pptx','pdfx','docx','xlsx');
				if(!in_array($ext,$arr)){
					?>
							<script type="text/javascript" charset="utf-8">
							alert('文件类型不正确！');
							window.history.go(-1);
							</script>
					<?php
						die;		
				
				}
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
						posts.id,
						posts.user_id,
						posts.title,
						posts.body,
						posts.type,
						posts.image_id,
						posts.attachment_id,
						posts.created,
						posts.modified,
						posts.doctype,
						posts.size
						FROM
						posts
						WHERE
						posts.id = $id";
				$repost=$this->Post->query($sql1);
				$imgid=$repost[0]['posts']['attachment_id'];
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
						/*if(isset($data['id'])){
							$pbody = array(
								'Post'=>array(
									'id'=>$data['id'],
									'user_id'=>$user['id'],
									'title'=>$data['title'],
									'body'=>$data['body'],
									'type'=>$data['Post']['cate_id'],
									"attachment_id"=>$relt['Attachment']['id'],
									'doctype'=>$data['Post']['doc_id'],	
									'size'=>$size2
								)
							);
						}else{*/
							$pbody = array(
								'Post'=>array(
									'id'=>$data['id'],
									'user_id'=>$user['id'],
									'title'=>$data['title'],
									'body'=>$data['body'],
									'type'=>$data['dcate_id'],
									"attachment_id"=>$relt['Attachment']['id'],
									'doctype'=>$data['Post']['doc_id'],	
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
						/*if(isset($data['id'])){
							$pbody = array(
								'Post'=>array(
									'id'=>$data['id'],
									'user_id'=>$user['id'],
									'title'=>$data['title'],
									'body'=>$data['body'],
									'type'=>$data['Post']['cate_id'],
									"attachment_id"=>$relt['Attachment']['id'],
									'doctype'=>$data['Post']['doc_id'],	
									'size'=>$size2
								)
							);
						}else{*/
								$pbody = array(
									'Post'=>array(
										'user_id'=>$user['id'],
										'title'=>$data['title'],
										'body'=>$data['body'],
										'type'=>$data['dcate_id'],
										"attachment_id"=>$relt['Attachment']['id'],
										'doctype'=>$data['Post']['doc_id'],	
										'size'=>$size2
									)
								);
							
							}
						}
							$this->Post->create();
							$repbody = $this->Post->save($pbody);
							if($repbody){
								 $this->Session->setFlash('操作成功！');
								  $this->redirect('/admin/Posts/index', null, false);
								 
								  
							}else{
								 $this->Session->setFlash('操作失败！');
								 $this->redirect('/admin/Posts/add', null, false);
							}
					
		}
	}
	
	
	//资源管理的修改页面
	public function admin_edit($id = null) {
		$sql="SELECT
				attachments.path,
				posts.title,
				posts.body,
				posts.type,
				posts.doctype,
				posts.id
				FROM
				posts
				INNER JOIN attachments ON posts.attachment_id = attachments.id where posts.id='$id'";
		 $Post =$this->Post->query($sql);
		 $docid=$Post[0]['posts']['type'];
		 if($docid==4 || $docid==5){
			$catelist = $this->globals->soft();
		 }else{
			$catelist = $this->globals->cate();
		}
		 
		 $doclist = $this->globals->doc();
		 //pr($id);
		 $this->set(compact('Post','catelist','doclist','id','docid'));
		
		 
	}
	
	
	public function admin_doctype() {
		$this->layout='ajax';
		$parent_id = $this->request['data']['treaid'];
		if($parent_id==1){
				$doclist = $this->globals->soft();
				
		}else{
				$doclist = $this->globals->cate();
		
		}
		//pr($doclist);
		$this->set(compact('doclist'));
	}
	
	
	/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 * 在View中使用 postLink() 将创建一个链接使用Javascrip来创建一个删除我们post的POST类型请求. 
 * 使用GET请求来删除内容是危险的,因为web爬虫将有机会删除你所有的内容.
 */
	public function admin_delete($id = null) {
		$this->Post->id = $id;
                                        //显示给用户确认信息。如果用户尝试通过GET请求删除post时，我们抛出异常。
			$sql1="SELECT
						posts.id,
						posts.user_id,
						posts.title,
						posts.body,
						posts.type,
						posts.image_id,
						posts.attachment_id,
						posts.created,
						posts.modified,
						posts.doctype,
						posts.size
						FROM
						posts
						WHERE
						posts.id = $id";
				$repost=$this->Post->query($sql1);
				$imgid=$repost[0]['posts']['attachment_id'];
				$Attachment= $this->Attachment->find('all',array(
				    'conditions'=>array('Attachment.id'=>$imgid),
				    'recursive'=>-1
				));
				//pr($Attachment);die;
				$userfiles=WWW_ROOT."files".DS."userfiles";
				unlink($userfiles.DS.$Attachment[0]['Attachment']['path']);
				$aa=$this->Attachment->delete($Attachment[0]['Attachment']['id']);
				//pr($aa);die;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		//$this->request->allowMethod('post', 'delete');
		if ($this->Post->delete()) {
			$this->Session->setFlash(__('删除成功！'));
		} else {
			$this->Session->setFlash(__('删除失败，请再试一次！'));
		}
		return  $this->redirect('/admin/Posts/index', null, false);
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
                                        /*
                                         * 这个动作首先确保用户已经访问到了一个已存的记录。
                                         * 如果他们没有传入 $id 的值或者post 没有找到，就抛出 NotFoundException 异常让 CakePHP ErrorHandler 来处理.
                                         */
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
                                        /*
                                         * 检查这个请求是否是一个POST请求，如果是，然后我们使用POST中的数据来 更新Post记录，否则就退回并将验证的错误显示给用户。
                                         */
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
			$this->request->data = $this->Post->find('first', $options);
		}
//                 $acls =  $this->Acl->check('User::'.$this->Auth->user('id'), 'Post', 'update') ;
//                                         pr($acls);
		$users = $this->Post->User->find('list');
		$this->set(compact('users'));
	}

	
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 * 在View中使用 postLink() 将创建一个链接使用Javascrip来创建一个删除我们post的POST类型请求. 
 * 使用GET请求来删除内容是危险的,因为web爬虫将有机会删除你所有的内容.
 */
	public function delete($id = null) {
		$this->Post->id = $id;
                                        //显示给用户确认信息。如果用户尝试通过GET请求删除post时，我们抛出异常。
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Post->delete()) {
			$this->Session->setFlash(__('The post has been deleted.'));
		} else {
			$this->Session->setFlash(__('The post could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
