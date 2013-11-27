<?php
namespace Util\Model;

use Zend\Db\TableGateway\TableGateway;
use Util\Model\User;
use Zend\Crypt\Password\BCrypt;

class UserTable
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

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getUserbyUsername($username){
        $rowset = $this->tableGateway->select(array('username' => $username));
        $row = $rowset->current();
        return $row;
    }

    public function getUserbyRole($role){
        $resultSet = $this->tableGateway->select(array('role' => $role));
        return $resultSet;
    }

    public function saveUser(User $user)
    {
         $bcrypt = new BCrypt();
         $pass = $bcrypt -> create($user->password);

        $data = array(
            'family' => $user->family,
            'role' => $user->role,
            'firstName' => $user->firstName,
            'username' => $user->username,
            'password' => $pass,
        );

        $id = (int)$user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function updatePassword(User $user){
        $bcrypt = new BCrypt;
        $newPass = $bcrypt -> create($user->password);

        $data = array(
            'password' => $newPass,
        );

        $this->tableGateway->update($data, array('id' => $user->id));

    }

    public function getMyFamily($family){
        $resultSet = $this->tableGateway->select(array('family' => $family));
        return $resultSet;
    }

    public function checkUser(User $user){
        $username = $user->username;
        $pass = $user->password;
        $storedUser = $this->getUserbyUsername($username);

        if ($storedUser){
            $suPass = $storedUser -> password;    
        } else {
            $e = 'Incorrect username or password!';
            return $e;
        }
        if($suPass){
            $bcrypt = new BCrypt();
            $check = $bcrypt -> verify($pass, $suPass);
            if($check == 1) {
                return 1;
            } else {
                $e = 'Incorrect username or password!';
                return $e;
            }
        } else {
            $e = 'Incorrect username or password!';
            return $e;
        }
    }

    public function checkPass(User $user){
        $u = $this->getUser($user->id);
        $uPass = $u->password;

        $bcrypt = new BCrypt();
        $check = $bcrypt -> verify($user->password, $uPass);
        if($check == 1){
            return 1;
        } else {
            $e = 'Current password does not match';
            return $e;
        }
    }
}