<?php
App::uses('AppModel', 'Model');
/**
 * CountryMaster Model
 *
 */
class CountryMaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table country_master
 */
	public $useTable = 'country_master';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'country_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'country_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter country name.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxLength' => array(
				'rule' => array('maxLength', '50'),
				'message' => 'Maximum 50 charactors allowed',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
                        'unique' => array(
                            'rule' => array('uniqueCheck', 'country_name'),
                            'message' => 'This country is already exists.'
                        ),
		),
	);
        
        /**
        * Checks if there are records on the datasource with the same role_name
        *
        */
       public function uniqueCheck ($email) {
          $conditions = array(
                 'CountryMaster.country_name' => $email
              );

          if(isset($this->data[$this->alias]['id'])) {
              $conditions['CountryMaster.id !='] = $this->data[$this->alias]['id'];
          }
          $count = $this->find('count', array(
             'conditions' => $conditions
          ));
          return $count == 0;
       }
       
   
}
