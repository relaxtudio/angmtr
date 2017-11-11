<?php
if (!defined('SAFELOAD'))
    exit('ACCESS FORBIDDEN!');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-type: application/json');

require_once('func/login.php');
require_once('func/crud.php');
require_once('func/map.php');
require_once('func/car.php');
require_once('func/promo.php');
require_once('func/sim.php');

function test() {
	echo getData();
}

function testData() {
	$login = new Login;
	$login->testData();
}

function checkToken($data) {
	$login = new Login;
	return $login->checkToken($data);
}

function createUser() {
	$data = getData();
	$login = new Login;
	$login->createUser($data);
}

function loginUser() {
	$data = getData();
	$login = new Login;
	$login->loginUser($data);
}

function editUser() {
	$data = getData();
	$login = new Login;
	$login->editUser($data);
}

function insertProduct() {
	$data = getData();
	$crud = new Crud;
	$crud->insertProduct($data);
}

// Map

function getMap() {
	$data = getData();
	$map = new Map;
	$map->getMap();
}

function addMap() {
	$data = getData();
	$map = new Map;
	$map->addMap($data);
}

function delMap() {
	$data = getData();
	$map = new Map;
	$map->delMap($data);
}

function editMap() {
	$data = getData();
	$map = new Map;
	$map->editMap($data);
}

// Car

function getBrand() {
	$data = getData();
	$car = new Car;
	$car->getBrand($data);
}

function getCar() {
	$data = getData();
	$car = new Car;
	$car->getCar($data);
}

function getCarDetail() {
	$data = getData();
	$car = new Car;
	$car->getCarDetail($data);
}

function getCarSum() {
	$data = getData();
	$car = new Car;
	$car->getCarSum($data);
}

function addCar() {
	$data = getData();
	$car = new Car;
	$car->addCar($data);
}

function delCar() {
	$data = getData();
	$car = new Car;
	$car->delCar($data);
}

function editCar() {
	$data = getData();
	$car = new Car;
	$car->editCar($data);
}

function editCarDetail() {
	$data = getData();
	$car = new Car;
	$car->editCarDetail($data);
}

function dirCar() {
	$data = getData();
	$car = new Car;
	$car->dirCar($data);
}

function testUpload() {
	$data = getData();
	$car = new Car;
	$car->testUpload($data);
}

// Promo

function getPromo() {
	$data = getData();
	$promo = new Promo;
	echo json_encode($promo->getPromo($data));
}

// Sim

function calcSim() {
	$data = getData();
	$sim = new Sim;
	echo json_encode($sim->calcSim($data));
}