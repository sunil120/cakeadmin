<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
// app/Model/User.php

App::uses('Model', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UserModel extends AppModel {
    public $useTable = 'user_master';

    public function beforeSave($options = array()) {

       if (isset($this->data[$this->alias]['password'])) {
           $passwordHasher = new BlowfishPasswordHasher();
           $this->data[$this->alias]['password'] = $passwordHasher->hash(
               $this->data[$this->alias]['password']
           );
       }
       return true;
    }
    
    public $belongsTo = array(
        'RoleMaster' => array(
            'className' => 'RoleMaster',
            'foreignKey' => 'role_id'
        )
    );


    public $validate = array(
            'role_id' => array(
                'notBlank' => array(
                     'rule' => array('notBlank'),
                    'message' => 'Please select any role.'
                ),
            ),
            'first_name' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Please fill first name.'
                ),
                'maxLength' => array(
                    'rule' => array('maxLength', '100'),
                    'message' => 'This first name exceeds allowed characters limit. Please select short first name.'
                ),
            ),
          'last_name' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Please fill last name.'
                ),
                'maxLength' => array(
                    'rule' => array('maxLength', '100'),
                    'message' => 'This last name exceeds allowed characters limit. Please select short last name.'
                ),
            ),
        'email' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Please enter email.'
                ),
                'email' => array(
                    'rule' => array('email'),
                    'message' => 'Please enter correct email.'
                ),
                'maxLength' => array(
                       'rule' => array('maxLength', '100'),
                       'message' => 'This middle name exceeds allowed characters limit. Please select short middle name.'
                ),
                'unique' => array(
                       'rule' => array('uniqueCheck', 'email'),
                       'message' => 'This Email already exists.'
                ),
            ),

    );

    /**
    * Checks if there are records on the datasource with the same role_name
    */
   public function uniqueCheck ($email) {
      $conditions = array(
             'UserModel.email' => $email,
          );
      if(isset($this->data[$this->alias]['id'])) {
          $conditions['UserModel.id !='] = $this->data[$this->alias]['id'];
      }
      $count = $this->find('count', array(
         'conditions' => $conditions
      ));
      return $count == 0;
   }
}
