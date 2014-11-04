<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
        
/*
 * 添加一些例外，以便AuthComponent将使我们能够创造一些组和用户。
 * 
 */
                    public function beforeFilter() {
                        parent::beforeFilter();



                        // For CakePHP 2.0
//                        $this->Auth->allow('*');

                        // For CakePHP 2.1 and up
//                        $this->Auth->allow('initDB');
                        $this->Auth->allow(array('register', 'login', 'logout'));
                    }
                    
 /*
  * 我们已经建立了一些基本的访问规则。
  * 我们允许administrators可以 做任何事。
  * Managers可以访问任何的posts 和 widgets。
  * 而users 只可以在posts 和 widgets中访问add 和 edit。
  * 
  * 
  * 我们得为 Group 模型建立一个引用，并且修改它的id让它指定 我们想要的ARO，
  * 这个因为 AclBehavior``并不设置表 ``aros 中 的别名字段，我们必须使用对象引用或者一个数据来引用我们想要用 的ARO。
  * 
  * 你可能已经注意到我故意的在Acl权限中略掉了index和view。
  * 我们要 让view和index在 PostsController 和 WidgetsController 中 是公开动作，这允许无需认证的users来查看这些页面。
  * 然而，你也 可以随时在 AuthComponent::allowedActions 中删除这些动作 ，恢复到在Acl中的设置。
  * 
  * 
  */                   
                    public function initDB() {
                            $group = $this->User->Group;
                            //Allow admins to everything
                            $group->id = 1;
                            $this->Acl->allow($group, 'controllers');

                            //allow managers to posts and widgets
                            $group->id = 2;
                            $this->Acl->deny($group, 'controllers');
                            $this->Acl->allow($group, 'controllers/Posts');
                            $this->Acl->allow($group, 'controllers/Widgets');

                            //allow users to only add and edit on posts and widgets
                            $group->id = 3;
                            $this->Acl->deny($group, 'controllers');
                            $this->Acl->allow($group, 'controllers/Posts/add');
                            $this->Acl->allow($group, 'controllers/Posts/edit');
                            $this->Acl->allow($group, 'controllers/Widgets/add');
                            $this->Acl->allow($group, 'controllers/Widgets/edit');
                            //we add an exit to avoid an ugly "missing views" error message
                            echo "all done";
                            exit;
                    }
                    
                    
 /*
  * login 动作执行AuthComponent中的 $this->Auth->login() 函数且不需要其他的设置的原因是
  * 我们遵循了之前提到的在数据库中的user表的命名约定，并且使用表单提交用户的数据到控制器。
  * 这个函数返回登录成功还是失败，如果成功，就重定向到我们设置的登录成功的跳转页面。
  */       
                    public function login() {
						$this->layout = 'ajax';
                        if ($this->request->is('post')) {
                                if ($this->Auth->login()) {
//                                    pr($this->Auth->login());exit;
                                        $this->Session->setFlash('登录成功');
//                                        return $this->redirect($this->Auth->redirect());
                                    return $this->redirect($this->Auth->redirectUrl());
                                }

                                $this->Session->setFlash(__('Your username or password was incorrect.'));
                            }
                        
                             /*
                            * 如果一个用户已经登录了，重定向到首页
                            */
                           if ($this->Session->read('Auth.User')) {
                                   $this->Session->setFlash('You are logged in!');
                                   $this->redirect('/', null, false);
                           }
                   
                    }
// $user = $this->Auth->user();
//                         if(!$user){
//                           return $this->redirect(array('action' => 'index'));
//                         }
public function admin_login() {
        // $this->layout = 'ajax';   
        $this->autoRender = false;
         /*
        * 如果一个用户已经登录了，重定向到首页
        */
       if ($this->Session->read('Auth.User')) {
               $this->Session->setFlash('You are logged in!');
               $this->redirect('/', null, false);
       }else{
              $this->redirect('/Users/login', null, false);
       }

}


                    function logout(){
//                            $this->Auth->logout();
//                            $this->redirect('/');
                            
                            
                            $this->Session->setFlash('Good-Bye');
                            //$this->redirect($this->Auth->logout());
          							$this->Auth->logout();
          							$this->redirect('/', null, false);
                     } 

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
                                         //检查这个请求是否是一个 HTTP POST 请求.
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
                                       
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
