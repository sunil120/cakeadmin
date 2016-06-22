<?php

App::uses('AuthComponent', 'Controller/Component');


class ModuleComponent extends Component {
    
    public $components = array('Session');
    public $request;
    public $controller;
    
    public function initialize(Controller $controller, $settings = array()) {
        $this->controller = $controller;
    }

    public function startup(Controller $controller) {
        //$this->controller = $controller;
    }
    private function getNestedMenus($arr, $parent = 0) {
        $navigation = Array();
        foreach($arr as $menu) {
            if($menu['parent_id'] == $parent) {
                $menu['sub'] = isset($menu['sub']) ? $menu['sub'] : $this->getNestedMenus($arr, $menu['module_id']);
                $navigation[] = $menu;
            }
        }
        return $navigation;
    }
    
    public function getPermittedModules($parentAlias = NULL) {
        $this->ModulePermission     = ClassRegistry::init('ModulePermission');
        $this->Module               = ClassRegistry::init('Module');
        $roleId                     = AuthComponent::user('role_id');
        $userId                     = AuthComponent::user('id');
        $parentId                   = 0;
        
        $conditions = array();
        if(!empty($parentAlias)) {
            $moduleByAlias      = $this->Module->find('first',array('conditions' => array('Module.alias' => $parentAlias)));
            $parentId           = $moduleByAlias['Module']['id'];
            $conditions['Module.parent_id'] = $parentId;
        }
        $conditions['ModulePermission.user_id'] = $userId;
        
        $modules        = $this->ModulePermission->find('all',array('conditions' => $conditions,'order' => array('Module.link_order ASC')));
        if(empty($modules)) {
            $conditions['ModulePermission.role_id'] = $roleId;
            $modules        = $this->ModulePermission->find('all',array('conditions' => $conditions,'order' => array('Module.link_order ASC')));
        }
        
        $moduleArray = array();
        
        if(!empty($modules)) {
            foreach ($modules as $module) {
                $href		= $module['Module']['href'];
                if(strpos($module['Module']['href'], 'http') !== true) {
                    $href = Router::url('/', true).$href;
                }
                $moduleArray[] = array(
                    'module_id' 	=> $module['Module']['id'],
                    'module_name' 	=> $module['Module']['name'],
                    'module_alias' 	=> $module['Module']['alias'],
                    'display_submodules'=> $module['Module']['display_submodules'],
                    'parent_id'  	=> $module['Module']['parent_id'],
                    'permissions' 	=> $module['ModulePermission']['permissions'],
                    'role_id'           => $roleId,
                    'user_id'           => $userId,
                    'anchor_href' 	=> $href,
                    'module_icon' 	=> $module['Module']['icon'],
                );
            }
        }
        $navigationMenus = $this->getNestedMenus($moduleArray, $parentId);
        return $navigationMenus;
    }
    
    /**
     * When the permissions of modules will updated, sessionMenuPermissions session variable will also get updated
     */
    public function setSessionMenuPermissions() {
        $this->ModulePermission  = ClassRegistry::init('ModulePermission');
        $modulePermissions      = array();
        $controllerPermissions  = array();
        
        $roleId                 = AuthComponent::user('role_id');
        $userId                 = AuthComponent::user('id');
        $modules                = $this->ModulePermission->find('all',array('conditions' => array('ModulePermission.user_id' => $userId)));
        if(empty($modules)) {
            $modules        = $this->ModulePermission->find('all',array('conditions' => array('ModulePermission.role_id' => $roleId)));
        }
        
        foreach ($modules as $module) {
            $controller             = strtolower(str_replace('_','',$module['Module']['controller']));
            $alias                  = $module['Module']['alias'];
            $availablePermssions    = json_decode($module['Module']['actions'], true);
            $permittedPermissions   = json_decode($module['ModulePermission']['permissions']);
            $permissionsBoolean     = array();
            if(!empty($availablePermssions)) {
                $availablePermssions = array_keys($availablePermssions);
                foreach($availablePermssions as $per) {
                    $qualifiedPermissionName     = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($per))));
                    $qualifiedPermissionName[0]  = strtolower($qualifiedPermissionName[0]);
                    $permissionsBoolean[$qualifiedPermissionName] = (in_array($per, $permittedPermissions)) ? true : false;
                }
            }
            $modulePermissions[$alias]          = $permittedPermissions;
            $controllerPermissions[$controller] = $permissionsBoolean;
        }
        $this->Session->write('sessionControllerPermissions', $controllerPermissions);
        $this->Session->write('sessionMenuPermissions', $modulePermissions);
    }
    
    /**
     * Get sessionMenuPermissions method
     */
    public function getSessionMenuPermissions() {
       return $this->Session->read('sessionMenuPermissions');
    }
    
    /**
     * Get sessionControllerPermissions method
     */
    public function getSessionControllerPermissions() {
       return $this->Session->read('sessionControllerPermissions');
    }
    
    /*
     * Check permissions
     */
    public function checkModulePermission($alias, $permission, $redirect = false) {
        $modulePermissions    = $this->getSessionMenuPermissions();
        //pr($modulePermissions);die;
        $permissionFlag = false;
        
        if(is_string($permission)) {
            $permissionFlag = (isset($modulePermissions[$alias]) && in_array($permission, $modulePermissions[$alias]));
        } else if(is_array($permission)) {
            if(isset($permission['AND'])) {
                $andPermissions = $permission['AND'];
                $permissionFlag = (isset($modulePermissions[$alias]) && count(array_intersect($andPermissions, $modulePermissions[$alias])) == count($modulePermissions[$alias]));
            } else if(isset($permission['OR'])) {
                $orPermissions = $permission['OR'];
                foreach ($orPermissions as $op) {
                    if(isset($modulePermissions[$alias]) && in_array($op, $modulePermissions[$alias])) {
                        $permissionFlag = true;
                        break;
                    }
                }
            }
        }
        if(empty($modulePermissions[$alias]) || !$permissionFlag) {
            if($redirect) {
               if($this->controller->request->is('ajax')) {
                    $this->controller->response->type('json');
                    echo json_encode(array(
                        'html' => '',
                        'notification' => array(
                            array(
                                'status' => 'error',
                                'title' => 'Error!',
                                'message' => 'You are not allowed to access this resource',
                                'type' => 'alert',
                            ),
                        )
                    ));
                    exit;
                } else {
                    $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
                    $this->Session->setFlash(__('<div class="alert alert-danger fade in" style="margin-top:18px;">
                                        <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                                         You are not allowed to access this resource
                                    </div>'), null, $params);
                    return $this->controller->redirect($this->webroot."/dashboard");
                } 
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
