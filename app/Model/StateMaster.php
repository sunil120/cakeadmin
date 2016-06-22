<?php
App::uses('AppModel', 'Model');
/**
 * StateMaster Model
 *
 * @property Country $Country
 */
class StateMaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'state_master';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

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
		'country_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter correct country.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'state_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter state name.',
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
                            'rule' => array('uniqueCheck', 'state_name'),
                            'message' => 'This state is already exists.'
                        ),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
            'CountryMaster' => array(
                'className' => 'CountryMaster',
                'foreignKey' => 'country_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
            )
    );
        
    /**
    * Checks if there are records on the datasource with the same role_name
    *
    */
    public function uniqueCheck ($email) {
        $conditions = array(
               'StateMaster.state_name' => $email
            );
        if(isset($this->data[$this->alias]['id'])) {
            $conditions['StateMaster.id !='] = $this->data[$this->alias]['id'];
        }
        $count = $this->find('count', array(
           'conditions' => $conditions
        ));
        return $count == 0;
    }
           
}
