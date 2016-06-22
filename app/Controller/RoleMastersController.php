<?php

/**
 * Role Controller
 *
 * Role module related actions.
 *
 * @package     App.Controller
 * @subpackage  RoleMastersController
 */
App::uses('AppController', 'Controller');

/**
 * Role Controller
 *
 * Role module related actions.
 *
 * @package     App.Controller
 * @subpackage  RoleMastersController
 */
class RoleMastersController extends AppController
{
    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;
    //Load needful models
    public $uses = array('RoleMaster', 'UserModel');

    /**
     * Before filter callback
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('controllerName', 'Role');
    }

    /**
     * Role list action.
     */
    public function index()
    {

        $options = array('conditions' => array('RoleMaster.status' => '0'));
        $this->set('roles', $this->getModel()->find('all', $options));
    }

    /**
     * get model object for this class.
     * @return object RoleMaster
     */
    public function getModel()
    {
        return $this->RoleMaster;
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

        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            // for Global AJAX search filter
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $where_conditions['RoleMaster.role_name LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['RoleMaster.description LIKE'] = "%" . $data['searchTerm'] . "%";
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
        }

        $roleMasters = $this->Crud->listdata(array('find_configs' => $conditions), $isexport);

        $this->autoRender = false;
        echo $roleMasters;
        exit();
    }

    /**
     * export data method
     * @throws NotFoundException
     * @return json array
     */
    public function export_fetch()
    {
        $this->Paginator->settings['fields'] = array(
            'RoleMaster.role_name as RoleName',
            'RoleMaster.description as Description',
            'RoleMaster.status as IsActive',
        );
        $this->ajax_fetch(true);
    }

    /**
     * Do related needful operation on before list data.
     * @param array $data
     * @param RoleMaster $model
     * @return array
     */
    public function onBeforeListData($data, $model)
    {
        $result = array();
        foreach ($data as $item) {
            $item['RoleMaster']['status'] = ($item['RoleMaster']['status'] == 1) ? "Active" : "Inactive";
            $result[] = $item;
        }

        return $result;
    }
    


    /**
     * Do related needful operation on before export data.
     * @param array $data
     * @param UserModel $model
     * @return array
     */
    public function onBeforeExportData($data, $model)
    {
        $result = array();
        foreach ($data as $item) {
            $item['RoleMaster']['IsActive'] = ($item['RoleMaster']['IsActive'] == 1) ? "True" : "False";
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Role add action.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
        $this->set('Action', 'Add');
    }

    /**
     * Do related needful operation on before save data.
     * @param object RoleMaster $data
     * @param type $model
     * @return array
     */
    public function onBeforeSaveData($data, $model)
    {
        return $data;
    }

    /**
     * Role edit action.
     * @param int $id
     */
    public function edit($id = null)
    {

        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data); exit;
            $data = $this->request->data;
            $this->Crud->edit($id);
        } else {
            $options = array('conditions' => array('RoleMaster.' . $this->getModel()->primaryKey => $id));
            $this->request->data = $this->getModel()->find('first', $options);
            $this->set('rolemaster', $this->request->data);
        }
        #to set redirect url from permission module
        $this->Session->write('http_referer', $this->here);
    }

    /**
     * Role delete action.
     * @param int $id
     */
    public function delete($id = null)
    {

        if (!isset($this->request->data['bulk_process_ids']) && $id <= 0) {
            throw new MethodNotAllowedException();
        }

        if (isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);
        $tables = array('UserModel' => 'role_id');
        $validate = $this->General->validaterecords($tables, $id);
        
        $totalDeletedUser = 0;
        if (count($validate['valid']) > 0) {
            foreach ($validate['valid'] as $valid) {
                if ($this->getModel()->delete($valid)) {

                }
            }
            $totalDeletedUser = count($validate['valid']);
        }
        if (count($validate['invalid']) > 0) {
            $totalNotDeletedUser = count($validate['invalid']);
        }

        if (count($validate['valid']) > 0 && count($validate['invalid']) > 0) {
            $msg = $totalDeletedUser . ' role(s) has been deleted <br>
                    ' . $totalNotDeletedUser . ' role(s) has not been deleted as there are users in this role';
            $this->Session->setFlash(__($this->General->errorMsg($msg)));
        }

        if (count($validate['valid']) > 0 && count($validate['invalid']) == 0) {
            $this->Session->setFlash(__($this->General->successMsg($totalDeletedUser . ' role(s) has been deleted')));
        }

        if (count($validate['invalid']) > 0 && count($validate['valid']) == 0) {
            $msg = $totalNotDeletedUser . ' role(s) has not been deleted as there are users in this role';
            $this->Session->setFlash(__($this->General->errorMsg($msg)));
        }

        return $this->redirect(array('action' => 'index'));
    }

    /**
     * change status to active or inactive
     */
    public function changestatus($id, $status)
    {
        $this->autoRender = FALSE;
        if ($status == 'false') {
            $new_status = 1;
            $msg = "Role status has been changed to active";
        } else {
            $new_status = 0;
            $msg = "Role status has been changed to inactive";
        }

        if ($this->getModel()->updateAll(array('RoleMaster.status' => $new_status), array('RoleMaster.id' => $id))) {
            $this->Session->setFlash(__($this->General->successMsg($msg)));
        } else {
            $this->Session->setFlash(__($this->General->errorMsg()));
        }

        return $this->redirect(array('action' => 'index'));
    }

}