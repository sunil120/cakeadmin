<?php

App::uses('AppModel', 'Model');

/**
 * UserMaster Model
 *
 */
class Faq extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'faq';
    public $virtualFields = array(
        'status_text' => "IF(status = 1, 'active', 'Inactive')"
    );
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'question' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter question.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'This question exceeds allowed characters limit. Please select short question.'
            ),
            'unique' => array(
                'rule' => array('uniqueCheck', 'question'),
                'message' => 'This question is already exists.'
            ),
        ),
        'answer' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter answer'
            )
        ),
    );
    
    /**
    * check unitqueness
    */
    public function uniqueCheck ($field) {
        $conditions = array(
               'Faq.question' => $field,
            );
        if(isset($this->data[$this->alias]['id'])) {
            $conditions['Faq.id !='] = $this->data[$this->alias]['id'];
        }
        $count = $this->find('count', array(
           'conditions' => $conditions
        ));
        return $count == 0;
    }

}
