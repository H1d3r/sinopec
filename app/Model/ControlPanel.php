<?php
App::uses('AppModel', 'Model');
/**
 * ControlPanel Model
 *
 * @property User $User
 */
class ControlPanel extends AppModel {

/**
 * name
 *
 * @var string
 */
	public $name = 'AclAco';

/**
 * useTable
 *
 * @var string
 */
	public $useTable = 'acos';

/**
 * actsAs
 *
 * @var array
 */
	public $actsAs = array('Tree');

/**
 * alias
 *
 */
	public $alias = 'Aco';

/**
 * hasAndBelongsToMany
 */
	public $hasAndBelongsToMany = array(
		'Aro' => array(
			'with' => 'Acl.AclPermission',
		),
	);
        
        
        
        
                            /**
 * getChildren
 *
 * @param integer aco id
 */
	public function getChildren($acoId, $fields = array()) {
		$fields = Hash::merge(array('id', 'parent_id', 'alias'), $fields);
		$acos = $this->children($acoId, true, $fields);
		foreach ($acos as &$aco) {
			$aco[$this->alias]['children'] = $this->childCount($aco[$this->alias]['id'], true);
		}
		return $acos;
	}
        
        
        
        
}
