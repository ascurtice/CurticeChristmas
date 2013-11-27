<?php
namespace Util\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Util\Model\User;
use Util\Model\Children;
use Util\Form\UsersForm;
use Util\Form\ChildrenForm;

class UsersController extends AbstractActionController
{
    protected $userTable;
    protected $roleTable;
    protected $familyTable;
    protected $childrenTable;

    public function indexAction()
    {
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        $roles = $this->getRoleTable()->fetchAll();
        foreach($roles as $role){
            $roleList[$role->id] = $role->role;
        }

        $users = $this->getUserTable()->fetchAll();
        $userList = array();
        foreach($users as $u){
            $childList = array();
            if ($u->role < 3){
                $children = $this->getChildrenTable()->getChildrenByParent($u->id);
                foreach($children as $c){
                    $child = $this->getUserTable()->getUser($c->user);
                    array_push($childList, $child->firstName);
                }
            }
            $user = array(
                'id' => $u->id,
                'firstName' => $u->firstName,
                'username' => $u->username,
                'role' => $roleList[$u->role],
                'children' => $childList,
            );

            array_push($userList, $user);
        }
        

        return new ViewModel(array(
            'users' => $userList,
        ));
    }

    public function addAction()
    {
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        $roles = $this->getRoleTable()->fetchAll();
        foreach ($roles as $r){
            $roleList[$r->id] = $r->role;
        }

        $families = $this->getFamilyTable()->fetchAll();
        foreach ($families as $f){
            $familyList[$f->id] = $f->lastName;
        }

        $form = new UsersForm();
        $form->get('submit')->setValue('Add');
        $form->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Role',
                'value_options' => $roleList,
            ),
        ));
        $form->add(array(
            'name' => 'family',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Family',
                'value_options' => $familyList,
            ),
        ));

        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user->exchangeArray($form->getData());
                $this->getUserTable()->saveUser($user);

                return $this->redirect()->toRoute('users');
            }
        }
        return array(
            'form' => $form,
        );
    }

    public function editAction()
    {
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user', array(
                'action' => 'add'
            ));
        }
        $user = $this->getUserTable()->getUser($id);

        $roles = $this->getRoleTable()->fetchAll();
        foreach ($roles as $r){
            $roleList[$r->id] = $r->role;
        }

        $families = $this->getFamilyTable()->fetchAll();
        foreach ($families as $f){
            $familyList[$f->id] = $f->lastName;
        }

        $children = $this->getChildrenTable()->getChildrenbyParent($id);
        $childList = array();
        foreach($children as $c){
            $child = $this->getUserTable()->getUser($c->user);
            $childList[$child->id] = $child->firstName;
        }

        $form  = new UsersForm();
        $form->bind($user);

        $form->get('submit')->setAttribute('value', 'Save');
        $form->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Role',
                'value_options' => $roleList,
            ),
        ));
        $form->add(array(
            'name' => 'family',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Family',
                'value_options' => $familyList,
            ),
        ));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUserTable()->saveUser($form->getData());

                return $this->redirect()->toRoute('user');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'role' => $user->role,
            'children' => $childList
        );
    }

    public function addChildAction(){
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        $kids = $this->getUserTable()->getUserByRole(3);
        foreach($kids as $kid){
            $childList[$kid->id] = $kid->firstName;
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        //get all current children
        $knownChildren = $this->getChildrenTable()->getChildrenByParent($id);
        $knownChildrenList = array();
        foreach($knownChildren as $kc){
            array_push($knownChildrenList, $kc->user);
        }

        $form = new ChildrenForm();
        $form->get('submit')->setAttribute('value', 'Save');
        $form->get('parent')->setAttribute('value', $id);
        $form->add(array(
            'name' => 'children',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array( 'multiple' => 'multiple'),
            'options' => array(
                'label' => 'Children',
                'value_options' => $childList,
            ),
        ));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->getChildrenTable()->deleteChildren($_POST['parent']);
            foreach($_POST['children'] as $c){
                $child = new Children();
                $child -> user = $c;
                $child -> parent = $_POST['parent'];
                $this->getChildrenTable()->saveChild($child);
            }

            return $this->redirect()->toRoute('users', array('action' => 'edit', 'id' => $_POST['parent']));

        }

        return array('form' => $form);
    }

    public function deleteChildAction(){
        
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Util\Model\UserTable');
        }
        return $this->userTable;
    }

    public function getRoleTable()
    {
        if (!$this->roleTable) {
            $sm = $this->getServiceLocator();
            $this->roleTable = $sm->get('Util\Model\RoleTable');
        }
        return $this->roleTable;
    }

    public function getFamilyTable()
    {
        if (!$this->familyTable) {
            $sm = $this->getServiceLocator();
            $this->familyTable = $sm->get('Util\Model\FamilyTable');
        }
        return $this->familyTable;
    }

    public function getChildrenTable()
    {
        if (!$this->childrenTable) {
            $sm = $this->getServiceLocator();
            $this->childrenTable = $sm->get('Util\Model\ChildrenTable');
        }
        return $this->childrenTable;
    }
}