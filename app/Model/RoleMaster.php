<?php
App::uses('AppModel', 'Model');
/**
 * RoleMaster Model
 *
 */
class RoleMaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $useTable = 'role_master';
    
    public $validate = array(
        'role_name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'This enter role name.'
            ),
            'unique' => array(
                'rule' => array('uniqueCheck', 'role_name'),
                'message' => 'This Role Name already exists.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'This Role Name exceeds allowed characters limit. Please select short role name.'
            ),
        )
    );

    /**
    * Checks if there are records on the datasource with the same role_name
    *
    */
    public function uniqueCheck ($role_name) {
        $conditions = array(
               'RoleMaster.role_name' => $role_name,
            );

        if(isset($this->data[$this->alias]['id'])) {
            $conditions['RoleMaster.id !='] = $this->data[$this->alias]['id'];
        }
        $count = $this->find('count', array(
           'conditions' => $conditions
        ));
        return $count == 0;
    }
}
