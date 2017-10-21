<?php
/**
* 
*/
class Car
{

	public static $table = "cars_prod";

	function getCar($data) {
		$model = new Model;
		$model->connect();
		$filter = "";
		if (isset($data['id'])) {
			$filter += "WHERE c_id = " . $data['id'] . "";
		}
		$sql = "SELECT * FROM " . self::$table . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);
		$model->close();
	}

	function addCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$sql = "INSERT INTO " . self::$table . " VALUES ";

	}

	function delCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$sql = "DELETE FROM " . self::$table . " VALUES ";

	}

	function editCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

	}
}
?>