<?php

App::uses('AppModel', 'Model');

/**
 * UserMaster Model
 *
 */
class Post extends AppModel
{
    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'posts';
    public $virtualFields = array(
        'status_text' => "IF(status = 1, 'active', 'Inactive')"
    );
     
    public $validate = array(
        'subject' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter subject.'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'Please Enter Short subject'
            ),
            'unique' => array(
                'rule' => array('uniqueCheck', 'subject'),
                'message' => 'This post is already exists.'
            ),
        ),
        'content' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter content.'
            ),
        )
       
    );

    /**
    * check unitqueness
    */
    public function uniqueCheck ($field) {
      $conditions = array(
             'Post.subject' => $field,
          );
      if(isset($this->data[$this->alias]['id'])) {
          $conditions['Post.id !='] = $this->data[$this->alias]['id'];
      }
      $count = $this->find('count', array(
         'conditions' => $conditions
      ));
      return $count == 0;
   }
    

}