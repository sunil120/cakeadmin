<?php

/**
 * CRUD Component
 * 
 * CRUD operation function e.g add,edit,list,etc.
 * 
 * @package     App.Controller.Component 
 * @subpackage  Crud
 */
App::uses('Component', 'Controller');

/**
 * CRUD Component.
 *
 * CRUD operation function e.g add,edit,list,etc.
 *
 * @package     App.Controller.Component 
 * @subpackage  Crud
 *
 */
class CrudComponent extends Component
{

    /**
     * Define Controller Object
     * @var Controller
     */
    public $controller;

    /**
     * Initialize crud component
     * @param Controller $controller
     * @param array $settings
     */
    public function initialize(Controller $controller, $settings = array())
    {
        $this->controller = $controller;
    }

    /**
     * Listdata.
     * Function to get listing data.
     * @param array $config
     * @param boolean $allData
     * @return array | json 
     */
    public function listdata($config = array(), $allData = false , $recursive = 1)
    {
        
        //allow to pass and override config options
        //default cofig option
        $default_config = array('recursive' => $recursive, 'onAjax' => true);
        if ($allData == false) {
            $default_config_pagination = array('perPage' => 10, 'pageNo' => 0, 'limit' => 10);
            $default_config = $default_config + $default_config_pagination;
        }

        //override default cofig option if not passed.
        $config = $config + $default_config;
        
        if ($allData == false) {
            //make sure default it will take first page and set limit during getting data instead of loading all data.
            $config['find_configs']['limit'] = $config['perPage'];
            $config['find_configs']['offset'] = (($config['pageNo'] + 1) * $config['perPage']) - $config['perPage'];
        }

        if ($this->controller->request->isget() && $this->controller->request->query != null) {
            $data = $this->controller->request->query;
            //Get number of records to be shown on the grid
            $advanceCondition = $this->advanceFilter($data, $config['find_configs']);
            if (!empty($advanceCondition)) {
                $config['find_configs'] = array_merge($config['find_configs'], $advanceCondition);
            }

            // Override perPage count            
            if(isset($data['numberRecordsPerPage']) && $data['numberRecordsPerPage'] != null)
                $config['perPage'] = $data['numberRecordsPerPage'];

            //Set order for sorting
            if(isset($data['orderSense']) && $data['orderSense'] == 1)
                $order_dir = 'ASC';
            else if(isset($data['orderSense']) && $data['orderSense'] == -1)
                $order_dir = 'DESC';

            //Set order by things to the query
            if(isset($order_dir))
                $config['find_configs']['order'] = array($data['orderBy'] => $order_dir);

            //set limit on page and records per page variables
            if(isset($data['pageNumber']) && $allData == false){
                $config['find_configs']['limit'] = $config['perPage'];
                $config['find_configs']['offset']= (($data['pageNumber']+1) * $config['perPage']) - $config['perPage'];
            }
        }
        // End default override config code


        $conditions = $config['find_configs'];
        $isAjax = $config['onAjax'];
        if (isset($config['find_configs']['action'])) {
            $model = $this->controller->getModel($config['find_configs']['action']);
        } else {
            $model = $this->controller->getModel();
        }

        $recursive = (isset($config['recursive']) ? $config['recursive'] : 2);
        $conditions = array_merge($conditions, array('recursive' => $recursive));
        if (!empty($this->controller->Paginator->settings['fields'])) {
            $conditions['fields'] = $this->controller->Paginator->settings['fields'];
        }
        $conditions['order'] = array(get_class($model).".id"=>'DESC');
        $records = $model->find('all', $conditions);

        unset($conditions['limit']);
        /* Note:
         * Commenting below line because its returning 'FALSE' if any fields
         * contains 'count' word like field name 'country_name'. 
         * PROBLEM: in CakePHP core file Model.php -> _findCount() function
         * -> $total_records = $model->find('count', $conditions);
         */
        $total_records = count($model->find('all', $conditions));

        if ($allData == false) {
            if (method_exists($this->controller, 'onBeforeListData')) {
                $records = $this->controller->onBeforeListData($records, $model);
            }
        } else {
            if (method_exists($this->controller, 'onBeforeExportData')) {
                $records = $this->controller->onBeforeExportData($records, $model);
            }
        }
        if ($model->name != 'ProjectMaster') {
            $listing = array();
            foreach ($records as $record) {
                array_push($listing, $record[$model->name]);
            }

            $return_all['data'] = $listing;
        } else {
            
        $return_all['data'] = $records;
        }

        $return_all['recordsNumber'] = $total_records;
        
        
        if ($isAjax)
            return json_encode($return_all);
        else
            return $return_all;
    }

