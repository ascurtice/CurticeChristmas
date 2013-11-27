<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Util\Controller\Util' => 'Util\Controller\UtilController',
            'Util\Controller\Family' => 'Util\Controller\FamilyController',
            'Util\Controller\Users' => 'Util\Controller\UsersController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'util' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/util[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Util\Controller\Util',
                        'action'     => 'index',
                    ),
                ),
            ),
            'family' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/family[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Util\Controller\Family',
                        'action'     => 'index',
                    ),
                ),
            ),
            'users' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/users[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Util\Controller\Users',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'util' => __DIR__ . '/../view',
        ),
    ),
);