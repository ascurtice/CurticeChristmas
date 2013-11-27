<?php
namespace Util\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class UtilController extends AbstractActionController
{
    public function indexAction()
    {
    	$session = new Container('userData');
       	$role = $session->role;
       	if ($role > 1){
       		return $this->redirect()->toRoute('wishlist');
       	}
    }
}