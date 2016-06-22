<?php

App::uses('AppController', 'Controller');

/**
 * ProjectTasks Controller
 *
 * @property ProjectTask $ProjectTask
 * @property PaginatorComponent $Paginator
 */
class SettingsController extends AppController {

    public $helpers = array('Breadcrumb');
    public $uses = array('Pages','Settings');

    /**
     * index method
     *
     * @return void
     */
    public function index() {        
        $settingsData = $this->getModel()->find('first');
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['Settings']['email_setting'] == "1") {
                $this->Settings->validator()->remove('smtp_host');
                $this->Settings->validator()->remove('smtp_port');
                $this->Settings->validator()->remove('smtp_username');
                $this->Settings->validator()->remove('smtp_password');
            }

            if (empty($this->data['Settings']['logo_img']['name'])) {
                $this->request->data['Settings']['logo_img'] = $settingsData['Settings']['logo_img'];
            } else {
                $file = $this->request->data['Settings']['logo_img'];
                $file_n = $this->data['Settings']['logo_img']['name'];/*name of file*/
                $file_n = str_replace(' ','_',$file_n);
                
                move_uploaded_file($this->data['Settings']['logo_img']['tmp_name'],IMAGES. 'logo/' . $file_n); 
                $this->request->data['Settings']['logo_img'] = $file_n;
            }
            
            $this->Crud->add();
        } 
        
        $this->set('settingsData', $settingsData);
        
    }


    /**
     * getModel method
     *
     * @return array
     */
    public function getModel() {
        return $this->Settings;
    }

}