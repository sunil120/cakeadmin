<?php
App::uses('AppModel', 'Model');
/**
 * CityMaster Model
 *
 * @property Country $Country
 */
class CityMaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'city_master';

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
		'country_id' => array(
                   'notBlank' => array(
                        'rule' => array('notBlank'),
                        'message' => 'Please select country.',
                    ),
		),
                'state_id' => array(
                    'notBlank' => array(
                        'rule' => array('notBlank'),
                        'message' => 'Please select state.',
                    ),
		),
		'city_name' => array(
                    'notBlank' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Please enter city name.',
                    ),
                    'maxLength' => array(
                            'rule' => array('maxLength', '50'),
                            'message' => 'Maximum 50 charactors allowed',
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
            ),
            'StateMaster' => array(
                'className' => 'StateMaster',
                'foreignKey' => 'state_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
            )
    );
        
    /**
    * Checks if there are records on the datasource with the same role_name
    *
    */
    public function uniqueCheck ($city) {
        $conditions = array(
               'CityMaster.city_name' => $city,
               'CityMaster.country_id' =>$this->data[$this->alias]['country_id'],
               'CityMaster.state_id' => $this->data[$this->alias]['state_id'],
            );
        if(isset($this->data[$this->alias]['id'])) {
            $conditions['CityMaster.id !='] = $this->data[$this->alias]['id'];
        }
        $count = $this->find('count', array(
           'conditions' => $conditions
        ));
        return $count == 0;
    }
           
}
