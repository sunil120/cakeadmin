<?php

App::uses('ApiController', 'Controller');

/**
 * RestUsers API for User
 *
 * @property Main Model $_mainModel
 * @property Uses other Models $uses
 */
class RestUsersController extends ApiController
{
    /**
     * User Main model Uses
     * @category API
     * @var array Specified Main Model
     */
    protected $_mainModel = 'UserModel';

    /**
     * User model Uses
     * @category API
     * @var array Specified All Model
     */
    public $uses = array('UserModel', 'RoleMaster');

    /**
     * load auth and parent settings
     * added settings
     * @category API
     * @param null
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->_getinputToData();        
    }

    public function getModel()
    {
        return $this->UserModel;
    }

    /**
     * Add Users
     * @category API
     * @param null
     * @return void
     */
    public function add()
    {

        // For fetching roles
        $roles_all = $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.status' => 1)));
        $roles = array();
        foreach ($roles_all as $key => $values) {
            $roles[$key]['id'] = $values['RoleMaster']['id'];
            $roles[$key]['name'] = $values['RoleMaster']['role_name'];
        }
        $this->set('roles', $roles);
        // For fetching operators
        $supervisor_options = array('conditions' => array('RoleMaster.role_name' => 'Operator', 'UserModel.status' => 1));
        $supervisor_all = $this->getModel()->find('all', $supervisor_options);
        $supervisors = array();
        foreach ($supervisor_all as $key => $values) {
            $supervisors[$key]['id'] = $values['UserModel']['id'];
            $supervisors[$key]['name'] = $values['UserModel']['first_name'] . ' ' . $values['UserModel']['last_name'];
        }
        $this->set('supervisors', $supervisors);
        // For fetching managers
        $manager_options = array('conditions' => array('RoleMaster.role_name' => 'Manager', 'UserModel.status' => 1));
        $manager_all = $this->getModel()->find('all', $manager_options);
        $managers = array();
        foreach ($manager_all as $key => $values) {
            $managers[$key]['id'] = $values['UserModel']['id'];
            $managers[$key]['name'] = $values['UserModel']['first_name'] . ' ' . $values['UserModel']['last_name'];
        }
        $this->set('managers', $managers);        
        if ($this->request->is('post')) {
            if ($this->request->data['UserModel']['role_id'] == 3) {
                $this->getModel()->validator()->remove('supervisor_id');
            }
            

            /**
             * Extra code for add
             * @category API
             */
            $this->getModel()->create();
            if ($this->getModel()->save($this->request->data)) {
                $message = $this->getModel()->findById($this->getModel()->id);
            } else {
                $message = $this->getModel()->validationErrors;
            }
        }
        /**
         * Serialize data
         * @category API
         */
        $this->set(array('data' => $message));
    }

    /**
     * Edit Users
     * @category API
     * @param null
     * @return void
     */
    public function edit($id = null)
    {

        $options = array('conditions' => array('UserModel.' . $this->getModel()->primaryKey => $id));
        $oldData = $this->getModel()->find('first', $options);

        $roles_all = $this->RoleMaster->find('all', array(
            'conditions' => array('OR' => array('RoleMaster.status' => 1,
                                'RoleMaster.id' => $oldData['UserModel']['role_id']
                            )),
            'order' => array('RoleMaster.id' => 'asc')
        ));
        foreach ($roles_all as $key => $values) {
            $roles[$values['RoleMaster']['id']] = $values['RoleMaster']['role_name'];
        }
        $this->set('roles', $roles);
        $manager_options = array('conditions' => array('RoleMaster.role_name' => 'Manager','UserModel.status' => 1));
        $manager_all = $this->getModel()->find('all', $manager_options);
        foreach ($manager_all as $key => $values) {
            $managers[$values['UserModel']['id']] = $values['UserModel']['first_name'] . ' ' . $values['UserModel']['last_name'];
        }
        $this->set('managers', $managers);

        // For fetching supervisors
        $supervisor_options = array('conditions' => array('RoleMaster.role_name' => 'Operator','UserModel.status' => 1));
        $supervisor_all = $this->getModel()->find('all', $supervisor_options);
        foreach ($supervisor_all as $key => $values) {
            $supervisors[$values['UserModel']['id']] = $values['UserModel']['first_name'] . ' ' . $values['UserModel']['last_name'];
        }
        $this->set('supervisors', $supervisors);
        if (count($oldData['UserManager']) > 0) {
            foreach ($oldData['UserManager'] as $keys => $vals) {
                $managers_pro[] = $vals['manager_id'];
            }
        }
                
        $config = $this->getDefaults();
        if (!$this->getModel()->exists($id)) {
            throw new Exception(__('Invalid AR Type. Please select proper AR Type first.'), 401);
        }
        if ($this->request->is(array('post', 'put'))) {
            $sp_array = array('in_user_id' => $id, 'in_supervisor_id' => $this->request->data['UserModel']['supervisor_id']);
            $result = $this->General->getDataFromSP($sp_array);
            $parentIds = explode(',', $result[0][0]['var_supervisor_list']);
            if (in_array($id, $parentIds)) {
                $options = array('conditions' => array('UserModel.' . $this->UserModel->primaryKey => $id));
                $this->request->data = $this->getModel()->find('first', $options);
                $this->set('userdata', $this->request->data);
                throw new Exception(__($this->config['messages']['failSupervisor']));
                $this->set('var', 1);
                return false;
            }
            if ($this->request->data['UserModel']['role_id'] == 3) {
                $this->getModel()->validator()->remove('supervisor_id');
            }
            $data = $this->request->data;

            /**
             * Extra code for add
             * @category API
             */
            $this->getModel()->id = $id;
            if ($this->getModel()->save($this->request->data)) {
                $message = $this->getModel()->findById($this->getModel()->id);
            } else {
                $message = $this->getModel()->validationErrors;
            }
            $this->set(array('data' => $message));
        }
    }

