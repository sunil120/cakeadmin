<?php

App::uses('AppModel', 'Model');

/**
 * Template Model
 *
 */
class Template extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'template';
    public $virtualFields = array(
        'status_text' => "IF(Template.status = 1, 'active', 'Inactive')"
    );
    

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'title';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'title' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please fill title'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', '100'),
                'message' => 'This title exceeds allowed characters limit. Please select short title.'
            ),
        ),
        'subject' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please fill subject'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', '255'),
                'message' => 'This title exceeds allowed characters limit. Please select short subject.'
            ),
        ),
        'type' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please select type'
            ),
        ),
        'content' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please endter template content.'
            ),
        ),

    );


    /**
     * set template as default
     *
     * @param int $id
     */
    public function setDefault($id)
    {
        $result = $this->find('first', array(
            'conditions' => array('id' => $id),
            'recursive' => 0, //int
            'fields' => array('type'),
        ));

        if ($this->updateAll(array('status' => 0), array('type' => $result['Template']['type']))){
            if ($this->updateAll(array('status' => 1), array('id' => $id))){
                return true;
            }
        }
        return false;
    }

    /**
     * check if template is deletable or not
     *
     * @param int $ids
     * @return array
     */
    public function isDeletable($ids)
    {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $validids = $ids;
        $returnarray['valid'] = array();
        $returnarray['invalid'] = array();

        #only templates which are not set to default(status =0) can be deleted
        $result = $this->find('all', array(
            'conditions' => array('id' => $ids, 'status' => 0),
            'recursive' => -1,
            'fields' => array('id'),
        ));

        if (!empty($result)) {
            $fetchids = Set::classicExtract($result, '{n}.Template.id');
            $validids = array_values(array_diff($validids, $fetchids));

            $returnarray['invalid'] = $validids;
            $returnarray['valid'] = array_values(array_diff($ids, $validids));
        } else {
            $returnarray['invalid'] = $ids;
        }

        return $returnarray;
    }
}
