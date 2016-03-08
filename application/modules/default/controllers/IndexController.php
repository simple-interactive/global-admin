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
                    'password' => md5($this->getParam('password', null)),
                    'createdDate' => new \MongoDate()
                ]);
                $user->save();

                $mdToken = new App_Model_MDToken([
                    'userId' => (string)$user->id,
                    'tokens' => [
                        sha1(uniqid())
                    ]
                ]);
                $mdToken->save();
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
                $user->password = md5($this->getParam('password', null));

            $user->save();
        }

        $token = App_Model_MDToken::fetchOne([
            'userId' => (string) $user->id
        ]);
        if ($token)
            $this->view->tokens = $token->tokens;
        $this->view->user = $user;
        $this->render('edit');
    }

    public function deleteAction()
    {
        App_Model_User::remove([
            'id' => new \MongoId($this->getParam('id', null))
        ]);

        $this->view->users = App_Model_User::fetchAll();
        $this->render('index');
    }
}