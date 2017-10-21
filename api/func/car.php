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

		$model = new Model;
		$model->connect();
		$sql = "";

		if (isset($data->id)) {
			$sql = "UPDATE " . self::$table . " SET";
		}

		if ($check) {
			$status->token = true;
			if (isset($data->id)) {
				mysqli_query($model->conn, $sql);
				$status->data = true;
			}
		}

		$model->close();
	}

	function dirCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
			if (file_exists("../img/" . $data->dir) == false) {
				$status->data = true;
				mkdir("../img/" . $data->dir, 0755);
			}
		}

		echo json_encode($status);
	}
}
?>