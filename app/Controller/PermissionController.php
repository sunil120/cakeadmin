<?php

/**
 * Permission Controller
 * 
 * Permission module related actions.
 * 
 * @package     App.Controller
 * @subpackage  PermissionController
 */
App::uses('AppController', 'Controller');

/**
 * Permission Controller
 * 
 * Permission module related actions.
 * 
 * @package     App.Controller
 * @subpackage  PermissionController
 */
class PermissionController extends AppController
{
    //Load needful models
    public $uses = array('UserMaster', 'Module', 'ModulePermission');
    //Load needful components
    public $components = array('Module');

    /**
     * Permission manage by user.
     * @param int $userId      
     */
    public function user($userId)
    {
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'User',
                'url' => array('controller' => 'users', 'action' => 'edit', $userId)
            ),
            array(
                'title' => 'Permission',
                'url' => array('controller' => 'permission', 'action' => 'user', $userId)
            )
        );
        @$this->set('breadcrumb', $breadcrumb);

        //If this is superuser, permissions cannot be udpated
        $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
        if ($userId == 1) {
            $this->Session->setFlash(__('<div class="alert alert-danger fade in" style="margin-top:18px;">
                    <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                    Permissions for Super Admin cannot be changed.
                </div>'), null, $params);
            return $this->redirect(array('controller' => 'users'));
        }
        $modules = $this->ModulePermission->getModulesWithPermissions(NULL, $userId);

        $givenPermissions = array_filter(Set::classicExtract($modules, '{n}.permissions'));
        if(empty($givenPermissions)) {
            $userRole = $this->UserModel->find('first', array('conditions' => array('UserModel.id' => $userId), 'fields' => array('role_id')));
            $roleId = $userRole['UserModel']['role_id'];
            $modules = $this->ModulePermission->getModulesWithPermissions($roleId);
        }

        $this->set('modules', $modules);
        $this->set('userId', $userId);
        $this->render('index');
        //Set breadcrumbs
        $breadcrumb[] = array('lable' => 'Permissions');
        $this->set(array('breadcrumb' => $breadcrumb));
    }

    /**
     * Permission manage by role.
     * @param int $roleId      
     */
    public function role($roleId)
    {
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Role',
                'url' => array('controller' => 'roleMasters', 'action' => 'edit', $roleId)
            ),
            array(
                'title' => 'Permission',
                'url' => array('controller' => 'permission', 'action' => 'role', $roleId)
            )
        );
        @$this->set('breadcrumb', $breadcrumb);
        $modules = $this->ModulePermission->getModulesWithPermissions($roleId);
        $this->set('modules', $modules);
        $this->set('roleId', $roleId);
        $this->render('index');
        //Set breadcrumbs
        $breadcrumb[] = array('lable' => 'Permissions');
        $this->set(array('breadcrumb' => $breadcrumb));
    }

    /**
     * Save Permission.
     * @return string | json
     */
    public function save()
    {

        $this->autoRender = false;
        $this->layout = false;
        $this->response->type('json');

        //Check if request is post
        if ($this->request->ispost()) {
            $postData = ($this->request->data != null) ? $this->request->data : array();

            if (!isset($postData['menu'])) {
                $response = array(
                    'notification' => array(
                        array(
                            'status' => 'error',
                            'title' => 'Error!',
                            'message' => 'Please select at-least one permission',
                            'type' => 'alert',
                        ),
                    ),
                );
                echo json_encode($response);
                exit;
            }

            $menu = $postData['menu'];
            $roleId = (!empty($postData['roleId'])) ? $postData['roleId'] : NULL;
            $userId = (!empty($postData['userId'])) ? $postData['userId'] : NULL;

            $params = array('messageType' => AppConstants::MESSAGE_TYPE_ERROR);
            //If this is superuser, permissions cannot be udpated
            if ($userId == 1) {
                $this->Session->setFlash(__('<div class="alert alert-danger fade in" style="margin-top:18px;">
                        <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                        Permissions for Super Admin cannot be changed.
                    </div>'), null, $params);
                return $this->redirect(array('controller' => 'users'));
            }


            //Get existing permissions and remove them
            $conditions = array();
            if (!empty($roleId)) {
                $this->ModulePermission->deleteAll(array('ModulePermission.role_id' => $roleId), false);
            } else if (!empty($userId)) {
                $this->ModulePermission->deleteAll(array('ModulePermission.user_id' => $userId), false);
            }
            //Now again re-assign the permissions
            foreach ($menu as $moduleId => $module) {
                $data = array();
                if (!empty($roleId)) {
                    $data['role_id'] = $roleId;
                } else if (!empty($userId)) {
                    $data['user_id'] = $userId;
                }
                if (isset($module['permissions'][0]) && !empty($module['permissions'][0])) {
                    $data['permissions'] = json_encode($module['permissions']);
                    $data['module_id'] = $moduleId;
                    $this->ModulePermission->create();
                    $this->ModulePermission->save($data);
                }
            }

            //Update sessionMenuPermissions variable
            $this->Module->setSessionMenuPermissions();

            //If permissions updated successfully, then show a success message
            $response = array(               
                'redirect' => array(
                    'current' => array(
                        'url' => $this->Session->read('http_referer'),
                        'after' => 100
                    )
                )
            );
            $this->Session->setFlash(__($this->General->successMsg('Permissions updated successfully.')));
            echo json_encode($response);
            exit;
        }
    }

    /**
     * clear user specific permissions
     * 
     * @param int $userId
     */
    public function clearpermissions($userId)
    {
        $this->autoRender = false;
        $this->layout = false;
        $this->response->type('json');
        try {
            $this->ModulePermission->deleteAll(array('ModulePermission.user_id' => $userId), false);
            $response = array(                
                'redirect' => array(
                    'current' => array(
                        'url' => $this->Session->read('http_referer'),
                        'after' => 100
                    )
                )
            );
            $this->Session->setFlash(__($this->General->successMsg('Permissions have been reset to role.')));
        } catch (Exception $exc) {
            $response = array(
                'notification' => array(
                    array(
                        'status' => 'error',
                        'title' => '',
                        'message' => Configure::read('errorMsg'),
                        'type' => 'alert',
                    ),
                ),
            );
        }
        echo json_encode($response);
    }

}