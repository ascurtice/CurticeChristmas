<?php
namespace Util\Model;

use Zend\Db\TableGateway\TableGateway;

class ChildrenTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getChildren($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(array('id' => $id));
        
        return $resultSet;
    }

    public function getChildrenByParent($pid){
        $resultSet = $this->tableGateway->select(array('parent' => $pid));
        return $resultSet;
    }

    public function saveChild(Children $children)
    {

        $data = array(
            'user' => $children->user,
            'parent' => $children->parent,
        );

        $id = (int)$children->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getChildren($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteChildren($pid){
        $this->tableGateway->delete(array('parent' => $pid));
    }

    public function deleteChild(Children $child){
        $this->tableGateway->delete(array('parent' => $child->parent, 'user'=> $child->user));
    }
}