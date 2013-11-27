<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Wishlist\Controller\Wishlist' => 'Wishlist\Controller\WishlistController',
            'Wishlist\Controller\View' => 'Wishlist\Controller\ViewController',
            'Wishlist\Controller\Children' => 'Wishlist\Controller\ChildrenController'
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'wishlist' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/wishlist[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Wishlist\Controller\Wishlist',
                        'action'     => 'index',
                    ),
                ),
            ),
            'children' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/children[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Wishlist\Controller\Children',
                        'action'     => 'index',
                    ),
                ),
            ),
            'view' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/view[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Wishlist\Controller\View',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'wishlist' => __DIR__ . '/../view',
        ),
    ),
);