<?php
namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Login\Form\LoginForm;
use Util\Model\User;

class LoginController extends AbstractActionController
{
    protected $userTable;

    public function indexAction()
    {
        $form = new LoginForm;
        $form -> get('submit') -> setValue('Login');

        $request = $this->getRequest();

        if($request->isPost()){
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if($form->isValid()){
                $user->exchangeArray($form->getData());
                
                $check = $this->getUserTable()->checkUser($user);

                if($check == 1){
                    $storedUser = $this->getUserTable()->getUserbyUsername($user->username);
                    $session = new Container('userData');
                    if ($storedUser){
                        $session -> username = $storedUser -> username;
                        $session -> firstName = $storedUser -> firstName;
                        $session -> userId = $storedUser -> id;
                        $session -> role = $storedUser -> role;
                        
                        return $this->redirect()->toRoute('wishlist');
                    } else {
                        $this->flashMessenger()->addMessage('ERROR');
                    }
                    
                } else{
                    $this->flashMessenger()->addMessage($check);
                }
            }
        }

        return array(
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function loginAction(){
        return $this->redirect()->toRoute('login');
    }

    public function logoutAction(){
        $session = new Container('userData');
        $session->getManager()->getStorage()->clear();
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Util\Model\UserTable');
        }
        return $this->userTable;
    }
}