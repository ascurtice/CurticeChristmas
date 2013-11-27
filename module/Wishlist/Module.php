<?php
namespace Wishlist;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Wishlist\Model\WishlistTable;
use Wishlist\Model\Wishlist;
use Wishlist\Model\Cart;
use Wishlist\Model\CartTable;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Wishlist\Model\WishlistTable' =>  function($sm) {
                    $tableGateway = $sm->get('WishlistTableGateway');
                    $table = new WishlistTable($tableGateway);
                    return $table;
                },
                'WishlistTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Wishlist());
                    return new TableGateway('wishlist', $dbAdapter, null, $resultSetPrototype);
                },
                'Wishlist\Model\CartTable' =>  function($sm) {
                    $tableGateway = $sm->get('CartTableGateway');
                    $table = new CartTable($tableGateway);
                    return $table;
                },
                'CartTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cart());
                    return new TableGateway('cart', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}