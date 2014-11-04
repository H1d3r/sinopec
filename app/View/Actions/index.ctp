
<table  cellspacing="0" cellpadding="0">
        <thead>
                <tr>
                        <td><?php echo __('Controller'); ?></td>
                        <td><?php echo __('Group'); ?></td>
                </tr>
        </thead>
        <tbody>
<?php

    echo $this->Form->create(array('url' => '/Actions/index'));  

    foreach ( $actions as $current_action_group ) {
    
        $action_name = $current_action_group[ 'name' ];
        $action_name_singular = $current_action_group[ 'name_singular' ];
        $all_actions = $current_action_group[ 'actions' ];
          
          
        echo '<tr><td>'.$action_name.'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
        
        foreach ( $all_actions as $current_action ) {
        
                
            $clean = str_replace( 'function ', '', $current_action );
            $clean = str_replace( '(', '', $clean );
            $selected = '';
        
            // selected
            $checked_yes = false;
            $checked_no = false;
            if ( $selected == 'yes' ) {
                $checked_yes = 'checked=""';
            }
            elseif ( $selected == 'no' ) {
                $checked_no = true;
            }                
            
            $action_value_1 = $action_name_singular.'__'.$clean;
            $action_value_2 = $action_name.'__'.$clean;
        
            echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$clean.'</td>';
//            Debugger::dump($action_value_1);
//            Debugger::dump($action_value_2);
            if ( isset( $aco_list[ $action_value_1 ] ) ) {

                echo '<td>Delete:';
                echo $this->Form->checkbox( 'Actions.SecurityAccess.'.$action_value_2,
                                        array(  'label'=>false,
                                                'div'=>false,
                                                'value'=>'delete',
                                                'checked'=>$checked_no ) );
                
                
                echo '</td>';                                                
            }
            else {
                
                echo '<td>Add:';
                echo $this->Form->checkbox( 'Actions.SecurityAccess.'.$action_value_2,
                                        array(  'label'=>false,
                                                'div'=>false,
                                                'value'=>'include',
                                                'checked'=>$checked_yes ) );
                echo '</td>';                                                   
            }     
            
            echo '</tr>';       
        }
    }  
    
?>
        </tbody>
</table>

<?php
    echo $this->Form->end( array( 'label' => 'Submit', 'div' => false ) );
?>