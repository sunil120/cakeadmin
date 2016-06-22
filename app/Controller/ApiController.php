<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       api.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * API Copntroller Which handles Auth, token and API requests
 * @category API
 */
App::uses('Controller', 'Controller');
App::import(
        'Vendor', 'JWT', array('file' => 'firebase' . DS . 'php-jwt' . DS . 'Authentication' . DS . 'JWT.php')
);

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class ApiController extends Controller
{
    /**
     * Main Model must be set by child Controller
     * @category API
     */
    protected $_mainModel = null;

    /**
     * Component Handle Request with json/xml/html what evete parse ext
     * @category API
     */
    public $components = array(
        'Flash',
        'Paginator',
        'Crud',
        'General',
        'Auth' => array(
            'authorize' => array('Controller') // Added this line
        ),
        'RequestHandler',        
    );

    /**
     * Default API settings property
     * @var array $settings
     * @category API
     */
    public $settings = array(
        'scope' => array('UserModel.status' => 1),
        'recursive' => 0,
        'contain' => null,
        'Paginator' => array(
            'paramType' => 'querystring',
            'page' => 1,
            'limit' => 10,
            'maxLimit' => 100,
            'whitelist' => array(
                'limit', 'sort', 'page', 'direction'
            ),
        ),
        'sessionKey' => false,
        'autoRedirect' => false,
        'allowMethod' => array('loginApi', 'forgotpassword'),
        'authenticate' => array(
            'Jwtauth.Token' => array(
                'parameter' => '_token',
                'header' => 'X-ApiToken',
                'userModel' => 'UserModel',
                'scope' => array('UserModel.status' => 1),
                'fields' => array(
                    'username' => 'user_name',
                    'password' => 'password',
                ),
                'continue' => true,
            )
        )
    );

    /**
     * This controller does not use a model
     *
     * @var array $uses
     */
    public $uses = array();

    /**
     * load auth and parent settings
     * added settings
     * @category API
     * @param null
     * @return void
     */
    function beforeFilter()
    {
        //parent::beforeFilter();
        // Pass settings in
        $this->Auth->authenticate = $this->settings['authenticate'];
        $this->Paginator->settings = $this->settings['Paginator'];
        AuthComponent::$sessionKey = $this->settings['sessionKey'];
        $this->Auth->autoRedirect = $this->settings['autoRedirect'];
        $this->Auth->allow($this->settings['allowMethod']);
        $this->_getFilterConditions();
        $this->_getFileToData($this->_mainModel);
        $this->config = $this->getDefaults();        
    }

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['RoleMaster']) && $user['RoleMaster']['id'] == 1) {
            return true;
        }

        // Default deny
        return false;
    }

    /**
     * responce with pagination filter
     * @category API
     * @param type $alias Model Alias
     * @return mix/array
     */
    protected function _getPaginationDetails($alias)
    {
        $pagination = $this->request['paging'][$alias];
        unset($pagination['paramType']);
        unset($pagination['options']);
        return $pagination;
    }

    /**
     * Convert file to data
     * @category API
     * @param type $alias Model Alias
     * @return mix/array
     */
    protected function _getFileToData($modelName)
    {
        if (isset($_FILES)) {
            foreach ($_FILES as $key => $file) {
                if (!empty($modelName)) {
                    $this->request->data[$modelName][$key] = $file;
                    $this->request->data[$modelName][$key]['name'] = urldecode($this->request->data[$modelName][$key]['name']);
                } else {
                    $this->request->data[$key] = $file;
                    $this->request->data[$key]['name'] = urldecode($this->request->data[$modelName][$key]['name']);
                }
            }
        }
    }

    /**
     * Set API Exception responce and other before render it
     * @category API
     * @return void
     */
    function beforeRender()
    {
        if ($this->request->is('json')) {
            $this->viewVars = array('success' => true) + $this->viewVars;

            // Currently we only allow index to have pagination
            if ($this->request->params['action'] == 'index') {
                $this->viewVars['pagination'] = $this->_getPaginationDetails($this->_mainModel);
            }

            $serializeVars = array();
            foreach ($this->viewVars as $key => $value) {
                $serializeVars[] = $key;
            }
            $this->viewVars['_serialize'] = $serializeVars;
        }
    }

    /**
     * Responce with pagination filter
     *
     * @category API
     * @param string $alias Model Alias
     * @return mix/array
     */
    protected function _getFilterConditions()
    {
        // Search functionality code
        if (isset($this->request->query['filter']) && !empty($this->request->query['filter'])) {
            foreach ($this->request->query['filter'] as $name => $value) {
                $name = CakeText::tokenize($name, '.');
                $mainModel = (isset($name[1]) && !empty($name[1])) ? $name[0] : $this->_mainModel;
                $name = (isset($name[1]) && !empty($name[1])) ? $name[1] : $name[0];
                if (!empty($value)) {
                    $this->request->query['filter'][$name] = $value;
                    $conditions[$mainModel . '.' . $name] = $value;
                }
            }
            // Search functionality Configured in Pagination
            if ($conditions) {
                $this->Paginator->settings['conditions'] = $conditions;
                $this->settings['Paginator']['conditions'] = $conditions;
            }
        }
    }

    /**
     * Index Method to responce listing to GET Method
     * @category API
     * @param null
     * @return mix/array json/xml
     */
    public function index()
    {
        // Set relation ship level=0, increase it for relation ship level incres
        $model = $this->_mainModel;
        $this->$model->recursive = $this->settings['recursive'];
        $this->Paginator->settings = $this->settings['Paginator'];
        $modelData = $this->Paginator->paginate($this->_mainModel);
        $this->set(array('data' => $modelData));
    }

    /**
     * add Method to responce created to post Method
     * @category API
     * @param null
     * @return array message
     */
    public function add()
    {
        $model = $this->_mainModel;
        if ($this->request->is('post')) {
            $this->$model->create();
            if ($this->$model->save($this->request->data)) {
                $message = $this->$model->findById($this->$model->id);
            } else {
                $message = $this->$model->validationErrors;
            }
        }
        $this->set(array('data' => $message));
    }

    /**
     * view Method to responce details view to GET Method
     * @category API
     * @param $id (int)
     * @return array
     */
    public function view($id)
    {
        $model = $this->_mainModel;
        if (!$this->$model->exists($id)) {
            throw new NotFoundException(__('Invalid ' . $this->_mainModel));
        }
        $options = array('conditions' => array($this->_mainModel . '.' . $this->$model->primaryKey => $id));
        $this->set('data', $this->$model->find('first', $options));
    }

    /**
     * edit Method to responce update to patch Method
     * @category API
     * @param $id (int)
     * @return array message
     */
    public function edit($id)
    {
        $model = $this->_mainModel;
        if (!$this->$model->exists($id)) {
            throw new NotFoundException(__('Invalid ' . $this->_mainModel));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->$model->id = $id;
            if ($this->$model->save($this->request->data)) {
                $message = $this->$model->findById($this->$model->id);
            } else {
                $message = $this->$model->validationErrors;
            }
        }
        $this->set(array('data' => $message));
    }

    /**
     * delete Method to responce succes deleted to DELETE Method
     * @category API
     * @param $id (int)
     * @return array message
     */
    public function delete($id)
    {
        $model = $this->_mainModel;
        $this->$model->id = $id;
        if (!$this->$model->exists()) {
            throw new NotFoundException(__('Invalid' . $this->_mainModel));
        }

        $this->request->allowMethod('post', 'delete');
        if ($this->$model->delete()) {
            $message = 'The ' . $this->_mainModel . ' has been deleted.';
        } else {
            $message = 'The ' . $this->_mainModel . ' could not be deleted. Please, try again.';
        }
        $this->set(array('data' => $message));
    }


    /**
     * included getDefaults from APP
     * @param UserModel $user Object of auth user
     * @category API-APP
     */
    public function getDefaults()
    {
        return array(
            'messages' => array(
                'recordNotFound' => 'Record not Found',
                'saveSucces' => 'Record saved Successfully',
                'passwordSuccess' => 'Please Re-Login with Updated Password',
                'saveError' => 'Record could not be saved. Please, try again.',
                'passwordError' => 'Information entered are incorrect',
                'failSupervisor' => 'Please assign Valid Supervisor',
                'forgotsuccess' => 'Mail has been sent to you for setting your new password.',
                'resetsuccess' => 'Your password has been reset. Please login with new password.',
                'deleteSuccess' => 'Record deleted successfully.',
                'deleteError' => 'Record not deleted',
                'customError' => '×',
            ),
        );
    }


    /**
     * Convert file to data
     * @category API
     * @param type $alias Model Alias
     * @return mix/array
     */
    protected function _getinputToData()
    {
        $modelName = $this->_mainModel;
        if ($this->request->input()) {
            foreach (json_decode($this->request->input()) as $key => $file) {
                $this->request->data = array($modelName => array($key => $file));
            }
        }
    }


}