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
require_once('func/upload.php');

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
	echo json_encode($map->addMap($data));
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

function getTrans() {
	$data = getData();
	$car = new Car;
	$car->getTrans($data);
}

function getModel() {
	$data = getData();
	$car = new Car;
	$car->getModel($data);
}

function getShowroom() {
	$data = getData();
	$car = new Car;
	$car->getShowroom($data);
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

function getCarSold() {
	$data = getData();
	$car = new Car;
	$car->getCarSold($data);
}

function addCar() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->addCar($data));
}

function addCarDetail() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->addCarDetail($data));
}

function delCar() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->delCar($data));
}

function soldCar() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->soldCar($data));
}

function editCar() {
	$data = getData();
	$car = new Car;
	$car->editCar($data);
}

function editCarDetail() {
	$data = getData();
	$car = new Car;
	json_encode($car->editCarDetail($data));
}

function addBrand() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->addBrand($data));
}

function delBrand() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->delBrand($data));
}

function addModel() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->addModel($data));
}

function delModel() {
	$data = getData();
	$car = new Car;
	echo json_encode($car->delModel($data));
}

// Upload

function dirCar() {
	$data = getData();
	$upload = new Upload;
	echo json_encode($upload->dirCar($data));
}

function uploadCar() {
	$data = getData();
	$upload = new Upload;
	echo json_encode($upload->uploadCar($data));
}

function delDir() {
	$data = getData();
	$upload = new Upload;
	echo json_encode($upload->delDir($data));
}

function delFiles() {
	$data = getData();
	$upload = new Upload;
	echo json_encode($upload->delFiles($data));
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

function addPromo() {
	$data = getData();
	$promo = new Promo;
	echo json_encode($promo->addPromo($data));
}

function delPromo() {
	$data = getData();
	$promo = new Promo;
	echo json_encode($promo->delPromo($data));
}

function promoToggle() {
	$data = getData();
	$promo = new Promo;
	echo json_encode($promo->promoToggle($data));
}

function getSocmed() {
	$data = getData();
	$promo = new Promo;
	echo json_encode($promo->getSocmed($data));
}

// Sim

function calcSim() {
	$data = getData();
	$sim = new Sim;
	echo json_encode($sim->calcSim($data));
}

function getValue() {
	$data = getData();
	$sim = new Sim;
	echo json_encode($sim->getValue($data));
}

function updateSim() {
	$data = getData();
	$sim = new Sim;
	echo json_encode($sim->updateSim($data));
}