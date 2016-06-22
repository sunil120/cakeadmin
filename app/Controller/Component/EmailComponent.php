<?php

/**
 * Email Component
 *
 * All email sending related functions here
 *
 * @package     App.Controller.Component
 * @subpackage  Email
 */
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class EmailComponent extends Component
{
    /**
     * Set email content
     * @param string $template_type
     * @param array $config
     * @return void
     */
    public function sendEmailWithTemplate($template_type, $data = array())
    {
        $templateModel = ClassRegistry::init('Template');
        $settingsModel = ClassRegistry::init('Settings');
        
        $result = $templateModel->find('first', array(
            'conditions' => array('type' => $template_type, 'status' => 1),
            'recursive' => 0,
        ));
        $settingsData = $settingsModel->find('first', array('fields' => array(
            'admin_email', 'site_title', 'logo_img'
        )));
        $settingData = array(
            '[site_url]' => Router::url('/', true),
            '[site_title]' => $settingsData['Settings']['site_title'],
            '[site_logo]' => Router::url('/', true) . "logos/" . $settingsData['Settings']['logo_img'],
            '[admin_email]' => $settingsData['Settings']['admin_email'],
        );
        $data = $data+$settingData;
        $search = $replace = array();
        if (!empty($data)) {
            $search = array_keys($data);
            $replace = array_values($data);

            $result['Template']['header'] = str_replace($search, $replace, $result['Template']['header']);
            $result['Template']['content'] = str_replace($search, $replace, $result['Template']['content']);
            $result['Template']['footer'] = str_replace($search, $replace, $result['Template']['footer']);

            $html = $result['Template']['header'] . $result['Template']['content'] . $result['Template']['footer'];
            $this->sendEmail($data['[email]'], $html, $result['Template']['subject']);
        }

    }


    /**
     * Send email
     *
     * @param string $to
     * @param string $message
     * @param string $subject
     * @return void
     */
    public function sendEmail($to, $message, $subject, $attachments = NULL) {
        $SettingsModel = ClassRegistry::init('Settings');
        $settingsData = $SettingsModel->find('first', array('fields' => array(
                'email_setting',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'admin_email',
                'site_title'
        )));

        $email = new CakeEmail();

        if ($settingsData['Settings']['email_setting'] == '2') {
            #SMTP
            $email->config(array(
                'transport' => 'Smtp',
                'host' => $settingsData['Settings']['smtp_host'],
                'port' => $settingsData['Settings']['smtp_port'],
                'username' => $settingsData['Settings']['smtp_username'],
                'password' => $settingsData['Settings']['smtp_password'],
            ));
        } else {
            #PHP mail
            $email->config('default');
        }

        if (!empty($attachments)){
            $email->attachments($attachments);
        }
        $email->from($settingsData['Settings']['admin_email'], $settingsData['Settings']['site_title']);
        $email->to($to);

        $email->emailFormat('html');
        $email->subject($subject);

        try {
            $email->send($message);
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
        }
    }
}

