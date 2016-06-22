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
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('ConnectionManager', 'Model');
App::uses('Controller', 'Controller');
App::uses('Acl', 'Lib');
/*
 * Set API Exception responce and other before render it
 * @category API
 */
App::import(
        'Vendor', 'JWT', array('file' => 'firebase' . DS . 'php-jwt' . DS . 'Authentication' . DS . 'JWT.php')
);
App::uses('CustomForm', 'Helper');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    //load general helpers used in system.
    public $helpers = array('Html', 'Form', 'Session', 'Paginator', 'Time');
    //load general models used in system.
    public $uses = array('CountryMaster', 'Settings', 'UserModel', 'StateMaster');
    //load general components used in system.
    public $components = array(
        'Module',
        'Crud',
        'General',
        'Session',
        'Paginator',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'dashboard', 'action' => 'index'),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login',
            ),
            'All' => array('userModel' => 'UserModel'),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish',
                    'userModel' => 'UserModel',
                    'fields' => array(
                        'username' => 'user_name',
                        'password' => 'password',
                    ),
                )
            ),
            'authorize' => array('Controller') // Added this line
        ),
    );

    /**
     * Before filter callback.
     *
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->layout = AppConstants::LAYOUT;
        $this->set('theme_skin', AppConstants::SKIN);

        $loggedUser = $this->General->_getLoggedUserInfo();
        $this->set('activeUser', $loggedUser);
        //pr($this->request->url);exit;
        /*
         * avoid the api actions
         * @category API
         */
        $urlParamter = explode(DS, $this->request->url);

        if ($urlParamter[0] != 'api')
            $this->Module->setSessionMenuPermissions();

        $controllerName = strtolower(str_replace('Controller', '', str_replace('_', '', $this->request->params['controller'])));

        // define your action name in $byPassAction to bypass login checks
        $byPassAction = array('loginApi', 'login', 'forgotpassword', 'setpassword');

        if (!in_array($this->request->params['action'], $byPassAction) && $urlParamter[0] != 'api') {
            if ($loggedUser) {
                $this->General->checkPermission($this->request->params['controller'], $this->request->params['action']);

                $controllerSessionPermissions = $this->Module->getSessionControllerPermissions();
                if (isset($controllerSessionPermissions[$controllerName])) {
                    foreach ($controllerSessionPermissions[$controllerName] as $permName => $permValue) {
                        $this->set($permName . 'Access', $permValue);
                    }
                }
            } else {
                $this->redirect('/users/login');
            }
        }

        if ($this->request->params['action'] == 'login' && is_array($loggedUser) || $this->request->params['action'] == 'forgotpassword' && is_array($loggedUser) || $this->request->params['action'] == 'setpassword' && is_array($loggedUser)) {
            $this->redirect('/dashboard/index');
        }

        //Comment below line as it is not required now due to apply new approach for same, will remove later this line if all work fine with new approach.
        //$this->Auth->allow('index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'getactiontypesforproject', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'getmodelobject', 'ajax_fetch', 'ajax_delete', 'claimdetail', 'getrejectioncode', 'subordinates', 'projects', 'index', 'getprojectprocess', 'getheaderfooterdata', 'getprojectheaderfooter', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'getactiontypesforproject', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'savereminderdays', 'savetfl', 'savedtf', 'savetransferclaims', 'saveactiontypecombo', 'savehighlightclaims', 'saveotherrules', 'deletereminderday', 'deletehighlightconditionalrules', 'deleteothersconditionalrules', 'getrelatedvalues', 'export_fetch', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'arviewheaders', 'ajax_fetch', 'onbeforelistdata', 'assignrecords', 'assignproviders', 'getfieldsbytemplate', 'comparemerge', 'comparemergesubmit', 'viewrecord', 'viewrecordhistory', 'claimprocessing', 'getfirstclaimprocessing', 'arrecordedit', 'projectchange', 'export_fetch', 'onbeforeexportdata', 'delete', 'changefieldlist', 'phpexcel', 'p2ptransfertocollection', 'index', 'view', 'add', 'edit', 'delete', 'index', 'ajax_fetch', 'onbeforelistdata', 'add', 'onbeforesavedata', 'edit', 'delete', 'uploadcode', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'export_fetch', 'onbeforeexportdata', 'index', 'index', 'ajax_fetch', 'upload', 'checkerastatus', 'export_fetch', 'onbeforeexportdata', 'claimlist', 'claimdetail', 'remitsummary', 'index', 'ajax_fetch', 'onbeforelistdata', 'add', 'edit', 'delete', 'export_fetch', 'onbeforeexportdata', 'index', 'saverules', 'deleterule', 'savequestions', 'deletequestion', 'index', 'ajax_fetch', 'export_fetch', 'onbeforeexportdata', 'add', 'edit', 'delete', 'index', 'evbvviewheaders', 'ajax_fetch', 'assignrecords', 'assignproviders', 'getfieldsbytemplate', 'comparemerge', 'comparemergesubmit', 'viewrecord', 'viewrecordhistory', 'claimprocessing', 'getfirstclaimprocessing', 'edit', 'getvalidatedfields', 'authorize', 'projectchange', 'export_fetch', 'onbeforeexportdata', 'delete', 'changefieldlist', 'coveragelimitation', 'downloadcoveragelimitation', 'index', 'ajax_fetch', 'onbeforelistdata', 'assignrecords', 'getfieldsbytemplate', 'comparemerge', 'comparemergesubmit', 'viewrecord', 'viewrecordhistory', 'claimprocessing', 'getfirstclaimprocessing', 'fileauthrecordedit', 'projectchange', 'export_fetch', 'onbeforeexportdata', 'delete', 'changefieldlist', 'fileauthviewheader', 'index', 'preview_appeal', 'view_appeal', 'download_appeal', 'download_tmp_appeal', 'add_appeal', 'delete', 'add_template', 'add_efax_template', 'file_details_template', 'ajax_fetch', 'appeal_list', 'efax_list', 'ajax_efax_fetch', 'onbeforelistdata', 'index', 'ajax_fetch', 'linkprojects', 'view', 'addsimple', 'addcomplex', 'editcomplex', 'edit', 'editsimple', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'getfieldlist', 'parsesimplefile', 'savecomplexfile', 'parsecomplexfile', 'previewcomplexfile', 'subval_sort', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'export_fetch', 'onbeforeexportdata', 'importfile', 'getprocesses', 'gettemplates', 'onbeforelistdata', 'edit', 'delete', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'user', 'role', 'save', 'index', 'ajax_fetch', 'view', 'add_remote', 'add', 'onbeforesavedata', 'edit', 'ajax_delete', 'delete', 'export_fetch', 'onbeforeexportdata', 'index', 'view', 'ajax_fetch', 'onbeforelistdata', 'add', 'onbeforesavedata', 'onbeforeeditdata', 'edit', 'delete', 'ajax_delete', 'copyproject', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'delete', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'uploadcode', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'uploadcode', 'onbeforesavedata', 'onbeforelistdata', 'getinscompanyforproject', 'getinstypesforproject', 'export_fetch', 'onbeforeexportdata', 'index', 'view', 'ajax_fetch', 'export_fetch', 'onbeforelistdata', 'onbeforeexportdata', 'add', 'onbeforesavedata', 'onbeforeeditdata', 'edit', 'delete', 'ajax_delete', 'ajaxdeleteinsportal', 'ajaxdeletelocation', 'ajaxdeleteprovider', 'ajax_delete_ins_comp_sec_que', 'copyproject', 'insurance_portal_name_check', 'applyarrules', 'onsavesuccess', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'gettaskmasterslist', 'getprocessmasterslist', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'ajax_delete', 'delete', 'uploadcode', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'index', 'ajax_fetch', 'onbeforelistdata', 'add', 'onbeforesavedata', 'edit', 'delete', 'export_fetch', 'onbeforeexportdata', 'index', 'add', 'onbeforesavedata', 'edit', 'export_fetch', 'index', 'ajax_fetch', 'view', 'add', 'edit', 'delete', 'onbeforesavedata', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata', 'getallrolesforproject', 'getusersforproject', 'validationdefault', 'index', 'onsavesuccess', 'onbeforesavedata', 'changepassword', 'forgotpassword', 'setpassword', 'view', 'ajax_fetch', 'add', 'getprojects', 'getquestion', 'userprofile', 'edit', 'delete', 'login', 'logout', 'onbeforelistdata', 'export_fetch', 'onbeforeexportdata','ajaxGetOneToManyOptions','wizard');
        //Apply this new approach so that we no need to add all new actions of system in allow.
        $this->Auth->allow();

        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'dashboard', 'action' => 'index');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->autoRedirect = false;

        //set logo and it's link from settings.
        $settingsData = $this->General->_getSettings();
        $site_logo = $settingsData['Settings']['logo_img'];
        $logo_link = $settingsData['Settings']['logo_link'];
        $site_title = $settingsData['Settings']['site_title'];
        $this->set(compact('site_logo','logo_link','site_title'));
        Configure::write("site_title", $site_title);
    }

    /**
     * Before render callback.
     * Before Rendering any Action set Global variables
     *
     * @return void
     */
    public function beforeRender() {
        parent::beforeRender();
        $loggedUser = $this->General->_getLoggedUserInfo();
        $this->set('activeUser', $loggedUser);
        $title_for_layout = $this->title_for_layout;
        if (empty($title_for_layout)) {
            $title_for_layout = $this->title_for_layout = $this->name;
        }
        $title_for_action = $this->General->getTitleForAction($this->action);
        if (empty($title_for_action)) {
            $title_for_action = $title_for_layout . ' ' . $action;
        }
        $this->set('title_for_layout', __($title_for_layout));
        $this->set('title_for_action', __($title_for_action));

        /**
         * Set API Exception responce and other before render it
         * @category API
         */
        if ($this->request->is('json')) {
            if ($this->name == 'CakeError') {
                $this->response->type('json');
                $json = json_encode(array(
                    'success' => false,
                    'status' => $this->viewVars['error']->getCode(),
                    'message' => $this->viewVars['error']->getMessage(),
                ));
                $this->response->body($json);
                $this->response->send();
                exit;
            }
        }
    }

    /**
     * Returns Model to Control Data
     *
     * @see AppModel
     * @return AppModel
     */
    public function getModel() {
        return null;
    }

    /**
     * Default system messages
     * @return array
     */
    public function getDefaults() {
        return array(
            'messages' => array(
                'recordNotFound' => 'Record not Found',
                'saveSucces' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Record saved Successfully
                                </div>',
                'passwordSuccess' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                     Please Re-Login with Updated Password
                                </div>',
                'saveError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Record could not be saved. Please, try again.
                                </div>',
                'passwordError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                   Information entered are incorrect
                                </div>',
                'failSupervisor' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Please assign Valid Supervisor
                                </div>',
                'forgotsuccess' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    A email has been sent with reset password link on your registered email id.
                                </div>',
                'resetsuccess' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Your password has been reset. Please login with new password.
                                </div>',
                'deleteSuccess' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Record deleted successfully.
                                </div>',
                'deleteError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    <strong>Failure!</strong>
                                    Record not deleted
                                </div>',
                'attributeCheckError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Please select attribute to manage attribute values.
                                </div>',
                'customError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    </div>',
                'readSuccess' => '<div class="alert alert-success fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    Record mark as read successfull.
                                </div>',
                'readError' => '<div class="alert alert-danger fade in" style="margin-top:18px;">
                                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                    <strong>Failure!</strong>
                                    Record not mark as read
                                </div>',
            ),
            'redirectToController' => $this->request['controller'],
            'redirectToAction' => 'index',
            'redirectToUrl' => null,
        );
    }

}
