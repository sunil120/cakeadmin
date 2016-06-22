<?php
App::uses('AppModel', 'Model');
/**
 * UserMaster Model
 *
 */
class Page extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'pages';
    public $virtualFields = array(
        'status_text' => "IF(status = 1, 'active', 'Inactive')"
    );

    public $validate = array(        
        'title' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter title.'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'Please Enter Short Title'
            ), 
            'unique' => array(
                    'rule' => array('uniqueCheck', 'title'),
                    'message' => 'This page is already exists.'
             ),
        ),  
         'content' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter content.'
            ),
        ), 
    );
    
    /**
    * Checks if there are records on the datasource with the same role_name
    */
    public function uniqueCheck ($field) {
      $conditions = array(
             'Page.title' => $field,
          );
      if(isset($this->data[$this->alias]['id'])) {
          $conditions['Page.id !='] = $this->data[$this->alias]['id'];
      }
      $count = $this->find('count', array(
         'conditions' => $conditions
      ));
      return $count == 0;
   }
    
}
