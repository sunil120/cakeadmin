<?php
App::uses('AppController', 'Controller');
/**
 * CityMasters Controller
 *
 * @property CityMaster $CityMaster
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CityMastersController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $helpers = array('Breadcrumb');
    public $components = array('Paginator', 'Flash', 'Session');
    public $uses = array('CountryMaster','StateMaster','RoleMaster','CityMaster');

    public function getModel() {
        return $this->CityMaster;
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
                'title' => 'City',
                'url' => array('controller' => 'CityMasters', 'action' => 'index')
            )
        );        
        $this->set('breadcrumb', $breadcrumb);
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
        $options = array('conditions' => array('CityMaster.' . $this->getModel()->primaryKey => $id));
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
                'title' => 'City',
                'url' => array('controller' => 'CityMasters', 'action' => 'index')
            ),
            array(
                'title' => 'Add',
                'url' => array('controller' => 'CityMasters', 'action' => 'add')
            )
        );        
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
        $countries = $this->CountryMaster->find('list',array('fields'=>array('id','country_name')));
        $this->set(array('countries'=>$countries,'breadcrumb'=>$breadcrumb));
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
                'title' => 'City',
                'url' => array('controller' => 'CityMasters', 'action' => 'index')
            ),
            array(
                'title' => 'Edit',
                'url' => array('controller' => 'CityMasters', 'action' => 'edit', $id)
            )
        );        
        @$this->set('breadcrumb', $breadcrumb);
        
        if (!$this->getModel()->exists($id)) {
                throw new NotFoundException(__('Invalid state master'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data =  $this->request->data;
            $this->Crud->edit($id);
        } 
        $options = array('conditions' => array('CityMaster.' . $this->getModel()->primaryKey => $id));
        $this->request->data = $this->getModel()->find('first', $options);
        $conditions = array();
        if(isset($this->request->data['CityMaster']['country_id']) && $this->request->data['CityMaster']['country_id']!='') {
            $conditions = array('country_id'=>$this->request->data['CityMaster']['country_id']);
        }

        $countries = $this->CountryMaster->find('list',array('fields'=>array('id','country_name')));
        $states = $this->StateMaster->find('list', array('fields'=>array('id','state_name'),'conditions'=>$conditions));
        $this->set(array('countries'=>$countries,'states'=>$states,'breadcrumb'=>$breadcrumb));
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
        $conditions['fields'] = array('CountryMaster.country_name','StateMaster.state_name','CityMaster.city_name', 'CityMaster.id');
      
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            //Working for Global AJAX search featire
            if(isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )){
                $search_term = true;
                $where_conditions['CityMaster.city_name LIKE'] = "%".$data['searchTerm']."%";
                $where_conditions['CountryMaster.country_name LIKE'] = "%".$data['searchTerm']."%";
                $where_conditions['StateMaster.state_name LIKE'] = "%".$data['searchTerm']."%";
                $conditions['conditions'] = array("OR"=>$where_conditions);
            }
        }
                
        $stateMasters = $this->Crud->listdata(array('find_configs' => $conditions, 'recursive' => 1), $isexport);
        $this->autoRender = false;
        echo $stateMasters;
        exit();
    }
    
    /**
     * fetch state by country id
     * @throws NotFoundException
     * @return json array
     */
    public function getStateByCountry() {
        $this->autoRender = false;
        if ($this->request->ispost() && !empty($this->request->data)) {
            $coutnry_id = $this->request->data['country'];
            return json_encode($this->StateMaster->find('list',array('conditions'=>array('country_id'=>$coutnry_id),'fields'=>array('id','state_name'))));
        }
        return false;
    }
    
    /**
     * export data method
     * @throws NotFoundException
     * @return json array
     */
    public function export_fetch() {
         $this->Paginator->settings['fields'] = array(
            'CountryMaster.country_name as Country',
            'StateMaster.state_name as State',
            'CityMaster.city_name as City'
        );
        $this->ajax_fetch(true);
    }
    
    /**
     * export data on before filter method
     * @throws NotFoundException
     * @return json array
     */
    public function onBeforeExportData($data, $model) {
        $configAvoidFields = array('id');
        $exportdata = $this->Crud->getExportData($data, $configAvoidFields, $model->name);
        return $exportdata;
    }
    
    /**
     * ajax fetch on before filter method
     * @throws NotFoundException
     * @return json array
     */
    public function onBeforeListData($data, $model) {
                          
        foreach($data as $key=>$val) {                       
            $data[$key]['CityMaster']['country_name'] = $val['CountryMaster']['country_name'];
            $data[$key]['CityMaster']['state_name'] = $val['StateMaster']['state_name'];
            unset($data[$key]['CountryMaster']);
            unset($data[$key]['StateMaster']);
            unset($data[$key]['CityMaster']['country_id']);
            unset($data[$key]['CityMaster']['state_id']);
        } 
        return $data;
    }    
}
