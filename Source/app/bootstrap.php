<?php
/**
 * Z-Scheduler
 *
 * Last revison: 21.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Bootstrap soubor
 */


// Načtení Nette Loaderu
require __DIR__ . '/../libs/autoload.php';


// Nastavení údajů o verzi aplikace
require __DIR__ . '/version.php';

// Nastavení parematrů
$params = array();
$params['dirApp'] = realpath(__DIR__ . '/../app');
$params['dirCfg'] = realpath(__DIR__ . '/../app/config');									  // Konfigurační soubor
$params['dirLog'] = realpath(__DIR__ . '/../log');										  // Logování
$params['dirTmp'] = realpath(__DIR__ . '/../temp');										  // Pracovní adresář
$params['dirLib'] = realpath(__DIR__ . '/../libs');										  // Knihovna externích scriptů


// Vytvoření konfigurátoru
$configurator = new Nette\Configurator;

// Nastavení ladění
$configurator->setDebugMode(FALSE);
$configurator->enableDebugger($params['dirLog']);

// Nastavení adresáře pro cache
$configurator->setTempDirectory($params['dirTmp']);

// Nastavení Loaderu
$configurator->createRobotLoader()
	->addDirectory($params['dirApp'])
	->addDirectory($params['dirLib'])
	->register();
	
// Přidání parametrů
$configurator->addParameters($params);

// Načtení konfigurace
$configurator->addConfig($params['dirCfg'] . '/config.neon');

// Vytvoření kontejneru
$container = $configurator->createContainer();

return $container;