    /**
     * Delete Users
     * @category API
     * @param null
     * @return void
     */
    public function delete($id = null)
    {

        if (!isset($this->request->data['bulk_process_ids']) && $id <= 0) {
            throw new MethodNotAllowedException();
        }

        $this->getModel()->id = $id;
        if (!$this->getModel()->exists()) {
            throw new NotFoundException(__('Invalid Record'));
        }

        if (isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);


        $tables = array('Post' => 'created_by',
            'TagMaster' => 'created_by',
            'UserManager' => 'manager_id',
            'UserModel' => 'supervisor_id'
        );
        $validate = $this->General->validaterecords($tables, $id);

        $totalDeletedUser = 0;
        if (count($validate['valid']) > 0) {
            foreach ($validate['valid'] as $valid) {
                $this->getModel()->unbindModel(array('hasMany' => 'UserManager'));
                if ($this->getModel()->delete($valid)) {

                }
            }
            $totalDeletedUser = count($validate['valid']);
        }
        if (count($validate['invalid']) > 0) {
            $totalNotDeletedUser = count($validate['invalid']);
        }


        if (count($validate['valid']) > 0 && count($validate['invalid']) > 0) {
            $message = $totalDeletedUser . ' user(s) has been deleted <br>
                    ' . $totalNotDeletedUser . ' user(s) has not been deleted';

        }

        if (count($validate['valid']) > 0 && count($validate['invalid']) == 0) {
            $message = $totalDeletedUser . ' user(s) has been deleted';
        }

        if (count($validate['invalid']) > 0 && count($validate['valid']) == 0) {
            $message = $totalNotDeletedUser . ' user(s) has not been deleted';
        }

        $config = $this->getDefaults();        
        $this->set(array('data' => $message));
    }

    /**
     * Change Password API
     * @param int $id userid
     * @return null
     * @category API
     */
    public function changepassword($id = null)
    {

        $id = $this->Auth->user('id');
        /** Get logged in user password * */
        $current_user_profile = $this->getModel()->find('first', array('conditions' => array('UserModel.id' => $id)));
        $user_password = $current_user_profile['UserModel']['password'];
        $config = $this->getDefaults();
        /** Request is Post - Change Password button click * */
        if ($this->request->is(array('post'))) {
            if ($this->request->data['UserModel']['new_password'] != $this->request->data['UserModel']['confirm_password']) {
                throw new Exception(__($this->config['messages']['passwordError']), 401);
            }
            if ($this->request->data['UserModel']['security_question'] != $questionId || $this->request->data['UserModel']['answers'] != $answer) {
                throw new Exception(__($this->config['messages']['passwordError']), 401);
            }
            $config = $this->getDefaults();
            /** Encrypt New Password * */
            $new_password = Security::hash($this->request->data['UserModel']['old_password'], 'blowfish', $user_password);
            /** Compare New Password with current Password ( comparision in encrypted format ) * */
            if ($user_password === $new_password) {
                unset($this->request->data['UserModel']['old_password']);
                $this->request->data['UserModel']['password'] = $this->request->data['UserModel']['new_password'];
                if ($this->getModel()->save($this->request->data)) {
                    $this->set('data', __($this->config['messages']['passwordSuccess']));
                } else {
                    throw new Exception(__($this->config['messages']['passwordError']), 401);
                }
            } else {
                throw new Exception(__($this->config['messages']['passwordError']), 401);
            }
        }
    }

