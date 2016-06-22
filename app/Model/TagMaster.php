<?php

App::uses('AppModel', 'Model');

/**
 * TagMaster Model
 *
 */
class TagMaster extends AppModel
{
    /**
     * Use table
     *
     * @var mixed False or table tag_master
     */
    public $useTable = 'tags';
    public $virtualFields = array(
        'status_text' => "IF(status = 1, 'active', 'Inactive')"
    );

    public $validate = array(        
        'tag_name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter tag.'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'Please enter short tag(Max Char:100).'
            ), 
            'unique' => array(
                    'rule' => array('uniqueCheck', 'tag_name'),
                    'message' => 'This tag is already exists.'
             ),
        ),  
    );
    
    /**
    * Checks if there are records on the datasource with the same role_name
    */
    public function uniqueCheck ($field) {
      $conditions = array(
             'TagMaster.tag_name' => $field,
          );
      if(isset($this->data[$this->alias]['id'])) {
          $conditions['TagMaster.id !='] = $this->data[$this->alias]['id'];
      }
      $count = $this->find('count', array(
         'conditions' => $conditions
      ));
      return $count == 0;
   }
    

}