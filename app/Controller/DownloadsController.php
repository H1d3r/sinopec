<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class DownloadsController extends AppController {

/**
 * Components
 *
 * @var array
 */
public $components = array('Paginator','globals');
	public $uses =array('Software','Attachment'); 
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
 *通用版
 * @return void
 */
	public function index() {
		$this->Software->recursive = 0;
		$pids = $this->Software->find('all',array(
			'conditions'=>array('Software.type'=>1,'Software.parent_id'=>0),
			'order'=>"Software.id DESC"
			// 'fields'=>'Software.id'
			));
		$ids =array();
		foreach ($pids as $key => $value) {
			$_id = $this->Software->find('first',array(
					'conditions'=>array('Software.type'=>1,'Software.parent_id'=>$value['Software']['id']),
					'order'=>"Software.id DESC",
					// 'fields'=>'Software.id',
					'limit'=>1
				));
			if($_id){

				if($_id['Software']['parent_id'] == $value['Software']['id']){
					unset($pids[$key]);
				}
				$ids[] =  $_id;
			}
		}
		$softwares = array_merge($pids,$ids);
		$this->set('softwares', $softwares);
	}

/**
 * index method
 *历史版本
 * @return void
 */
	public function lists($id=null) {
		$this->Software->recursive = 0;
		$softwares = $this->Software->find('all',array('conditions'=>array(
			'OR'=>array('Software.parent_id'=>$id,'Software.id'=>$id)),'order'=>"Software.created DESC"));
		// $software['Software']['body'] = 
		$this->set('softwares', $softwares);

	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 //专用版
	public function view($id = null) {
		$pids = $this->Software->find('all',array(
			'conditions'=>array('Software.type'=>2,'Software.parent_id'=>0),
			'order'=>"Software.id DESC"
			// 'fields'=>'Software.id'
			));
		$ids =array();
		foreach ($pids as $key => $value) {
			$_id = $this->Software->find('first',array(
					'conditions'=>array('Software.type'=>2,'Software.parent_id'=>$value['Software']['id']),
					'order'=>"Software.id DESC",
					// 'fields'=>'Software.id',
					'limit'=>1
				));
			if($_id){

				if($_id['Software']['parent_id'] == $value['Software']['id']){
					unset($pids[$key]);
				}
				$ids[] =  $_id;
			}
		}
		$softwares = array_merge($pids,$ids);
		$this->set('softwares', $softwares);
		
	}

	//运行环境
	public function version($id = null) {
		$pids = $this->Software->find('all',array(
			'conditions'=>array('Software.type'=>3,'Software.parent_id'=>0),
			'order'=>"Software.id DESC"
			// 'fields'=>'Software.id'
			));
		$ids =array();
		foreach ($pids as $key => $value) {
			$_id = $this->Software->find('first',array(
					'conditions'=>array('Software.type'=>3,'Software.parent_id'=>$value['Software']['id']),
					'order'=>"Software.id DESC",
					// 'fields'=>'Software.id',
					'limit'=>1
				));
			if($_id){

				if($_id['Software']['parent_id'] == $value['Software']['id']){
					unset($pids[$key]);
				}
				$ids[] =  $_id;
			}
		}
		$softwares = array_merge($pids,$ids);
		$this->set('softwares', $softwares);
		
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
