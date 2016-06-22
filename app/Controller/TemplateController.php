<?php

App::uses('AppController', 'Controller');

/**
 * ProjectTasks Controller
 *
 * @property ProjectTask $ProjectTask
 * @property PaginatorComponent $Paginator
 */
class TemplateController extends AppController {

    public $helpers = array('Breadcrumb');
    public $uses = array('Template');

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
            $this->request->data['Template']['type'] = strtolower(str_replace(' ', '_', $this->request->data['Template']['title']));
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
     * getModel method
     *
     * @return array
     */
    public function getModel() {
        return $this->Template;
    }

    /**
     * ajax_fetch method
     * ajax call
     * @return json
     */
    public function ajax_fetch() {
        $conditions = array();
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            //Working for Global AJAX search featire
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $search_term = true;
                $where_conditions['Template.title LIKE'] = "%" . $data['searchTerm'] . "%";
                $where_conditions['Template.subject LIKE'] = "%" . $data['searchTerm'] . "%";
                $conditions['conditions'] = array("OR" => $where_conditions);
                $conditions['fields'] = array("OR" => $where_conditions);
            }
        }
        $templateMasters = $this->Crud->listdata(array('find_configs' => $conditions));
        $this->autoRender = false;
        echo $templateMasters;
        exit();
    }

   
}