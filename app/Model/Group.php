<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 * @property User $User
 */
class Group extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'group_id',
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

        
/*
 * 我们所做的，就是将 Group 和 User 模型系到 Acl上，并告诉 CakePHP 每次你创建一个User或Group的同时也要在 aros 表中输入一条记录。
 *  这使Acl的管理很简单，因为你的 AROs 在绑定你的 users 和 groups 表之后变得透明了，
 * 所以你每次创建或者删除一个 user/group 的同时 Aro 表也会更新。
 */
                    public $actsAs = array('Acl' => array('type' => 'requester'));

                    public function parentNode() {
                        return null;
                    }
}
