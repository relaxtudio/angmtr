<?php

require_once('func/login.php');
require_once('func/crud.php');

function test() {
	echo getData();
}

function testData() {
	$login = new Login;
	$login->testData();
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