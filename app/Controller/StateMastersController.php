<?php
App::uses('AppController', 'Controller');
/**
 * StateMasters Controller
 *
 * @property StateMaster $StateMaster
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class StateMastersController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $helpers = array('Breadcrumb');
    public $components = array('Paginator', 'Flash', 'Session');
    public $uses = array('StateMaster','StateMaster','RoleMaster','StateMaster');

    public function getModel() {
        return $this->StateMaster;
    }

/**
 * index method
 *
 * @return void
 */
    public function index() {        
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'State',
                'url' => array('controller' => 'state_masters', 'action' => 'index')
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        if (!$this->getModel()->exists($id)) {
                throw new NotFoundException(__('Invalid state master'));
        }
        $options = array('conditions' => array('StateMaster.' . $this->getModel()->primaryKey => $id));
        $this->set('stateMaster', $this->getModel()->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {        
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'State',
                'url' => array('controller' => 'state_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Add',
                'url' => array('controller' => 'state_masters', 'action' => 'add')
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
        
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
        $countries = $this->CountryMaster->find('list');
        $this->set(compact('countries'));
    }

    /**
     * edit method
     *
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
                'title' => 'State',
                'url' => array('controller' => 'state_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Edit',
                'url' => array('controller' => 'state_masters', 'action' => 'edit', $id)
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
        
        if (!$this->getModel()->exists($id)) {
                throw new NotFoundException(__('Invalid state master'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data =  $this->request->data;
            $this->Crud->edit($id);
        } else {
            $options = array('conditions' => array('StateMaster.' . $this->getModel()->primaryKey => $id));
            $this->request->data = $this->getModel()->find('first', $options);
        }
        $countries = $this->CountryMaster->find('list');
        $this->set(compact('countries'));
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
        $conditions['fields'] = array('CountryMaster.country_name','StateMaster.state_name', 'StateMaster.id');
      
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            //Working for Global AJAX search featire
            if(isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )){
                $search_term = true;
                $where_conditions['StateMaster.state_name LIKE'] = "%".$data['searchTerm']."%";
                $where_conditions['CountryMaster.country_name LIKE'] = "%".$data['searchTerm']."%";
                $conditions['conditions'] = array("OR"=>$where_conditions);
            }
        }
                
        $stateMasters = $this->Crud->listdata(array('find_configs' => $conditions, 'recursive' => 1), $isexport);
        $this->autoRender = false;
        echo $stateMasters;
        exit();
    }
    
    /**
     * export data method
     * @throws NotFoundException
     * @return json array
     */
    public function export_fetch() {
         $this->Paginator->settings['fields'] = array(
            'CountryMaster.country_name as Country',
            'StateMaster.state_name as State'
        );
        $this->ajax_fetch(true);
    }

    public function onBeforeExportData($data, $model) {
        $configAvoidFields = array('id');
        $exportdata = $this->Crud->getExportData($data, $configAvoidFields, $model->name);
        return $exportdata;
    }
    
    public function onBeforeListData($data, $model) {
                          
        foreach($data as $key=>$val) {                       
            $data[$key]['StateMaster']['country_name'] = $val['CountryMaster']['country_name'];
            unset($data[$key]['CountryMaster']);
            unset($data[$key]['StateMaster']['country_id']);
        } 
        return $data;
    }    
}
