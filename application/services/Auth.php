<?php

class App_Service_Auth
{

    public function checkAuth()
    {
        $config = Zend_Registry::get('config');
        if (@$_SERVER['PHP_AUTH_USER'] != $config['auth']['login'] || @$_SERVER['PHP_AUTH_PW'] != $config['auth']['password'])
            $this->_sendAuthHeader();
    }

    private function _sendAuthHeader()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}