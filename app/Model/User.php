<?php
App::uses('AppModel', 'Model');

App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Group $Group
 * @property Post $Post
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
        
                    
                    public $actsAs = array('Acl' => array('type' => 'requester'));
                    
                    
/*
 * 为了让 Auth 和 Acl 正确工作，我们需要将users和groups同Acl的 表进行关联。
 * 需要用到 AclBehavior。
 *  AclBehavior 允许将 模型自动连接到Acl的表。
 * 使用它得要在你的模型中实现 parentNode() 方法，在模型 User 中添加如下代码* 
 */                    
                    
                    public function parentNode() {
                       if (!$this->id && empty($this->data)) {
                           return null;
                       }
                       if (isset($this->data['User']['group_id'])) {
                           $groupId = $this->data['User']['group_id'];
                       } else {
                           $groupId = $this->field('group_id');
                       }
                       if (!$groupId) {
                           return null;
                       } else {
                           return array('Group' => array('id' => $groupId));
                       }
                   }           
                   
 /*
  * 
  * 为了简单，只对每个组进行权限设置，我们需要在 User 模型中实现 bindNode()
  * 这个方法将会告诉 ACL 忽略检查 User Aro’s 而只检查 Group Aro’s.
  * 任意user都需要设置 group_id 才可起作用。
  * 
  */                  
                    public  function  bindNode ( $user )  { 
                        return  array ( 'model'  =>  'Group' ,  'foreign_key'  =>  $user [ 'User' ][ 'group_id' ]); 
                    }

 /*
  * 
  * 存储明文密码是非常不安全的
  * 每次用户密码保存的时候，都会使用 AuthComponent 组件提供的默认的类进行散列化。
  */       
                    public function beforeSave($options = array()) {
                        $this->data['User']['password'] = AuthComponent::password(
                          $this->data['User']['password']
                        );
                        return true;
                    }                  

}