    /**
     * Add.
     * Function to add data.
     * @param array $config 
     */
    public function add($config = array())
    {
       

        $config += $this->controller->getDefaults();
       
        $model = $this->controller->getModel();
        if (!($model instanceof AppModel)) {
            throw new NotFoundException(__('Model Not found'));
        }

        if ($this->controller->request->is(array('post', 'put')) || isset($config['submit'])) {
            $model->create();
            $data = $this->controller->request->data;

            if (method_exists($this->controller, 'onBeforeSaveData')) {
                $data = $this->controller->onBeforeSaveData($data, $model);
            }

            if ($model->saveAll($data, array('deep' => true))) {
                if (method_exists($this->controller, 'onSaveSuccess')) {
                    $this->controller->onSaveSuccess($data, $model);
                }
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_SUCCESS);
                $this->controller->Session->setFlash(__($config['messages']['saveSucces']), null, $params);
                $this->_redirectToConfig($config);
            } else {

                $errors = isset($model->validationErrors['project_name']) ? $model->validationErrors['project_name'] : '';
                if (!empty($errors)) {
                    // debug($model->validationErrors);exit;
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->controller->Session->setFlash(__($config['messages']['saveError']), null, $params);
                } else {

                    // debug($model->validationErrors);exit;
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->controller->Session->setFlash(__($config['messages']['saveError']), null, $params);
                }
            }
        }
    }

    /**
     * Edit.
     * Function to edit data.
     * @param int $id
     * @param array $config
     */
    public function edit($id, $config = array())
    {
        $config += $this->controller->getDefaults();
        $model = $this->controller->getModel();
        if (!($model instanceof Model)) {
            throw new NotFoundException(__('Model Not found'));
        }
        if (!($modelData = $model->findById($id))) {
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['recordNotFound']), null, $params);
            $this->_redirectToConfig($config);
        }
        if ($this->controller->request->is('post') || $this->controller->request->is('put') || isset($config['submit'])) {
            $data = $this->controller->request->data;
            if (method_exists($this->controller, 'onBeforeSaveData')) {
                $data = $this->controller->onBeforeSaveData($data, $model);
            }
            if ($model->saveAll($data)) {
                if (method_exists($this->controller, 'onSaveSuccess')) {
                    $this->controller->onSaveSuccess($data, $model);
                }
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_SUCCESS);
                $this->controller->Session->setFlash(__($config['messages']['saveSucces']), null, $params);
                $this->_redirectToConfig($config);
            } else {
                $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                $this->controller->Session->setFlash(__($config['messages']['saveError']), null, $params);
            }
        } else {
            if (method_exists($this->controller, 'onBeforeAssignData')) {
                $this->controller->onBeforeAssignData($modelData, $model);
            }
            $this->controller->request->data = $modelData;
        }
    }

    /**
     * Delete.
     * Function to delete data.
     * @param int $id
     * @param array $config
     */
    public function delete($id, $config = array())
    {
        $config += $this->controller->getDefaults();
        $model = $this->controller->getModel();
        if (!($model instanceof Model)) {
            throw new NotFoundException(__('Model Not found'));
        }
        if (!($modelData = $model->findById($id))) {
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['recordNotFound']), null, $params);
            $this->_redirectToConfig($config);
        }
        $model->set($modelData);
        if ($model->delete($id)) {
            if (method_exists($this->controller, 'onDeleteSuccess')) {
                $this->controller->onDeleteSuccess($id, $model);
            }
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_SUCCESS);
            $this->controller->Session->setFlash(__($config['messages']['deleteSuccess']), null, $params);
        } else {
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['deleteError']), null, $params);
        }
        $this->_redirectToConfig($config);
    }

    /**
     * View.
     * Function to get and set view data.
     * @param int $id
     * @param array $config
     */
    public function view($id, $config = array())
    {
        $config += $this->controller->getDefaults();
        $model = $this->controller->getModel();
        if (!($model instanceof Model)) {
            throw new NotFoundException(__('Model Not found'));
        }
        if (!($modelData = $model->findById($id))) {
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['recordNotFound']), null, $params);
            $this->_redirectToConfig($config);
        }
        $config += array('assignTo' => strtolower($model->name));
        if (method_exists($this->controller, 'onBeforeAssignData')) {
            $this->controller->onBeforeAssignData($modelData, $model);
        }
        $assignTo = !empty($config['assignTo']) ? $config['assignTo'] : strtolower($model->name);
        $this->controller->set($assignTo, $modelData);
    }

    /**
     * AdvanceFilter.
     * Function to get / prepare advance filter conditions.
     * @param array $data
     * @param array $conditions
     * @return array 
     */
    public function advanceFilter($data, $conditions)
    {
        $detailedconditions = array();
        if (isset($data['searchData']) && !empty($data['searchData'])) {
            parse_str($data['searchData'], $detailedconditions);
            $andconditions = array();
            $a = 0;
            $orconditions = array();
            $o = 0;
            if (isset($detailedconditions['data']) && !empty($detailedconditions['data'])) {
                foreach ($detailedconditions['data'] as $onecondition) {
                    if (strpos($onecondition['columns'], '.') === false) {
                        $onecondition['columns'] = $this->controller->getModel()->name . '.' . $onecondition['columns'];
                    }
                   
                    if ($onecondition['columns'] == 'Template.status' ){
                        if (strtolower($onecondition['value']) == 'yes') {
                            $onecondition['value'] = 1;
                        } else {
                            $onecondition['value'] = 0;
                        }
                    }
                    
                    if (preg_match('/status/i',$onecondition['columns'])) {
                        if (strtolower($onecondition['value']) == 'active' || strtolower($onecondition['value']) == 'yes') {
                            $conditions['conditions']['AND'][$onecondition['columns']] = 1;
                        } else {
                            $conditions['conditions']['AND'][$onecondition['columns']] = 0;
                        }
                        return $conditions;
                    }

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

            return $conditions;
        }
    }

    /**
     * getExportData.
     * Function to get / prepare export data.
     * @param array $results
     * @param array $configAvoidFields
     * @param string $modelName
     * @return array
     */
    public function getExportData($results, $configAvoidFields, $modelName, $extraConfig = array())
    {
        $data = array();
        if ($results) {
            $conditions = array();
            foreach ($results as $key => $array) {
                if ($array) {
                    foreach ($array as $subkey => $value) {
                        if (!empty($configAvoidFields)) {
                            foreach ($configAvoidFields as $configValue) {
                                unset($value[$configValue]);
                            }
                        }

                        //export_column_display_name will override column name display in export file instead of given alias with query.
                        if (!empty($extraConfig['export_column_display_name']) && !empty($value)) {
                            foreach ($value as $column_name => $column_value) {
                                if (isset($extraConfig['export_column_display_name'][$column_name])) {
                                    unset($value[$column_name]);
                                    $value[$extraConfig['export_column_display_name'][$column_name]] = $column_value;
                                }
                            }
                        }

                        #remove null from export data
                        $value2 = array();
                        if (!empty($value)) {
                            foreach ($value as $k => $v) {
                               $value2[$k] =  (empty($v))?"":$v;
                            }
                        }

                        $conditions = array_merge($conditions, $value2);
                    }
                    $data[$key] = array($modelName => $conditions);
                }
            }
        }
        return $data;
    }

    /**
     * ChangeStatus.
     * Function to change status of given Filed if value is 1 then it will update to 0 vice-versa.
     * @param int $status
     * @param int $id
     * @param array $options
     */
    public function changeStatus($status, $id, $options = array())
    {
        $options += array(
            'key' => 'id',
            'statusKey' => 'flag'
        );


        $config = $this->controller->getDefaults();
        $model = $this->controller->getModel();
        if (!($modelData = $model->findById($id))) {
            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['recordNotFound']), null, $params);
            $this->_redirectToConfig($config);
        }

        $data[$options['key']] = $id;
        if ($status == AppModel::STATUS_ACTIVATE) {
            $data[$options['statusKey']] = 1;
        } elseif ($status == AppModel::STATUS_DEACTIVATE) {
            $data[$options['statusKey']] = 0;
        }


        if (method_exists($this->controller, 'onBeforeStatusChange')) {
            $this->controller->onBeforeStatusChange($data, $model);
        }

        if ($model->save($data)) {
            if (method_exists($this->controller, 'onStatusChangeSuccess')) {

                $model->set($modelData);
                $this->controller->onStatusChangeSuccess($data, $model);
            }

            $params = array('messageType' => AppConstants::MESSAGE_TYPE_SUCCESS);
            $this->controller->Session->setFlash(__($config['messages']['saveSucces']), null, $params);
        } else {

            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            $this->controller->Session->setFlash(__($config['messages']['saveError']), null, $params);
        }
        $this->_redirectToConfig($config);
    }

    /**
     * RedirectToConfig
     * Function for redirect based on passed config.
     * @param array $config 
     */
    public function _redirectToConfig($config)
    {
        if (isset($this->controller->request->data['ru']) || isset($this->controller->request->query['ru'])) {
            $config['redirectToUrl'] = isset($this->controller->request->data['ru']) ?
                    $this->controller->request->data['ru'] : $this->controller->request->query['ru'];
        } else {
            $redirect = array('action' => $config['redirectToAction'], 'controller' => $config['redirectToController']);
        }

        if (!empty($config['redirectToUrl'])) {
            $redirect = $config['redirectToUrl'];
        }
        $this->controller->redirect($redirect);
    }

}
