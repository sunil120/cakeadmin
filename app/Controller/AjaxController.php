<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AjaxController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Country','Post','Users');
	public $helpers = array('Html', 'Form');

	
    public function index() {
         $this->set('post', $this->Post->find('all'));
    }

    public function getModelObject($modelName) {
    	$AnotherModel = ClassRegistry::init($modelName);
        return $AnotherModel;
    }

    public function ajax_fetch() {
        if ($this->request->isget() && $this->request->query != null) {
	        $data = $this->request->query;

	        $model = $this->getModelObject($data['model']);
	        foreach(json_decode($data['key']) as $field => $value){
	        	$conditions[$field] = $value;
	        }

	        $result = $model->find('all',array('conditions' => array($conditions)));
	    }

        Configure:: write('debug', 0);
        $this->autoRender = false;
        echo json_encode(array('state_name' => $result[0]['StateMaster']['state_name']));
        exit();
    }

	public function ajax_delete() {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }

	    $data = $this->request->input('json_decode', true);
    	$index = $data['index'];
    	$id = $data['value']['id'];

	    if ($this->Post->delete($id)) {
	        $this->Session->setFlash(
	            __('The post with id: %s has been deleted.', h($id))
	        );
	    } else {
	        $this->Session->setFlash(
	            __('The post with id: %s could not be deleted.', h($id))
	        );
	    }

	    Configure:: write('debug', 0);
        $this->autoRender = false;
        $return['data'] = array(
        	'status' => 200,
        	'index' => $index
        	);
        echo json_encode($return);
        exit();
	}
	
}
