<?php

App::uses('AppModel', 'Model');

/**
 * UserMaster Model
 *
 */
class Settings extends AppModel
{
    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'settings';
    public $validate = array(
        'admin_email' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please fill email'
            ),
            'unique' => array(
                'rule' => array('email'),
                'message' => 'Please Enter Proper Email Id'
            ),
        ),
        'site_title' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please enter Site Title'
        ),
        'logo_link' => array(
            'rule' => 'url',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'This look like a website to me'
        ),
        'logo_img' => array(
            'rule' => array('check_link'),
            'message' => 'Please Select File',
            'required' => true,
        ),
        'email_setting' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please select email setting'
        ),
        'smtp_host' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please enter SMTP host'
        ),
        'smtp_port' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please enter SMTP port'
        ),
        'smtp_username' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please enter SMTP username'
        ),
        'smtp_password' => array(
            'rule' => array('notBlank'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Please enter SMTP password'
        ),
    );

    function check_link($id = null)
    {

        $check_logo = $this->data['Settings']['logo_img'];
        if ($check_logo == "") {
            return false;
        } else {
            return true;
        }
    }

}