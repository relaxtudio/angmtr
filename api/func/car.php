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
	public static $table5 = "cars_transmission";
	public static $table6 = "showroom";
	public static $table7 = "cars_model";
	public static $sptable = "usr_lgn";

	function getBrand($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT brand_id as id, brand_nm as name FROM " . self::$table2;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getCar($data) {
		$model = new Model;
		$model->connect();
		$filter = "";
		$limit = "";
		$offset = "";
		if (isset($data['filter'])) {
			if (isset($data['filter']['id'])) {
				$filter = $filter . " WHERE c_id = " . $data['filter']['id'];
			}
			if (isset($data['filter']['page']) && isset($data['filter']['limit'])) {
				$limit = " LIMIT " . $data['filter']['limit'];
				$offset = " OFFSET " . $data['filter']['limit'] * ($data['filter']['page'] - 1);
			}
			
		}
		$sql = "SELECT cars_prod.c_id as id,  
						cars_prod.name as name,
						cars_detail.harga,
						cars_stats.stats_id as stats_id,
						cars_stats.status as status,
						cars_brand.brand_id as brand_id,
						cars_brand.brand_nm as brand,
						cars_transmission.trans_nm as trans,
						cars_model.value as model,
						cars_detail.warna as warna,
						cars_detail.tahun as tahun,
						usr_lgn.usr_nm as addby,
						cars_detail.dir_img
				FROM " . self::$table1 . " 
				LEFT JOIN " . self::$table3 . " ON " . self::$table3 . ".cars_prod_id = " . self::$table1 . ".c_id 
				LEFT JOIN " . self::$table2 . " ON " . self::$table2 . ".brand_id = " . self::$table1 . ".brand_id_fk 
				LEFT JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id 
				LEFT JOIN " . self::$table5 . " ON " . self::$table5 . ".trans_id = " . self::$table3 . ".trans_id 
				LEFT JOIN " . self::$table7 . " ON " . self::$table7 . ".cars_model_id = " . self::$table1 . ".cars_model_id 
				LEFT JOIN " . self::$sptable . " ON " . self::$sptable . ".usr_id = " . self::$table3 . ".add_by 
				" . $filter . "
				ORDER BY cars_prod.c_id " . $limit . $offset;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);
		$model->close();
	}

	function getCarDetail($data) {
		$model = new Model;
		$model->connect();

		$filter = "";

		if (isset($data['filter'])) {
			
		}

		$sql = "SELECT * FROM " . self::$table3 . "
				LEFT JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id 
				LEFT JOIN " . self::$table5 . " ON " . self::$table5 . ".trans_id = " . self::$table3 . ".trans_id " . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getCarSum($data) {
		$model = new Model;
		$model->connect();

		$filter = "";
		if (isset($data->filter)) {
			$filter += "";
		}

		$sql = "SELECT s.sr_nm as showroom,
				count(cp.c_id) as total
				FROM " . self::$table1 . " cp
				LEFT JOIN " . self::$table3 . " cd ON cd.cars_prod_id = cp.c_id
				LEFT JOIN " . self::$table6 . " s ON s.sr_id = cd.showroom_id
				GROUP BY s.sr_nm";
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
			return $status;
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
			return $status;
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

		if ($check) {
			$status->token = true;
		} else {
			return $status;
		}

		$sql = "DELETE FROM " . self::$table1 . " WHERE c_id = " . $data['data']['id'];

		$q = mysqli_query($model->conn, $sql);

		if ($q) {
			$status->data = true;
		}

		echo json_encode($status);

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

	function editCarDetail($data) {

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