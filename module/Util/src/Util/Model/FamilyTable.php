<?php
namespace Util\Model;

use Zend\Db\TableGateway\TableGateway;

class FamilyTable
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

    public function getFamily($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFamily(Family $family)
    {
        $data = array(
            'lastName' => $family->lastName,
        );

        $id = (int)$family->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getFamily($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
}