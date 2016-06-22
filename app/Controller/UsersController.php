<?php

/**
 * User Controller
 *
 * User module related actions.
 *
 * @package     App.Controller
 * @subpackage  UsersController
 */
App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * User Controller
 *
 * User module related actions.
 *
 * @package     App.Controller
 * @subpackage  UsersController
 */
class UsersController extends AppController
{
    //Load needful models
    public $uses = array('UserModel', 'RoleMaster');

    //load components
    public $components = array('Email');

    /**
     * Before filter callback
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        /**
         * Allowed loginApi
         * Updated settings
         * @category API
         */
        $this->Auth->allow('loginApi');
    }

    /**
     * get model object for this class.
     * @return object UserModel
     */
    public function getModel()
    {
        return $this->UserModel;
    }

    

    /**
     * User list action.
     */
    public function index(){ }

    /**
     * User profile action.
     * @param int $id
     */
    public function userprofile($id = null)
    {
        if ($this->request->is(array('post','put'))) {
            if($this->UserModel->saveAll($this->request->data)) {
               $config = $this->getDefaults();
               $this->Session->setFlash(__($config['messages']['saveSucces']), null, 'success');
               return $this->redirect(array('controller'=>'dashboard','action' =>'index'));
            } 
        } 
        $this->request->data = $this->getModel()->read(null,$id);
        $roles = $this->RoleMaster->find('list', array('conditions' => array('status' => 1),'fields'=>array('id','role_name')));
        $this->set(compact('roles'));
        
    }

    /**
     * Do related needful operation on save success.
     * @return void
     */
    public function onSaveSuccess()
    {
        $userId = $this->getModel()->getLastInsertID();
        if ($userId) {
            $params = $this->prepareEmailData(array('user_id' => $userId));
            $this->Email->sendEmailWithTemplate('user_registration', array('user_id' => $params));
        }
    }

    /**
     * Do related needful operation on before save data.
     * @param object UserModel $data
     * @param type $model
     * @return array
     */
    public function onBeforeSaveData($data, $model)
    {
        if ($this->action == 'edit' || $this->action == 'userprofile') {
            $auth = $this->Auth->user();
            $userId = $auth['id'];
            $message = (isset($auth['first_name']))?$auth['first_name']:'';
            $message .= (isset($auth['last_name']))?' '.$auth['last_name']:'';
            $message .= ' updated profile';
        }
        return $data;
    }

    /**
     * User change password action.
     * @param int $id
     * @return void
     */
    public function changepassword($id = null)
    {

        $id = $this->Auth->user('id');
        $current_user_profile = $this->getModel()->find('first', array('conditions' => array('UserModel.id' => $id)));
        $user_password = $current_user_profile['UserModel']['password'];
        $config = $this->getDefaults();
        if ($this->request->is(array('post'))) {
            if ($this->request->data['UserModel']['new_password'] != $this->request->data['UserModel']['confirm_password']) {
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                $this->Session->setFlash(__($config['messages']['passwordError']), null, $params);
                return $this->redirect(array('action' => 'changepassword', $this->request->data['UserModel']['id']));
            }
            $config = $this->getDefaults();
            $new_password = Security::hash($this->request->data['UserModel']['old_password'], 'blowfish', $user_password);
            if ($user_password === $new_password) {
                unset($this->request->data['User']['old_password']);
                $this->request->data['UserModel']['password'] = $this->request->data['UserModel']['new_password'];
                if ($this->getModel()->save($this->request->data)) {
                    return $this->redirect(array('action' => 'logout'));
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->Session->setFlash(__($config['messages']['passwordSuccess']), null, $params);
                } else {
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->Session->setFlash(__($config['messages']['passwordError']), null, $params);
                    return $this->redirect(array('action' => 'changepassword', $this->request->data['UserModel']['id']));
                }
            } else {
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                $this->Session->setFlash(__($config['messages']['passwordError']), null, $params);
                return $this->redirect(array('action' => 'changepassword', $this->request->data['UserModel']['id']));
                return $this->redirect(array('action' => 'changepassword', $this->request->data['UserModel']['id']));
            }
        } else {
            $options = array('conditions' => array('UserModel.' . $this->getModel()->primaryKey => $id));
            $this->request->data = $this->UserModel->find('first', $options);
            $this->set('userdata', $this->request->data);
        }
    }

    /**
     * User forgot password action.
     * @return void
     */
    public function forgotpassword()
    {
        $this->layout = 'outer';
        $errorMessage = $this->Session->read('forgotPasswordUser');
        $this->set('error', $errorMessage);
        $this->Session->write('forgotPasswordUser', '');
        $config = $this->getDefaults();
        if ($this->request->is(array('post'))) {
            $user_exist = $this->getModel()->find('first', array('conditions' => array('UserModel.email' => $this->request->data['UserModel']['email'])));
            if ($user_exist) {
                $params = $this->prepareEmailData(array('user_id' => $user_exist['UserModel']['id']));
                $this->Email->sendEmailWithTemplate('forgot_password', $params);
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                $this->Session->setFlash(__($config['messages']['forgotsuccess']), 'default',$params);
                return $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->write('forgotPasswordUser', 'Invalid Information');
                return $this->redirect(array('action' => 'forgotpassword'));
            }
        }
    }

    /**
     * User set password action.
     * @param int $id
     * @return void
     */
    public function setpassword($id = null)
    {
        $this->layout = 'outer';
        $config = $this->getDefaults();
        $errorMessage = $this->Session->read('setPassword');
        $this->set('error', $errorMessage);
        $this->Session->write('setPassword', '');
        if ($this->request->is(array('post'))) {
            if ($this->request->data['UserModel']['new_password'] != $this->request->data['UserModel']['confirm_password']) {
                $this->Session->write('setPassword', 'Invalid Information');
                return $this->redirect(array('action' => 'setpassword', $id));
                return false;
            } else {
                $this->request->data['UserModel']['password'] = $this->request->data['UserModel']['new_password'];
                unset($this->request->data['UserModel']['new_password']);
                unset($this->request->data['UserModel']['confirm_password']);
                if ($this->getModel()->save($this->request->data)) {
                    $params = $this->prepareEmailData(array('user_id' => $user_exist['UserModel']['id']));
                    $this->Email->sendEmailWithTemplate('reset_password_confirmation',$params);
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->Session->setFlash(__($config['messages']['resetsuccess']), null, $params);
                    $this->redirect('/users/login');
                }
            }
        } else {
            $options = array('conditions' => array('UserModel.' . $this->getModel()->primaryKey => $id));
            $this->request->data = $this->getModel()->find('first', $options);
            $this->set('userdata', $this->request->data);
        }
    }

    /**
     * User view details action.
     * @param int $id
     */
    public function view($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    /**
     * ajax_fetch
     * Index action or listing action ajax call back for getting listing datable data in json format.
     * @param boolean $isexport
     * @return string | json
     */
    public function ajax_fetch($isexport = false)
    {

        $conditions = array();
        $conditions['fields'] = array('UserModel.*', 'concat(UserModel.first_name," ",UserModel.last_name) as full_name', 'RoleMaster.id as RoleMaster_id', 'RoleMaster.role_name as RoleMaster_role_name');

        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            //for Global AJAX search filter
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $search_term = true;
                $where_conditions['UserModel.first_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.middle_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.last_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.email LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.user_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
        }

        $config = array('find_configs' => $conditions);
        $userMasters = $this->Crud->listdata($config, $isexport);
        $this->autoRender = false;
        echo $userMasters;
        exit();
    }

    /**
     * User add action.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
         // For fetching roles
        $roles = $this->RoleMaster->find('list', array('conditions' => array('status' => 1),'fields'=>array('id','role_name')));
        $this->set(compact('roles'));
    }

    /**
     * User edit action.
     * @param int $id
     */
    public function edit($id = null)
    {
        if ($this->request->is(array('post','put'))) {
            $data = $this->request->data;
            $this->Crud->edit($id);
        } else {
            $this->request->data = $this->getModel()->read(null,$id);
        }
        $this->Session->write('http_referer', $this->here);
        $roles = $this->RoleMaster->find('list', array('conditions' => array('status' => 1),'fields'=>array('id','role_name')));
        $this->set(compact('roles'));
        
    }

    /**
    * delete method
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function delete($id = null) {
        $config = $this->getDefaults();
        if (!isset($this->request->data['bulk_process_ids']) && $id <= 0) {
            throw new MethodNotAllowedException();
        }
        if(isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);

        if ($this->getModel()->delete($id,true)) {
            $this->Session->setFlash(__($config['messages']['deleteSuccess']), null, 'success');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__($config['messages']['deleteError']), null, 'error');
            return $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * User login action.
     */
    public function login()
    {
        $this->layout = 'outer';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $session = $this->Auth->user();
                if ($session['RoleMaster']['status'] == "1" && $session['status'] == "1"){
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Session->destroy();
                    $this->Session->setFlash(__('You are set as inactive.'), 'default', array(), 'auth');
                }

            } else {
                $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
            }
        }
    }

    /**
     * User logout action.
     */
    public function logout()
    {
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Do related needful operation on before list data.
     * @param array $data
     * @param UserModel $model
     * @return array
     */
    public function onBeforeListData($data, $model)
    {

        foreach ($data as $key => $val) {
            $data[$key]['UserModel'] = array_merge($val['UserModel'], $val['RoleMaster']);
            $data[$key]['UserModel']['full_name'] = $data[$key][0]['full_name'];
            if ($data[$key]['UserModel']['status'] == 1) {
                $data[$key]['UserModel']['status'] = 'Active';
            } else {
                $data[$key]['UserModel']['status'] = 'Inactive';
            }
            unset($data[$key]['RoleMaster']);
        }
        return $data;
    }


    /**
     * Generate loginApi wih login Credentials
     * added settings
     * @category API
     * @param null
     * @return mix/array json/xml
     */
    public function loginApi()
    {
        $udata = $this->UserModel->find('first',array('conditions'=>array('UserModel.id'=>2)));
        unset($udata['UserModel']['password']);
        $data['UserModel'] = $udata['UserModel'];
        $data['UserModel']['RoleMaster'] = $udata['RoleMaster'];
        $this->Auth->login($data['UserModel']); 
        $this->redirect($this->Auth->redirectUrl());
        
        
    }

    /**
     * change status to active or inactive
     */
    public function changestatus($id, $status)
    {
        $this->autoRender = FALSE;
        if (strtolower($status) == 'active') {
            $new_status = 0;
            $msg = "User status has been changed to inactive";
        } else {
            $new_status = 1;
            $msg = "User status has been changed to active";
        }
        $this->getModel()->unbindModel(array('belongsTo' => array('RoleMaster')));
        if ($this->getModel()->updateAll(array('status' => $new_status), array('id' => $id))) {
            $this->Session->setFlash(__($this->General->successMsg($msg)));
        } else {
            $this->Session->setFlash(__($this->General->errorMsg()));
        }

        return $this->redirect(array('action' => 'index'));
    }
    
     /**
     * Set data for all the placeholders of email
     * @param array $config
     * @return array $data
     */
    public function prepareEmailData($config = array())
    {
        if (!empty($config)) {

            #get all placeholder data for user id
            $result = $this->getModel()->find('first', array(
                'conditions' => array('id' => $config['user_id']),
                'recursive' => -1,
            ));
            $data = array(
                '[first_name]' => $result['UserModel']['first_name'],
                '[last_name]' => $result['UserModel']['last_name'],
                '[user_name]' => $result['UserModel']['user_name'],
                '[email]' => $result['UserModel']['email'],
                '[forgot_password_link]' => Router::url('/', true) . "users/setpassword/" . $result['UserModel']['id'],
                '[registration_confirmation_link]' => Router::url('/', true) . "users/setpassword/" . $result['UserModel']['id'],
            );

            return $data;
        }

        return null;
    }


}