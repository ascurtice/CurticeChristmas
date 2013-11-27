<?php
namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Login\Form\PasswordForm;
use Util\Model\User;

class PasswordController extends AbstractActionController
{
    protected $userTable;

    public function changeAction()
    {
        $session = new Container('userData');
        $uId = $session->userId;
        $uName = $session->username;

        $form = new PasswordForm;
        $form -> get('submit') -> setValue('Change Password');
        $form -> get('username') -> setValue($uName);

        $request = $this->getRequest();

        if($request->isPost()){
            $user = new User();
            $user->id = $uId;
            $user->password = $_POST['password'];
            $check = $this->getUserTable()->checkPass($user);
            if($check == 1){
                if($_POST['npassword'] === $_POST['ncpassword']){
                    $user->password = $_POST['npassword'];
                    $this->getUserTable()->updatePassword($user);
                } else {
                    $this->flashMessenger->addMessage('New passwords do not match');
                }
            } else {
                $this->flashMessenger->addMessage($check);
            }

            return $this->redirect()->toRoute('wishlist');
        }

        return array(
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
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