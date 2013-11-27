<?php
namespace Wishlist\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Wishlist\Model\Wishlist;
use Wishlist\Form\WishlistForm;

class WishlistController extends AbstractActionController
{
    protected $userTable;
    protected $wishlistTable;

    public function indexAction()
    {
        $session = new Container('userData');
        $uid = $session->userId;
    	
        $id = (int) $this->params()->fromRoute('id', $uid);
        
        return new ViewModel(array(
            'wishlist' => $this->getWishlistTable()->getWishlistByUser($id),
        ));
    }

    public function addAction()
    {
    	$session = new Container('userData');
        $id = $session->userId;
        
        $form = new WishlistForm();
        $form->get('submit')->setValue('Add');
        $form->get('user')->setValue($id);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $wishlist = new Wishlist();
            $form->setInputFilter($wishlist->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $wishlist->exchangeArray($form->getData());
                $wishlist->taken = 0;
                $this->getWishlistTable()->saveWishlist($wishlist);

                return $this->redirect()->toRoute('wishlist');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('brand', array(
                'action' => 'add'
            ));
        }
        $session = new Container('userData');
        $user = $session->userId;
        
        $item = $this->getWishlistTable()->getWishlist($id);

        $form  = new WishlistForm();
        $form->bind($item);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $wishlist = $form->getData();
                $wishlist->taken = 0;
                $this->getWishlistTable()->saveWishlist($form->getData());

                return $this->redirect()->toRoute('wishlist');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('wishlist');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getWishlistTable()->deleteWishlist($id);
            }
            return $this->redirect()->toRoute('wishlist');
        }

        return array(
            'id'    => $id,
            'wishlist' => $this->getWishlistTable()->getWishlist($id)
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

    public function getWishlistTable()
    {
        if(!$this->wishlistTable){
            $sm = $this->getServiceLocator();
            $this->wishlistTable = $sm->get('Wishlist\Model\WishlistTable');
        }
        return $this->wishlistTable;
    }
}