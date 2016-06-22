<?php

App::uses('AppHelper', 'View/Helper');

class ModuleHelper extends AppHelper
{

    private function getNestedMenus($arr, $parent = 0)
    {
        $navigation = Array();
        foreach ($arr as $menu) {
            if ($menu['parent_id'] == $parent) {
                $menu['sub'] = isset($menu['sub']) ? $menu['sub'] : $this->getNestedMenus($arr, $menu['module_id']);
                $navigation[] = $menu;
            }
        }
        return $navigation;
    }

    public function getPermittedModules()
    {
        $roleId = AuthComponent::user('role_id');
        $userId = AuthComponent::user('id');
        $this->ModulePermission = ClassRegistry::init('ModulePermission');
        $modules = $this->ModulePermission->find('all', array(
            'conditions' => array('ModulePermission.user_id' => $userId),
            'order' => array('Module.link_order ASC')
        ));
//        pr($modules); exit;
        if (empty($modules)) {
            $modules = $this->ModulePermission->find('all', array(
                'conditions' => array('ModulePermission.role_id' => $roleId),
                'order' => array('Module.link_order ASC')
            ));
        }
        $moduleArray = array();
//pr($modules); exit;
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $href = $module['Module']['href'];
                if (strpos($module['Module']['href'], 'http') !== true) {
                    $href = Router::url('/', true) . $href;
                }
                $moduleArray[] = array(
                    'module_id' => $module['Module']['id'],
                    'module_name' => $module['Module']['name'],
                    'module_alias' => $module['Module']['alias'],
                    'display_submodules' => $module['Module']['display_submodules'],
                    'parent_id' => $module['Module']['parent_id'],
                    'permissions' => $module['ModulePermission']['permissions'],
                    'role_id' => $roleId,
                    'user_id' => $userId,
                    'anchor_href' => $href,
                    'controller' => $module['Module']['controller'],
                    'action' => $module['Module']['action'],
                    'module_icon' => $module['Module']['icon'],
                );
            }
        }

        $navigationMenus = $this->getNestedMenus($moduleArray);
        return $navigationMenus;
    }

    /**
     * When the permissions of modules will updated, sessionMenuPermissions session variable will also get updated
     */
    public function setSessionMenuPermissions()
    {

        $modulePermissions = array();
        $roleId = AuthComponent::user('role_id');
        $userId = AuthComponent::user('id');
        $modules = $this->ModulePermission->find('all', array('conditions' => array('ModulePermission.user_id' => $userId)));
        if (empty($modules)) {
            $modules = $this->ModulePermission->find('all', array('conditions' => array('ModulePermission.role_id' => $roleId)));
        }

        foreach ($modules as $module) {
            $alias = $module['Module']['alias'];
            $permissions = $module['ModulePermission']['permissions'];
            $permissions = json_decode($permissions);
            $modulePermissions[$alias] = $permissions;
        }
        $this->Session->write('sessionMenuPermissions', $modulePermissions);
    }

    /**
     * Get sessionMenuPermissions method
     */
    public function getSessionMenuPermissions()
    {
        return $this->Session->read('sessionMenuPermissions');
    }

}