<?php

App::uses('AppController', 'Controller');

/**
 * ProjectTasks Controller
 *
 * @property ProjectTask $ProjectTask
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController
{
    public $helpers = array('Breadcrumb');
    public $uses = array('Post', 'TagMaster', 'PostTag');
    
    /**
     * getModel method
     *
     * @return array
     */
    public function getModel()
    {
        return $this->Post;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index(){   }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        $userId = $this->Auth->user();
        if ($this->request->is('post')) {
            $this->request->data['Post']['created_by'] = $userId['id'];
            if ($this->request->data['TagMaster']['tag_name']) {
                $this->request->data['TagMaster']['tag_name'] = json_decode($this->request->data['TagMaster']['tag_name']);
                $tempTagText = array();
                if (!empty($this->request->data['TagMaster']['tag_name'])) {
                    foreach ($this->request->data['TagMaster']['tag_name'] as $tagText) {
                        $tempTagText[] = $tagText->text;
                    }
                }
                $this->request->data['TagMaster']['tag_name'] = implode(',', $tempTagText);
            }
            $this->Crud->add();
        }
    }

    /**
     * perfomed some opration on inserted success
     *
     * @return void
     */
    public function onSaveSuccess()
    {
        $postId = $this->getModel()->id;
        if ($this->request->data['TagMaster']['tag_name']) {
            // Add Tags into Tags table
            $keywords = explode(',', $this->request->data['TagMaster']['tag_name']);            
            $this->PostTag->deleteAll(array('PostTag.post_id' => $postId), false);
            foreach ($keywords as $value) {
                $value = substr(ucwords(trim($value)), 0, 100);
                $tagId = $this->TagMaster->findByTagName($value, 'id');
                if (!empty($tagId)) {
                    $tagId = $tagId['TagMaster']['id'];
                    $this->PostTag->set('post_id', $postId);
                    $this->PostTag->set('tag_id', $tagId);

                    if (!$this->PostTag->save()) {
                        throw new Exception('Error to save post tag slave', 404);
                    }
                } else {
                    $this->TagMaster->set('tag_name', $value);
                    $this->TagMaster->set('created_by', $this->request->data['Post']['created_by']);
                    if ($this->TagMaster->save()) {
                        $tagId = $this->TagMaster->id;
                        $this->PostTag->set('post_id', $postId);
                        $this->PostTag->set('tag_id', $tagId);
                        //$this->PostTag->deleteAll(array('PostTag.post_id' => $postId), false);
                        if (!$this->PostTag->save()) {
                            throw new Exception('Error to save post tag slave', 404);
                        }
                    }
                    $this->TagMaster->clear();
                }
            }
        } else {
            $postTagCount = $this->PostTag->find('count', array(
                'fields' => array('PostTag.post_id', 'PostTag.tag_id'),
                'conditions' => array(
                    'PostTag.post_id' => $postId,
                ),
            ));
            if ($postTagCount) {
                $this->PostTag->deleteAll(array('PostTag.post_id' => $postId), false);
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->getModel()->exists($id)) {
            throw new NotFoundException(__('Invalid Post'));
        }
        $userId = $this->Auth->user();
        $postTagData = $this->PostTag->find('all', array(
            'fields' => array('PostTag.post_id', 'PostTag.tag_id'),
            'conditions' => array(
                'PostTag.post_id' => $id,
            ),
        ));
        $tagArray = array();
        foreach ($postTagData as $postTag) {
            $tempArray = $this->TagMaster->find('all', array(
                'fields' => array('TagMaster.tag_name'),
                'conditions' => array(
                    'TagMaster.id' => $postTag['PostTag']['tag_id'],
                ),
            ));
            $tagArray[]['text'] = $tempArray[0]['TagMaster']['tag_name'];
        }
        $postTagData = json_encode($tagArray);
        $this->set('postTagData', $postTagData);
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Post']['modified_by'] = $userId['id'];
            if ($this->request->data['TagMaster']['tag_name']) {
                $this->request->data['TagMaster']['tag_name'] = json_decode($this->request->data['TagMaster']['tag_name']);
                $tempTagText = array();

                if (!empty($this->request->data['TagMaster']['tag_name'])) {
                    foreach ($this->request->data['TagMaster']['tag_name'] as $tagText) {
                        $tempTagText[] = $tagText->text;
                    }
                }

                $this->request->data['TagMaster']['tag_name'] = implode(',', $tempTagText);
            }
            $data = $this->request->data;
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
     * ajax_fetch method
     * ajax call
     *
     * @return json
     */
    public function ajax_fetch()
    {
        $conditions = array();

        if ($this->request->isget() && $this->request->query != null) {
            $data = $this->request->query;

            //Working for Global AJAX search featire
            if (isset($data['searchTerm']) && ($data['searchTerm'] != '' && $data['searchTerm'] != null )) {
                $search_term = true;
                $where_conditions['Post.subject LIKE'] = "%" . $data['searchTerm'] . "%";
                if (preg_match('/active/i',$data['searchTerm'])) {
                    $where_conditions['Post.status'] = 1;
                }
                if (preg_match('/inactive/i',$data['searchTerm'])) {
                    $where_conditions['Post.status'] = 0;
                }
                $conditions['conditions'] = array("OR" => $where_conditions);
            }
        }

        $faqMasters = $this->Crud->listdata(array('find_configs' => $conditions));
        $this->autoRender = false;
        echo $faqMasters;
        exit();
    }

    /**
     * change status to active or inactive
     */
    public function changestatus($id, $status)
    {
        $this->autoRender = FALSE;
        if ($status == 'false') {
            $new_status = 1;
            $msg = "Post status has been changed to active";
        } else {
            $new_status = 0;
            $msg = "Post status has been changed to inactive";
        }

        if ($this->getModel()->updateAll(array('Post.status' => $new_status), array('Post.id' => $id))) {
            $this->Session->setFlash(__($this->General->successMsg($msg)));
        } else {
            $this->Session->setFlash(__($this->General->errorMsg()));
        }

        return $this->redirect(array('action' => 'index'));
    }

}