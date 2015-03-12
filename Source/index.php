<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 * 
 * Vstupní bod aplikace
 */


/**
 * Vytvoření konteineru
 */
$container = require __DIR__ . '/app/bootstrap.php';


/**
 * Nastavení úvodní stránky
 */
$container->router = new Nette\Application\Routers\SimpleRouter('Home:default');
$container->getService('application')->run();
