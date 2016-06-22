<?php

App::uses('AppTime', 'Utility');

final class AppConstants
{
    const DEFAULT_LOCALE = 'en';
    const DEFAULT_TIMEZONE = 'Asia/Kolkata';
    const ADMIN_ROLE = 1;
    const AUDITOR_ROLE = 2;
    const USER_ROLE = 3;
    const MESSAGE_TYPE_SUCCESS = 'success';
    const MESSAGE_TYPE_ERROR = 'error';
    const MESSAGE_TYPE_INFO = 'info';
    const MESSAGE_TYPE_OTHER = 'other';
    const RATING_COUNT = 10;

    /**
     * Available layout values
     *
     * default
     * fixed
     * top_nav
     */
    const LAYOUT = 'default';    

    /**
     * Available skin values
     *
     * skin-blue
     * skin-black
     * skin-red
     * skin-yellow
     * skin-purple
     * skin-green
     * skin-blue-light
     * skin-black-light
     * skin-red-light
     * skin-yellow-light
     * skin-purple-light
     * skin-green-light
     */
    const SKIN = "skin-blue";
    

    public static function getUserRoles()
    {
        return array('Admin' => 1,
            'Auditor' => 2,
            'User' => 3); //user
    }

    public static function array_filter_recursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value);
            }
        }
        return array_filter($input);
    }

}
?>
