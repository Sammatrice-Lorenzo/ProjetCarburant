<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;

require_once('vendor/autoload.php');
require './vendor/php-webdriver/webdriver/lib/Chrome/ChromeOptions.php';
require './vendor/php-webdriver/webdriver/lib/Remote/RemoteWebDriver.php';
require './vendor/php-webdriver/webdriver/lib/Chrome/ChromeDriver.php';
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
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']);

        $serverUrl = 'http://localhost:9515';
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        $driver = RemoteWebDriver::create($serverUrl, $capabilities);
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
                    "rue" => $span[0]->getText() != "" ? $span[0]->getText() : $span[1]->getText(),
                    "cp" => $span[0]->getText() != "" ? $span[1]->getText() : $span[2]->getText(),
                    "prix" => $prix[0]->getText() != "" ? $prix[0]->getText() : $prix[1]->getText(),
                    "date" => $prix[0]->getText() != "" ? $prix[1]->getText() : $prix[2]->getText()
                ];
            }
            $results[] = $desciptionElement;
        }

        header('Content-Type: application/json');
        echo json_encode($results ? $results : "Il n'y a pas de stations référencées dans la commune recherchée");
        $driver->quit();
	}

    if ($request_method == "GET") {
        getData($_GET['cp']);
    } else {
        header("HTTP/1.0 405 Method Not Allowed");
    }
?>