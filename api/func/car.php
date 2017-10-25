<?php
/**
* 
*/
class Car
{

	public static $table1 = "cars_prod";
	public static $table2 = "cars_brand";
	public static $table3 = "cars_detail";
	public static $table4 = "cars_stats";

	function getBrand($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT * FROM cars_brand";
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getCar($data) {
		$model = new Model;
		$model->connect();
		$filter = "";
		if (isset($data->filter)) {
			$filter += "WHERE c_id = " . $data['filter']['id'] . "";
		}
		$sql = "SELECT cars_prod.c_id,  
						cars_prod.name,
						cars_detail.harga,
						cars_stats.stats_id,
						cars_stats.status,
						cars_brand.brand_id,
						cars_brand.brand_nm,
						cars_detail.dir_img
				FROM " . self::$table1 . " 
				LEFT JOIN " . self::$table3 . " ON " . self::$table3 . ".cars_prod_id = " . self::$table1 . ".c_id 
				LEFT JOIN " . self::$table2 . " ON " . self::$table2 . ".brand_id = " . self::$table1 . ".brand_id_fk 
				LEFT JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id  " . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);
		$model->close();
	}

	function getCarDetail($data) {
		$model = new Model;
		$model->connect();

		$filter = "WHERE " .self::$table1 . ".c_id = " . $data['id'];

		$sql = "SELECT * FROM " . self::$table1 . " 
				LEFT JOIN " . self::$table3 . " ON " . self::$table3 . ".cars_prod_id = " . self::$table1 . ".c_id 
				LEFT JOIN " . self::$table2 . " ON " . self::$table2 . ".brand_id = " . self::$table1 . ".brand_id_fk 
				LEFT JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id  " . $filter;
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

		if ($check) {
			$status->token = true;
		} else {
			return echo $status;
		}

		$sql = "INSERT INTO " . self::$table1 . " (brand_id_fk, cars_model_id, name, add_by) VALUES (
				" . $data['data']['brand_id'] . ", 
				" . $data['data']['cars_model_id'] . ", 
				" . $data['data']['name'] . ", 
				" . $data['data']['add_by'] . " ) ";

		$q = mysqli_query($model->conn, $sql);

		if ($q) {
			$id = mysqli_insert_id($model->conn);
			$status->data = $id;
		}

		echo json_encode($status);

		$model->close();

	}

	function addCarDetail($data) {
		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
		} else {
			return echo $status;
		}

		$sql = "INSERT INTO " . self::$table3 . " (cars_prod_id, harga, tahun, nopol,
													bbm, km, trans_id, silinder, warna,
													showroom_id, cars_stats_id, dir_img, add_by) VALUES (
				" . $data['data']['id'] . ",
				" . $data['data']['harga'] . ",
				" . $data['data']['tahun'] . ",
				" . $data['data']['nopol'] . ",
				" . $data['data']['bbm'] . ",
				" . $data['data']['km'] . ",
				" . $data['data']['trans_id'] . ",
				" . $data['data']['silinder'] . ",
				" . $data['data']['warna'] . ",
				" . $data['data']['showroom_id'] . ",
				" . $data['data']['cars_stats_id'] . ",
				" . $data['data']['dir_img'] . ",
				" . $data['data']['add_by'] . " )";

		$q = mysqli_query($model->conn, $sql);

		if ($q) {
			$id = mysqli_insert_id($model->conn);
			$status->data = $id;
		}

		$model->close();
		echo json_encode($status);
	}

	function delCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$sql = "DELETE FROM " . self::$table1 . " VALUES ";

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
			$sql = "UPDATE " . self::$table1 . " SET";
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

	function uploadCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
			pathinfo($data->dir . $data->$file,PATHINFO_EXTENSION);
		}

		echo json_encode($status);

	}
}
?>