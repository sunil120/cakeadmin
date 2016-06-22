<?php

class SessionUser {
    /*
     * Tubes Constant
     */
    const ADMIN = 1;
    //const ROLE_ADMIN = 1;
    //const ROLE_USER = 2;
    protected static $_userInfo = null;
   
    public function setProperties($properties = array())
    {
        foreach($properties as $property => $data)
        {
            if(is_string($data)) {
                $this->{$property} = $data;
            }
        }
    }
    
    public static function getInstance()
    {
        if(is_null(self::$_userInfo)) {
            self::$_userInfo = new self();
        }
        return self::$_userInfo;
    }
    public static function getSessionUserId() {
        $userInfo = self::getInstance();
        //pr($userInfo);
        return isset($userInfo->id) ? $userInfo->id : 0;
    }
    
    public static function getSessionUserEmail() {
        $userInfo = self::getInstance();
        
        return isset($userInfo->email) ? $userInfo->email : 0;
    }
    
    public static function getSessionUserCountry() {
        $userInfo = self::getInstance();
        
        return isset($userInfo->country_id) ? $userInfo->country_id : 0;
    }
    
    public static function getSessionUserName() {
        $userInfo = self::getInstance();
        return isset($userInfo->user_name) ? $userInfo->user_name : 0;
    }
    
    
    public static function getSessionUserRole(){
        $userInfo = self::getInstance();
        
        return isset($userInfo->role_id) ? $userInfo->role_id : 0;
    }

    public static function setCurrentUser($userInfo) {
		
        $instance = self::getInstance();
        $instance->setProperties($userInfo);
    }
    
    public static function isAdmin()
    {
        $role = AppConstants::getUserRoles();
        $userrole = self::getSessionUserRole();
        return $userrole == $role['Administrator'] ? 1 : 0;
    }
    
    public static function isUser()
    {
        $role = AppConstants::getUserRoles();
        @$userrole = self::getSessionUserRole();
        //pr($userrole);die;
        return $userrole == $role['User'] ? 1 : 0;
    }
    
    public static function isModerator()
    {
        $role = AppConstants::getUserRoles();
        $role['Moderator'];
        $userrole = self::getSessionUserRole();
        return $userrole == $role['Moderator'] ? 1 : 0;
    }
    
    public static function isClient()
    {
        $role = AppConstants::getUserRoles();
        $role['Client'];
        $userrole = self::getSessionUserRole();
        return $userrole == $role['Client'] ? 1 : 0;
    }
    
    
}
