<?php

class ControlPanelController extends AppController {

	public $name = 'ControlPanel';
	public $helpers = array('Html', 'Form');
	
	public $uses = array( 'User' ,'Groups');

    function beforeFilter() {  
        
        // public access actions
        $this->Auth->allow( 'welcome' ); 
    }

    // Public welcome page of control panel
    function welcome() {
//            Debugger::dump($this->Auth->user());
        // check if the temporary user exists
        $tmpUser = $this->User->findByUsername( 'temp' );
            
        if ( empty( $tmpUser ) ) {
            
            $this->User->create();
            $this->User->save( array(   'username' => 'temp',
                                        'password' => Security::hash( 'temp', null, true ) ) );
        }
    }

    // Page when logged in
    /**
 * admin_index
 *
 * @param id integer aco id, when null, the root ACO is used
 * @return void
 */
	function index($id = null, $level = null) {
                                $aco = new Aco();    
//                                $this->data = $aco->generateTreeList(null, null, null, '&a;&nbsp;&nbsp;');
                                
//                              if (isset($this->request->query['root'])) {
//                                        $query = strtolower($this->request->query['root']);
//                                }
//
//                                if ($id == null) {
//                                        $root = isset($query) ? $query : 'controllers';
//                                        //$root = $this->AclAco->node(str_replace('.', '_', $root));
//                                        $root = $aco->node(str_replace('.', '_', $root));
//                                        $root = $root[0];
//                                } else {
//                                        $root = $aco->read(null, $id);
//                                }
//pr($root); 
//die;
//                                if ($level !== null) {
//                                        $level++;
//                                }    
                                
//                                $acos = array();
                                $roles = $this->Groups->find('list');
//                                if ($root) {
//                                        $fields = Hash::merge(array('id', 'parent_id', 'alias'), $fields = array());
////		$acos = $this->children($acoId, true, $fields);
//                                        $acos = $aco->children($root['Aco']['id'], true, $fields);
//                                        
////                                        foreach ($acos as $key => $acov) {
////                                            $children = $aco->childCount($acov['Aco']['id'], true);
////                                            pr($acos);
////                                            pr($acos[$key]['Aco']);
////			$acos[$key]['Aco']['children'][] = $children;
////		}
//
//                                }
//                                        $options = array('conditions' => array('Aco.parent_id' => 1));
		$acos = $aco ->find('all',$options=null);
//                pr($roles);
//                                $this->set(compact('acos', 'roles', 'level'));
                                $this->set(compact('acos', 'roles'));
                
                
                
//                                pr($level);die;
//                              $acos = $this->AclAco->getChildren($root['Aco']['id']);
            
		
	}
	
	function dashBoard() {
		
		// list number of users
		
		// last login users
		
		// first 10 lines of debug log
		
		// last 10 lines of error log
		
		// application specific data
                    }
                    

}
?>
