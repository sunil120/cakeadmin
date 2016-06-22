<?php
App::uses('AppController', 'Controller');
/**
 * CountryMaster Controller
 *
 * @property CountryMaster $CountryMaster
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CountryMastersController extends AppController {

    /**
     * Components
     * @var array
     */
    public $helpers = array('Breadcrumb');
    public $components = array('Paginator', 'Flash', 'Session');
    public $uses = array('CountryMaster','CountryMaster','RoleMaster');

    public function getModel() {
        return $this->CountryMaster;
    }
    
    /**
     * index method
     * @return void
     */
    public function index() {
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Country',
                'url' => array('controller' => 'country_masters', 'action' => 'index')
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
    }

    /**
     * view method
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->getModel()->exists($id)) {
            throw new NotFoundException(__('Invalid country master'));
        }
        $options = array('conditions' => array('CountryMaster.' . $this->getModel()->primaryKey => $id));
        $this->set('countryMaster', $this->getModel()->find('first', $options));
    }

    /**
     * add method
     * @return void
     */
    public function add() {
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Country',
                'url' => array('controller' => 'country_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Add',
                'url' => array('controller' => 'country_masters', 'action' => 'add')
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
        
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
        $this->set('Action', 'Add');
    }

    /**
     * edit method
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Country',
                'url' => array('controller' => 'country_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Edit',
                'url' => array('controller' => 'country_masters', 'action' => 'edit', $id)
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
        
        if (!$this->getModel()->exists($id)) {
            throw new NotFoundException(__('Invalid country'));
        }
        if ($this->request->is(array('post', 'put'))) {
           $data =  $this->request->data;
           $this->Crud->edit($id);
        } else {
            $options = array('conditions' => array('CountryMaster.' . $this->getModel()->primaryKey => $id));
            $this->request->data = $this->getModel()->find('first', $options);
        }
    }

    /**
    * delete method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function delete($id = null) {
        $config = $this->getDefaults();
        if (!isset($this->request->data['bulk_process_ids']) && $id <= 0) {
            throw new MethodNotAllowedException();
        }
        if(isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);

        if ($this->getModel()->delete($id,true)) {
            $this->Session->setFlash(__($config['messages']['deleteSuccess']), null, 'success');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__($config['messages']['deleteError']), null, 'error');
            return $this->redirect(array('action' => 'index'));
        }
    }
    
    /**
    * ajax fetch method
    * @throws NotFoundException
    * @return json array
    */
    public function ajax_fetch($isexport = false) {
        $conditions = array();
        $conditions['fields'] = array('CountryMaster.id', 'CountryMaster.country_name as CountryName');

        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            // for Global AJAX search filter
            if(isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )){
                $where_conditions['CountryMaster.country_name LIKE'] = "%".$data['searchTerm']."%";
                $conditions['conditions'] = array("OR"=>$where_conditions);
            }
        }

        $countryMasters = $this->Crud->listdata(array('find_configs' => $conditions), $isexport);
        $this->autoRender = false;
        echo $countryMasters;
        exit();
    }

    /**
     * export data method
     * @throws NotFoundException
     * @return json array
     */
    public function export_fetch() {
         $this->Paginator->settings['fields'] = array(
            'CountryMaster.country_name as Country'
        );
        $this->ajax_fetch(true);
    }

    public function onBeforeExportData($data, $model) {
        $configAvoidFields = array('id');
        $exportdata = $this->Crud->getExportData($data, $configAvoidFields, $model->name);
        return $exportdata;
    }
}
