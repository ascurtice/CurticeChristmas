<?php
namespace Wishlist\Model;

use Zend\Db\TableGateway\TableGateway;

class CartTable
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

    public function getMyCart($id)
    {
        $resultSet = $this->tableGateway->select(array('userId' => $id));
        return $resultSet;
    }

    public function addToCart(Cart $cart)
    {
        $data = array(
            'userId' => $cart->userId,
            'wishlistId' => $cart->wishlistId,
        );
            
        $this->tableGateway->insert($data);
    }

    public function deleteFromCart($id)
    {
        $this->tableGateway->delete(array('wishlistId' => $id));
    }

}