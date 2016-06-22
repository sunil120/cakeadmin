<?php

/**
 * General Component
 *
 * @package     App.Controller.Component
 * @subpackage  General
 */
App::uses('Component', 'Controller');

class GeneralComponent extends Component
{
    /**
     * Load module component
     */
    public $components = array('Module', 'Auth', 'Session');

    /**
     * Define Controller Object
     * @var Controller
     */
    public $controller;

    /**
     * Initialize general component
     * @param Controller $controller
     * @param array $settings
     */
    public function initialize(Controller $controller, $settings = array())
    {
        $this->controller = $controller;
    }

    /**
     * Check Permission
     * @param string $controller
     * @param string $action
     */
    public function checkPermission($controller, $action)
    {
        $controller = strtolower(str_replace('Controller', '', $controller));
        $controller = str_replace('_', '', $controller);
        $action = strtolower($action);
        $definedPermissions = $this->getDefinedPermissions();
        if (isset($definedPermissions[$controller][$action]) && !isset($definedPermissions[$controller][$action]['always'])) {
            $alias = $definedPermissions[$controller][$action]['alias'];
            $permission = $definedPermissions[$controller][$action]['permission'];
            $this->Module->checkModulePermission($alias, $permission, true);
        }
    }

    /**
     * Define all the permissions for controllers and actions
     * @return array
     */
    public function getDefinedPermissions()
    {
        return array('ajax' => array('index' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'getmodelobject' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'ajax_fetch' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'ajax_delete' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ),),
            'api' => array('index' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'add' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'view' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'edit' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'delete' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ),),
            'dashboard' => array('index' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ),),
            'permission' => array('user' => array(
                    'alias' => 'user_and_roles_users',
                    'permission' => 'EDIT_PERMISSION'
                ), 'role' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'EDIT_PERMISSION'
                ), 'save' => array(
                    'alias' => 'user_and_roles_users',
                    'permission' => array('OR' => array('ADD', 'EDIT'))
                ), 'clearpermission' => array(
                    'alias' => 'user_and_roles_users',
                    'permission' => 'EDIT_PERMISSION'
                ),
                ),
            'rolemasters' => array('index' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'VIEW'
                ), 'ajax_fetch' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'VIEW'
                ), 'onbeforelistdata' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'VIEW'
                ), 'add' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'ADD'
                ), 'onbeforesavedata' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'edit' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'EDIT'
                ), 'delete' => array(
                    'alias' => 'user_and_roles_roles',
                    'permission' => 'DELETE'
                ),),
            'settings' => array('index' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'add' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'edit' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'delete' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ), 'ajax_fetch' => array(
                    'alias' => 'indexalias',
                    'permission' => 'indexpermission',
                    'always' => true
                ),),
            'template' => array('index' => array(
                   'alias' => 'cms_template',
                   'permission' => 'VIEW'
               ), 'add' => array(
                   'alias' => 'cms_template',
                   'permission' => 'ADD'
               ), 'edit' => array(
                   'alias' => 'cms_template',
                   'permission' => 'EDIT'
               ), 'delete' => array(
                   'alias' => 'cms_template',
                   'permission' => 'DELETE'
               ), 'ajax_fetch' => array(
                   'alias' => 'cms_template',
                   'permission' => 'VIEW'
               ),
            ),
            'discount' => array('index' => array(
                   'alias' => 'quote_discount',
                   'permission' => 'VIEW'
               ), 'add' => array(
                   'alias' => 'quote_discount',
                   'permission' => 'ADD'
               ), 'edit' => array(
                   'alias' => 'quote_discount',
                   'permission' => 'EDIT'
               ), 'delete' => array(
                   'alias' => 'quote_discount',
                   'permission' => 'DELETE'
               ), 'ajax_fetch' => array(
                   'alias' => 'quote_discount',
                   'permission' => 'VIEW'
               ),
            ),
        );
    }


    public function validaterecords($tables = array(), $ids)
    {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $validids = $ids;
        $returnarray['valid'] = array();
        $returnarray['invalid'] = array();
        foreach ($tables as $k => $v) {
            $obj = ClassRegistry::init($k);
            $fetchdata = $obj->find('all', array('conditions' => array($v => $ids), 'recursive' => -1, 'group' => $v, 'fields' => $v));
            if (!empty($fetchdata)) {
                $fetchids = Set::classicExtract($fetchdata, '{n}.' . $k . '.' . $v . '');
                $validids = array_values(array_diff($validids, $fetchids));
            }
        }
        $returnarray['valid'] = $validids;
        $returnarray['invalid'] = array_values(array_diff($ids, $validids));
        return $returnarray;
    }

    public function _uploadFiles($folder, $formdata, $htmlElement = null, $itemId = null)
    {
        // setup dir names absolute and relative
        $folder_url = WWW_ROOT . $folder;
        $rel_url = $folder;

        // create the folder if it does not exist
        if (!is_dir($folder_url)) {
            mkdir($folder_url);
        }
        // if itemId is set create an item folder
        if ($itemId) {
            // set new absolute folder
            $folder_url = WWW_ROOT . $folder . '/' . $itemId;
            // set new relative folder
            $rel_url = $folder . '/' . $itemId;
            // create directory
            if (!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }
        // list of permitted file types, this is only images but documents can be added
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
        // loop through and deal with the files
        if ($htmlElement == null) {
            foreach ($formdata as $file) {
                // replace spaces with underscores
                $filename = str_replace(' ', '_', $file['name']);
                // assume filetype is false
                $typeOK = false;
                // check filetype is ok
                foreach ($permitted as $type) {
                    if ($type == $file['type']) {
                        $typeOK = true;
                        break;
                    }
                }
                // if file type ok upload the file
                if ($typeOK) {
                    // switch based on error code
                    switch ($file['error']) {
                        case 0:
                            // check filename already exists
                            if (!file_exists($folder_url . '/' . $filename)) {
                                // create full filename
                                $full_url = $folder_url . '/' . $filename;
                                $url = $rel_url . '/' . $filename;
                                // upload the file
                                $success = move_uploaded_file($file['tmp_name'], $url);
                            } else {
                                // create unique filename and upload file
                                ini_set('date.timezone', 'Europe/London');
                                $now = date('Y-m-d-His');
                                $full_url = $folder_url . '/' . $now . $filename;
                                $url = $rel_url . '/' . $now . $filename;
                                $success = move_uploaded_file($file['tmp_name'], $url);
                            }
                            // if upload was successful
                            if ($success) {
                                // save the url of the file
                                $result['urls'][] = $url;
                            } else {
                                $result['errors'][] = "Error uploaded $filename. Please try again.";
                            }
                            break;
                        case 3:
                            // an error occured
                            $result['errors'][] = "Error uploading $filename. Please try again.";
                            break;
                        default:
                            // an error occured
                            $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                            break;
                    }
                } elseif ($file['error'] == 4) {
                    // no file was selected for upload
                    $result['nofiles'][] = "No file Selected";
                } else {
                    // unacceptable file type
                    $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
                }
            }
        } else {
            // replace spaces with underscores
            $file = $formdata[$htmlElement];
            $filename = str_replace(' ', '_', $file['name']);

            // assume filetype is false
            $typeOK = false;
            // check filetype is ok
            foreach ($permitted as $type) {
                if ($type == $file['type']) {
                    $typeOK = true;
                    break;
                }
            }
            // if file type ok upload the file
            if ($typeOK) {
                // switch based on error code
                switch ($file['error']) {
                    case 0:
                        // check filename already exists
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename
                            $full_url = $folder_url . '/' . $filename;
                            $url = $rel_url . '/' . $filename;
                            // upload the file
                            $success = move_uploaded_file($file['tmp_name'], $url);
                        } else {
                            // create unique filename and upload file
                            ini_set('date.timezone', 'Europe/London');
                            $now = date('Y-m-d-His');
                            $full_url = $folder_url . '/' . $now . $filename;
                            $url = $rel_url . '/' . $now . $filename;
                            $success = move_uploaded_file($file['tmp_name'], $url);
                        }
                        // if upload was successful
                        if ($success) {
                            // save the url of the file
                            $result['urls'][] = $url;
                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif ($file['error'] == 4) {
                // no file was selected for upload
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        }
        return $result;
    }

    public function getDataFromSP($param_array)
    {

        /* $model = $this->getModel();
          $db = $model->getDataSource();

          $result = $db->fetchAll(
          "CALL sp_get_child_user(:in_user_id, :in_supervisor_id)", $param_array
          );
          $model->clear();
         */

        return null;
    }

    /**
     * Returns Friendly name for Action
     *
     * @return string
     */
    public function getTitleForAction($action)
    {
        $actionList = $this->getActonTitleList();
        if (array_key_exists($action, $actionList)) {
            return $actionList[$action];
        }
        return $action;
    }

    /**
     * Returns list of Action Names
     *
     * @return array
     */
    public function getActonTitleList()
    {
        return array(
            'index' => $this->title_for_layout . ' List',
            'add' => 'Add ' . $this->title_for_layout,
            'edit' => 'Edit ' . $this->title_for_layout,
            'view' => $this->title_for_layout . ' View',
        );
    }

    public function _getSettings()
    {
        $SettingsModel = ClassRegistry::init('Settings');
        $settings_data = $SettingsModel->find('first');
        return ($settings_data);
    }

    public function _getLoggedUserInfo()
    {
        $loggedUser = null;
        if ($this->Auth->loggedIn()) {
            $loggedUser = $this->Auth->user();
            SessionUser::setCurrentUser($loggedUser);
        }

        return $loggedUser;
    }

    public function _getListingTable()
    {
        $table = null;
        if ($this->_listingTable && $this->DataTable) {
            App::uses($this->_listingTable, 'Lib/Table');
            $table = $this->_listingTable;
            $table = new $table();
        }
        return $table;
    }

    /**
     * Check Model has Search feature
     *
     * @param AppModel $model
     * @return boolean
     */
    public function _hasSearchable($model, $searchBehaviour = 'Searchable')
    {
        return $model->Behaviors->loaded($searchBehaviour) && $model->filterArgs;
    }

    public function _redirectToPage($controller, $action, $msg = null)
    {

        if ($msg == null) {
            $msg = "Unknown error occured.";
        }
        $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
        $this->Session->setFlash(__('<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                     ' . $msg . '
                                </div>'), null, $params);
        return $this->controller->redirect(array('controller' => $controller, 'action' => $action));
    }

    /**
     * Success flash message
     *
     * @param type $msg
     * @return html
     */
    public function successMsg($msg)
    {
        return '<div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        ' . $msg . '
                </div>';
    }

    /**
     * Error flash message
     *
     * @param type $msg
     * @return html
     */
    public function errorMsg($msg = '')
    {
        if ($msg == "") {
            // global error message
            $msg = Configure::read('errorMsg');
        }

        return '<div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        ' . $msg . '
                </div>';
    }

    /**
     * Delete file
     *
     * @param type $filepath
     * @return boolean
     */
    public function deleteFile($filepath)
    {
        $file = new File($filepath, false, 0777);
        if ($file->delete()) {
            return true;
        }
        return false;
    }

    /**
     * advanceFilter
     * Filter the Values
     *
     * @param $data - all fetched data array/object
     * @param $conditions - Required Condition for filter data
     * @return array
     * @author: Maulik Panchal
     * @date: 1/4/2016
     */
    public function advanceFilter($data, $conditions = array()) {
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
                        $onecondition['columns'] = $this->getModel()->name . '.' . $onecondition['columns'];
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
}