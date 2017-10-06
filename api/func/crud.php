<?php

class Crud {
	
	function insertProduct($data) {
		$login = new Login;
		$login->checkToken();
	}
}