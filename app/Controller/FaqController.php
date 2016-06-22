<?php

App::uses('AppController', 'Controller');

/**
 * ProjectTasks Controller
 *
 * @property ProjectTask $ProjectTask
 * @property PaginatorComponent $Paginator
 */
class FaqController extends AppController {

    public $helpers = array('Breadcrumb');
    public $uses = array('Faq');
    
    /**
     * getModel method
     *
     * @return array
     */
    public function getModel() {
        return $this->Faq;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {        
        
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {         
        if (!$this->getModel()->exists($id)) {
            throw new NotFoundException(__('Invalid Faq'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Crud->edit($id);
        } else {
            $this->request->data = $this->getModel()->read(null, $id);
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
        if (isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);

        if ($this->getModel()->delete($id, true)) {
            $this->Session->setFlash(__($config['messages']['deleteSuccess']), null, 'success');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__($config['messages']['deleteError']), null, 'error');
            return $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * ajax_fetch method
     * ajax call
     *
     * @return json
     */
    public function ajax_fetch() {

        $conditions = array();
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            //for Global AJAX search filter
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $where_conditions['Faq.question LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['Faq.answer LIKE'] = "%" . $data['searchTerm'] . "%";
                if (preg_match('/active/i',$data['searchTerm'])) {
                    $where_conditions['Faq.status'] = 1;
                }
                if (preg_match('/inactive/i',$data['searchTerm'])) {
                    $where_conditions['Faq.status'] = 0;
                }
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
        }
        $config = array('find_configs' => $conditions);
        $faqMasters = $this->Crud->listdata($config, false);
        $this->autoRender = false;

        echo $faqMasters;
        exit();
    }

}
