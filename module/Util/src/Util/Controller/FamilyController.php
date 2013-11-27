<?php
namespace Util\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Util\Model\Family;
use Util\Form\FamilyForm;

class FamilyController extends AbstractActionController
{
    protected $familyTable;

    public function indexAction()
    {
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        return new ViewModel(array(
            'families' => $this->getFamilyTable()->fetchAll()
        ));
    }

    public function addAction()
    {
        $session = new Container('userData');
        $role = $session->role;
        if ($role > 1){
            return $this->redirect()->toRoute('wishlist');
        }

        $form = new FamilyForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $family = new Family();
            $form->setInputFilter($family->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $family->exchangeArray($form->getData());
                $this->getFamilyTable()->saveFamily($family);

                return $this->redirect()->toRoute('family');
            }
        }
        return array('form' => $form);
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
            return $this->redirect()->toRoute('family', array(
                'action' => 'add'
            ));
        }
        $family = $this->getFamilyTable()->getFamily($id);

        $form  = new FamilyForm();
        $form->bind($family);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($family->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getFamilyTable()->saveFamily($form->getData());

                return $this->redirect()->toRoute('family');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function getFamilyTable()
    {
        if (!$this->familyTable) {
            $sm = $this->getServiceLocator();
            $this->familyTable = $sm->get('Util\Model\FamilyTable');
        }
        return $this->familyTable;
    }
}