    /**
     * Forgot Password API
     * @return null
     * @category API
     */
    public function forgotpassword()
    {
        if ($this->request->is(array('post'))) {
            $user_exist = $this->getModel()->find('first', array('conditions' => array('UserModel.email' => $this->request->data['UserModel']['email'], 'UserModel.security_question_id' => $this->request->data['UserModel']['security_question'], 'UserModel.answer' => $this->request->data['UserModel']['answers'])));
            if ($user_exist) {
                $this->General->sendClaimEmail($user_exist['UserModel'], 'forgotpassword');
                $this->set('data', __($this->config['messages']['forgotsuccess']));
            } else {
                throw new Exception(__('Invalid Information'), 401);
            }
        }
    }

    /**
     * Ajax Fetch API
     * @return null
     * @category API
     */
    public function ajax_fetch()
    {

        $conditions = array();
        $search_term = false;
       
        $conditions['perPage'] = $page_count = 10;
        $conditions['pageNo'] = $page_no = 0;
        $conditions['limit'] = $page_count;
        $conditions['recursive'] = -1;
        $conditions['joins'] = array(
            array(
                'table' => 'mac_role_master',
                'alias' => 'RoleMaster',
                'type' => 'LEFT',
                'conditions' => array(
                    'RoleMaster.id = UserModel.role_id'
                )
            ),
            array(
                'table' => 'mac_user_master',
                'alias' => 'UserModel_supervisor',
                'type' => 'LEFT',
                'conditions' => array(
                    'UserModel_supervisor.id = UserModel.supervisor_id'
                )
            )
        );
        $conditions['fields'] = array('concat(UserModel_supervisor.first_name," ",UserModel_supervisor.last_name) as supervisor_name', 'UserModel.*', 'concat(UserModel.first_name," ",UserModel.last_name) as full_name', 'RoleMaster.id as RoleMaster_id', 'RoleMaster.role_name as RoleMaster_role_name');
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            //Get number of records to be shown on the grid
            $detailedconditions = array();
            if (isset($data['searchData']) && !empty($data['searchData'])) {
                parse_str($data['searchData'], $detailedconditions);
                $andconditions = array();
                $a = 0;
                $orconditions = array();
                $o = 0;
                if (isset($detailedconditions['data']) && !empty($detailedconditions['data'])) {
                    foreach ($detailedconditions['data'] as $onecondition) {
                        switch ($onecondition['operator']) {
                            case 'greaterthan' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " >"] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " >"] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'lessthan' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " <"] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " <"] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'equalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns']] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns']] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'notequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " !="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " !="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'greaterthanequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " >="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " >="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'lessthanequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " <="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " <="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'beetween' :
                                $temp = explode(',', $onecondition['value']);
                                if (count($temp) == 2) {
                                    if ($onecondition['type'] == 'AND') {
                                        $andconditions[$a][$onecondition['beetween'] . " <="] = $temp[0];
                                        $andconditions[$a][$onecondition['beetween'] . " >="] = $temp[1];
                                        $a++;
                                    } else {
                                        $orconditions[$o][$onecondition['beetween'] . " <="] = $temp[0];
                                        $orconditions[$o][$onecondition['beetween'] . " >="] = $temp[1];
                                        $o++;
                                    }
                                }
                                break;
                            case 'contain' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'Doesnotcontain' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " NOT LIKE "] = "%" . $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " NOT LIKE "] = "%" . $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'startswith' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'endswith' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'];
                                    $o++;
                                }
                                break;
                        }
                    }
                }
                if (!empty($andconditions)) {
                    $conditions['conditions'][]['AND'] = $andconditions;
                }
                if (!empty($orconditions)) {
                    $conditions['conditions'][]['OR'] = $orconditions;
                }
            }
            if (isset($data['numberRecordsPerPage']) && $data['numberRecordsPerPage'] != null)
                $page_count = $data['numberRecordsPerPage'];

            //Set order for sorting
            if (isset($data['orderSense']) && $data['orderSense'] == 1)
                $order_dir = 'ASC';
            else if (isset($data['orderSense']) && $data['orderSense'] == -1)
                $order_dir = 'DESC';

            //Set order by things to the query
            if (isset($order_dir))
                $conditions['order'] = array($data['orderBy'] => $order_dir);

            //Working for Global AJAX search featire
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $search_term = true;
                $where_conditions['UserModel.first_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.middle_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.last_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.email LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['UserModel.user_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
            //set limit on page and records per page variables
            if (isset($data['pageNumber'])) {
                $conditions['limit'] = $page_count * ($data['pageNumber'] + 1);
                $conditions['offset'] = (($data['pageNumber'] + 1) * $page_count) - $page_count;
            }
        }
        $conditions['onAjax'] = true;
       
        $userMasters = $this->Crud->listdata(array('condition' => $conditions));
        $this->set('data', $userMasters);
    }

    /**
     * Export index data API
     * @return null
     * @category API
     */
    public function export_fetch()
    {

        $conditions = array();
        $search_term = false;        
        $conditions['recursive'] = -1;
        $conditions['joins'] = array(
            array(
                'table' => 'role_master',
                'alias' => 'RoleMaster',
                'type' => 'LEFT',
                'conditions' => array(
                    'RoleMaster.id = UserModel.role_id'
                )
            ),
            array(
                'table' => 'user_master',
                'alias' => 'UserModel_supervisor',
                'type' => 'LEFT',
                'conditions' => array(
                    'UserModel_supervisor.id = UserModel.supervisor_id'
                )
            )
        );
        $conditions['fields'] = array('concat(UserModel_supervisor.first_name," ",UserModel_supervisor.last_name) as supervisor_name', 'UserModel.*', 'concat(UserModel.first_name," ",UserModel.last_name) as full_name', 'RoleMaster.id as RoleMaster_id', 'RoleMaster.role_name as RoleMaster_role_name');
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            //Get number of records to be shown on the grid
            $detailedconditions = array();
            if (isset($data['searchData']) && !empty($data['searchData'])) {
                parse_str($data['searchData'], $detailedconditions);
                $andconditions = array();
                $a = 0;
                $orconditions = array();
                $o = 0;
                if (isset($detailedconditions['data']) && !empty($detailedconditions['data'])) {
                    foreach ($detailedconditions['data'] as $onecondition) {
                        switch ($onecondition['operator']) {
                            case 'greaterthan' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " >"] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " >"] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'lessthan' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " <"] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " <"] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'equalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns']] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns']] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'notequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " !="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " !="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'greaterthanequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " >="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " >="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'lessthanequalto' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " <="] = $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " <="] = $onecondition['value'];
                                    $o++;
                                }
                                break;
                            case 'beetween' :
                                $temp = explode(',', $onecondition['value']);
                                if (count($temp) == 2) {
                                    if ($onecondition['type'] == 'AND') {
                                        $andconditions[$a][$onecondition['beetween'] . " <="] = $temp[0];
                                        $andconditions[$a][$onecondition['beetween'] . " >="] = $temp[1];
                                        $a++;
                                    } else {
                                        $orconditions[$o][$onecondition['beetween'] . " <="] = $temp[0];
                                        $orconditions[$o][$onecondition['beetween'] . " >="] = $temp[1];
                                        $o++;
                                    }
                                }
                                break;
                            case 'contain' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'Doesnotcontain' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " NOT LIKE "] = "%" . $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " NOT LIKE "] = "%" . $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'startswith' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = $onecondition['value'] . "%";
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = $onecondition['value'] . "%";
                                    $o++;
                                }
                                break;
                            case 'endswith' :
                                if ($onecondition['type'] == 'AND') {
                                    $andconditions[$a][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'];
                                    $a++;
                                } else {
                                    $orconditions[$o][$onecondition['columns'] . " LIKE "] = "%" . $onecondition['value'];
                                    $o++;
                                }
                                break;
                        }
                    }
                }
                if (!empty($andconditions)) {
                    $conditions['conditions'][]['AND'] = $andconditions;
                }
                if (!empty($orconditions)) {
                    $conditions['conditions'][]['OR'] = $orconditions;
                }
            }
            if (isset($data['numberRecordsPerPage']) && $data['numberRecordsPerPage'] != null)
                $page_count = $data['numberRecordsPerPage'];

            //Set order for sorting
            if (isset($data['orderSense']) && $data['orderSense'] == 1)
                $order_dir = 'ASC';
            else if (isset($data['orderSense']) && $data['orderSense'] == -1)
                $order_dir = 'DESC';

            //Set order by things to the query
            if (isset($order_dir))
                $conditions['order'] = array($data['orderBy'] => $order_dir);

            //Working for Global AJAX search featire
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
        $conditions['onAjax'] = true;
       
        $userMasters = $this->Crud->listdata(array('condition' => $conditions), true);
        $this->set('data', $userMasters);
    }

}