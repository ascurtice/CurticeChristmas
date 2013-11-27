<?php
namespace Wishlist\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Wishlist\Model\Wishlist;
use Wishlist\Form\WishlistForm;
use Wishlist\Model\Cart;

class ViewController extends AbstractActionController
{
    protected $userTable;
    protected $childrenTable;
    protected $wishlistTable;
    protected $cartTable;

    public function indexAction()
    {
        $session = new Container('userData');
        $uid = $session->userId;

        $me = $this->getUserTable()->getUser($uid);
        $family = $this->getUserTable()->getMyFamily($me->family);

        foreach($family as $person){
            if($person->id != $uid){
                $myFamily[$person->id] = $person->firstName;
            }
        }
        
        return new ViewModel(array(
            'family' => $myFamily,
        ));
    }

    public function wishlistAction(){
        $id = (int) $this->params()->fromRoute('id', 0);

        if($id){
            return new ViewModel(array(
                'wishlist' => $this->getWishlistTable()->getWishlistByUser($id),
                'person' => $this->getUserTable()->getUser($id),
            ));
        } else {
            return $this->redirect()->toRoute('wishlist');
        }
    }


    public function takeAction()
    {
    	$session = new Container('userData');
        $uid = $session->userId;

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('wishlist');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $item = new Cart();
                $item->userId = $uid;
                $item->wishlistId = $id;
                $this->getCartTable()->addToCart($item);
                $this->getWishlistTable()->takeItem($id);
            }
            $wishlist = $this->getWishlistTable()->getWishlist($id);
            return $this->redirect()->toRoute('view', array('action' => 'wishlist', 'id' => $wishlist->user));
        }

        return array(
            'id'    => $id,
            'wishlist' => $this->getWishlistTable()->getWishlist($id)
        );
    }

    public function cartAction()
    {
        $session = new Container('userData');
        $uid = $session->userId;
        $cartList = array();

        $myCart = $this->getCartTable()->getMyCart($uid);
        foreach($myCart as $cart){
            $item = $this->getWishlistTable()->getWishlist($cart->wishlistId);
            $user = $this->getUserTable()->getUser($item->user);
            $cartItem = array(
                'id' => $item->id,
                'familyMember' => $user->firstName,
                'item' => $item->description,
                'cost' => $item->cost,
                'storeOrWebsite' => $item->storeOrWebsite,
                'addtl' => $item->additionalInformation,
            );
            array_push($cartList, $cartItem);
       }

        return new ViewModel(array('cartList' => $cartList));
    }

    public function releaseAction()
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
                $this->getCartTable()->deleteFromCart($id);
                $this->getWishlistTable()->releaseItem($id);
            }
            return $this->redirect()->toRoute('view', array('action' => 'cart'));
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

    public function getChildrenTable()
    {
        if (!$this->childrenTable) {
            $sm = $this->getServiceLocator();
            $this->childrenTable = $sm->get('Util\Model\ChildrenTable');
        }
        return $this->childrenTable;
    }

    public function getWishlistTable()
    {
        if(!$this->wishlistTable){
            $sm = $this->getServiceLocator();
            $this->wishlistTable = $sm->get('Wishlist\Model\WishlistTable');
        }
        return $this->wishlistTable;
    }

    public function getCartTable()
    {
        if(!$this->cartTable){
            $sm = $this->getServiceLocator();
            $this->cartTable = $sm->get('Wishlist\Model\CartTable');
        }
        return $this->cartTable;
    }
}