<?php

class IndexController extends App_Controller_Base
{

    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('main');
    }

    public function indexAction()
    {
        $this->view->users = App_Model_User::fetchAll();
        $this->render('index');
    }

    public function adduserAction()
    {
        if ($this->_request->isPost()) {
            $count = App_Model_User::getCount([
                'email' => $this->getParam('email', null)
            ]);

            if ($count != 0) {
                $this->view->error = 'Email exists';
            }
            else {
                $user = new App_Model_User([
                    'businessName' => $this->getParam('businessName', 'Some Business Name'),
                    'email' => $this->getParam('email', null),
                    'password' => $this->getParam('password', null),
                    'createdDate' => time()
                ]);
                $user->save();
                $this->getResponse()->setRedirect('/');
            }

        }
        $this->render('adduser');
    }

    public function editAction()
    {
        $user = App_Model_User::fetchOne([
           'id' => $this->getParam('id', null)
        ]);
        if (!$user) {
            $this->redirect('/');
        }

        if ($this->_request->isPost()) {
            $user->businessName = $this->getParam('businessName', null);
            $user->email = $this->getParam('email', null);
            if (!empty($this->getParam('password', null)))
                $user->password = $this->getParam('password', null);

            $user->save();
        }
        $this->view->user = $user;
        $this->render('edit');
    }
}