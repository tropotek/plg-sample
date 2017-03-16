<?php
$config = \Tk\Config::getInstance();

// NOTE:
// You must manually include all required php files if you are not using composer to install the plugin.
// Alternatively be sure to use the plugin namespace for all classes such as \sample\Ems\MyClass


/** @var \Tk\Routing\RouteCollection $routes */
$routes = $config['site.routes'];

$params = array('section' => \App\Db\UserRole::SECTION_ADMIN);
$routes->add('Sample Admin Settings', new \Tk\Routing\Route('/sample/adminSettings.html', 'Ems\Controller\SystemSettings::doDefault', $params));

$params = array('section' => \App\Db\UserRole::SECTION_CLIENT);
$routes->add('Sample Institution Settings', new \Tk\Routing\Route('/sample/institutionSettings.html', 'Ems\Controller\InstitutionSettings::doDefault', $params));

$params = array('section' => array(\App\Db\UserRole::SECTION_CLIENT, \App\Db\UserRole::SECTION_STAFF));
$routes->add('Sample Course Profile Settings', new \Tk\Routing\Route('/sample/courseProfileSettings.html', 'Ems\Controller\CourseProfileSettings::doDefault', $params));

$params = array('section' => array(\App\Db\UserRole::SECTION_CLIENT, \App\Db\UserRole::SECTION_STAFF));
$routes->add('Sample Course Settings', new \Tk\Routing\Route('/sample/courseSettings.html', 'Ems\Controller\CourseSettings::doDefault', $params));


