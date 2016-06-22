<?php

App::uses('AppModel', 'Model');

class ModulePermission extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'module_permission';
    public $belongsTo = array(
        'Module' => array(
            'className' => 'Module',
            'foreignKey' => 'module_id'
        )
    );

    public function getModulesWithPermissions($roleId = NULL, $userId = NULL) {
        $this->Module           = ClassRegistry::init('Module');
        $this->ModulePermission = ClassRegistry::init('ModulePermission');
        
        $conditions = array('Module.id = ModulePermission.module_id');
        if(!empty($roleId) && empty($userId)) {
            $conditions[] = 'ModulePermission.role_id = '.$roleId;
        } else if(!empty($userId)) {
            $conditions[] = 'ModulePermission.user_id = '.$userId;
        }
        
        $modules = $this->Module->find('all', array(
                        'conditions' => array('Module.is_deleted' => 0),
                        'joins' => array(
                            array(
                                'table' => 'mac_module_permission',
                                'alias' => 'ModulePermission',
                                'type' => 'LEFT',
                                'conditions' => $conditions
                            )
                        ),
                        'group' => array('Module.id'),
                        'fields' => array('Module.id', 'Module.name', 'Module.alias', 'Module.icon', 'Module.parent_id', 'Module.href', 'Module.target','Module.controller','Module.action','Module.actions','ModulePermission.permissions')
            ));
        
        if(!empty($modules)) {
            foreach ($modules as $module) {
                $href		= $module['Module']['href'];
                if(strpos($module['Module']['href'], 'http') !== true) {
                    $href = Router::url('/', true).$href;
                }
                $moduleArray[] = array(
                    'id' 	=> $module['Module']['id'],
                    'module_name' 	=> $module['Module']['name'],
                    'module_alias' 	=> $module['Module']['alias'],
                    'parent_id'  	=> $module['Module']['parent_id'],
                    'actions'           => $module['Module']['actions'],
                    'permissions' => $module['ModulePermission']['permissions'],
                    'role_id'           => $roleId,
                    'user_id'           => $userId,
                    'anchor_href' 	=> $href,
                    'module_icon' 	=> $module['Module']['icon'],
                );
            }
        }
        
        $navigationMenus = $this->getNestedMenus($moduleArray);
        return $navigationMenus;
    }
    
    private function getNestedMenus($arr, $parent = 0) {
        $navigation = Array();
        foreach($arr as $menu) {
            if($menu['parent_id'] == $parent) {
                $menu['sub'] = isset($menu['sub']) ? $menu['sub'] : $this->getNestedMenus($arr, $menu['id']);
                $navigation[] = $menu;
            }
        }
        return $navigation;
    }

}
