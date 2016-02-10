<?php

abstract class App_Controller_Base extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();

        $auth = new App_Service_Auth();
        $auth->checkAuth();
    }
}