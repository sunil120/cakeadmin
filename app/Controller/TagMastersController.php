<?php

App::uses('AppController', 'Controller');

/**
 * CountryMaster Controller
 *
 * @property TagMaster $TagMaster
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class TagMastersController extends AppController
{
    /**
     * Components
     * @var array
     */
    public $helpers = array('Breadcrumb');
    public $uses = array('TagMaster');

    public function getModel()
    {
        return $this->TagMaster;
    }

    /**
     * index method
     * @return void
     */
    public function index(){
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Tag Master',
                'url' => array('controller' => 'tag_masters', 'action' => 'index')
            )
        );  
        $this->set(compact('breadcrumb'));
    }

    
    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Crud->add();
        }
        $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Tag Master',
                'url' => array('controller' => 'tag_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Add',
                'url' => array('controller' => 'tag_masters', 'action' => 'add')
            )
        );  
        $this->set(compact('breadcrumb'));
    }
    
    /**
     * edit method
     * @return void
     */
    public function edit($id = '')
    {
        if (!$this->getModel()->exists($id)) {
            throw new NotFoundException(__('Invalid Post'));
        }
        if ($this->request->is(array('post','put'))) {
            $this->Crud->edit($id);
        } else {
            $this->request->data = $this->getModel()->read(null, $id);
        }
        
         $breadcrumb = array(
            array(
                'title' => 'Home',
                'url' => array('controller' => 'dashboard', 'action' => 'index')
            ),
            array(
                'title' => 'Tag Master',
                'url' => array('controller' => 'tag_masters', 'action' => 'index')
            ),
            array(
                'title' => 'Edit',
                'url' => array('controller' => 'tag_masters', 'action' => 'edit',$id)
            )
        );  
        $this->set(compact('breadcrumb'));
    }
   
    /**
     * delete method
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $config = $this->getDefaults();
        if (!isset($this->request->data['bulk_process_ids']) && $id <= 0) {
            throw new MethodNotAllowedException();
        }
        if(isset($this->request->data['bulk_process_ids']) && $id == null)
            $id = explode(', ', $this->request->data['bulk_process_ids']);

        if ($this->getModel()->delete($id,true)) {
            $this->Session->setFlash(__($config['messages']['deleteSuccess']), null, 'success');
        } else {
            $this->Session->setFlash(__($config['messages']['deleteError']), null, 'error');
        }
        return $this->redirect(array('action' => 'index'));
    }


    /**
     * ajax fetch method
     * @throws NotFoundException
     * @return json array
     */
    public function ajax_fetch($isexport = false)
    {
        $conditions = array();
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            //Working for Global AJAX search featire
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $where_conditions['TagMaster.tag_name LIKE'] = "%" . $data['searchTerm'] . "%";
                if (preg_match('/active/i',$data['searchTerm'])) {
                    $where_conditions['TagMaster.status'] = 1;
                }
                if (preg_match('/inactive/i',$data['searchTerm'])) {
                    $where_conditions['TagMaster.status'] = 0;
                }
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
        }

        $tagMasters = $this->Crud->listdata(array('find_configs' => $conditions), $isexport);
        $this->autoRender = false;
        echo $tagMasters;
        exit();
    }

    /**
     * AutoComplete Tag
     * @return json array
     */
    public function autocompleteTag()
    {
        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;
            if (isset($data['tag']) && ($data['tag'] != '' && $data['tag'] != null )) {
                $conditions['onAjax'] = true;
                $records = $this->getModel()->find('all', array(
                    'fields' => array('TagMaster.tag_name'),
                    'conditions' => array(
                        'TagMaster.tag_name LIKE' => "%" . $data['tag'] . "%",
                        'TagMaster.status' => 1,
                    )
                ));
                $data = array();
                if ($records) {
                    foreach ($records as $record) {
                        $data[]['text'] = $record['TagMaster']['tag_name'];
                    }
                } else {
                }
            }

            $this->autoRender = false;
            $tagMasters = json_encode($data);
            echo $tagMasters;
            exit();
        }
    }

}