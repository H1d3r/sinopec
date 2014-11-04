<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class BooksController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','globals');
	public $uses = array('Post');
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
 //操作手册通用版
 public function index() {
 
 
		$this->Post->recursive = 0;
		$lists = $this->Post->find('all',array(
			'conditions'=>array('Post.doctype'=>1,'Post.type'=>4),
			'order'=>"Post.id DESC"
			// 'fields'=>'Software.id'
			));
		/*$sql="SELECT
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
					posts.size,
					attachments.id,
					attachments.`name`,
					attachments.type,
					attachments.path
					FROM
					posts
					INNER JOIN attachments ON posts.attachment_id = attachments.id where posts.doctype =1 and posts.type=4";*/
					
			//$lists=$this->Post->query($sql);
			//pr($lists);
			$this->set(compact('lists'));
		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 //操作手册专用版
	public function view($id = null) {
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
					posts.size,
					attachments.id,
					attachments.`name`,
					attachments.type,
					attachments.path
					FROM
					posts
					INNER JOIN attachments ON posts.attachment_id = attachments.id where posts.doctype =1 and posts.type=5";
			$lists=$this->Post->query($sql);
				//pr($lists);
			$this->set(compact('lists'));
		
	}
	
	//操作手册运行环境
	public function edit($id = null) {
		
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
