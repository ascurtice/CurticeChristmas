<?php
namespace Wishlist\Model;

use Zend\Db\TableGateway\TableGateway;

class WishlistTable
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

    public function getWishlist($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getWishlistByUser($user){
        $resultSet = $this->tableGateway->select(array('user' => $user));
        return $resultSet;
    }

    public function saveWishlist(Wishlist $wishlist)
    {
        $data = array(
            'user' => $wishlist->user,
            'description' => $wishlist->description,
            'cost'=> $wishlist->cost,
            'storeOrWebsite'=> $wishlist->storeOrWebsite,
            'additionalInformation'=> $wishlist->additionalInformation,
            'taken'=> $wishlist->taken,
        );

        $id = (int)$wishlist->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getWishlist($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function takeItem($id){
        $data= array(
            'taken' => 1,
        );

        $this->tableGateway->update($data, array('id' => $id));
    }

    public function releaseItem($id){
        $data = array(
            'taken' => 0,
        );
        $this->tableGateway->update($data, array('id' => $id));
    }

    public function deleteWishlist($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}