<?php

require_once('func/login.php');
require_once('func/crud.php');
require_once('func/map.php');
require_once('func/car.php');

function test() {
	echo getData();
}

function testData() {
	$login = new Login;
	$login->testData();
}

function checkToken() {
	$data = getData();
	$login = new Login;
	$login->checkToken($data);
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

// Car

function getCar() {
	$data = getData();
	$car = new Car;
	$car->getCar($data);
}