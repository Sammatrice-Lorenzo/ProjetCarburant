<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

require_once('vendor/autoload.php');
require_once('vendor/autoload.php');
require './vendor/php-webdriver/webdriver/lib/Firefox/FirefoxDriver.php';
require './vendor/php-webdriver/webdriver/lib/Remote\DesiredCapabilities.php';
require './vendor/php-webdriver/webdriver/lib/Support/Events/EventFiringWebDriverNavigation.php';

	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");

	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // cache for 1 day
    }
	
    // Access-Control headers are received during OPTIONS requests
    if ($request_method == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        exit(0);
    }

    function getData(string $cp) {
        $serverUrl = 'http://localhost:4444';
        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::firefox());
        $driver->get('https://www.prix-carburants.gouv.fr/');
        $driver->findElement(WebDriverBy::cssSelector('input#rechercher_choix_carbu_0'))->click();
        $driver->findElement(WebDriverBy::cssSelector('input#rechercher_localisation'))->sendKeys($cp);
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('input.submit_recherche'))->submit();
        sleep(1);

        $rows = $driver->findElements(WebDriverBy::cssSelector('tr.data'));
        $desciptionElement = [];
        $results = [];

        foreach($rows as $row) {
            $desriptions = $row->findElements(WebDriverBy::cssSelector('div.pdv-description'));
            $chiffres = $row->findElement(WebDriverBy::cssSelector('td.chiffres'));
            $prix = $chiffres->findElements(WebDriverBy::cssSelector('span'));

            foreach($desriptions as $desription) {
                $title = $desription->findElement(WebDriverBy::cssSelector('h4.title'));
                $span = $desription->findElements(WebDriverBy::cssSelector('span'));

                $desciptionElement = [
                    "title" => $title->getText(),
                    "rue" => $span[0]->getText(),
                    "cp" => $span[1]->getText(),
                    "prix" => $prix[0]->getText(),
                    "date" => $prix[1]->getText()
                ];
            }
            $results[] = $desciptionElement;
        }
        echo json_encode($results);
        $driver->quit();
	}

    if ($request_method == "GET") {
        getData($_GET['cp']);
    } else {
        header("HTTP/1.0 405 Method Not Allowed");
    }
?>