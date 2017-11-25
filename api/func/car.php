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
	public static $directory = "../../ng-amproj/";

	function getBrand($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT brand_id as id, brand_nm as name FROM " . self::$table2;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getTrans($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT trans_id as id, trans_nm as name FROM " . self::$table5;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getModel($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT cars_model_id as id, value as name FROM " . self::$table7;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getShowroom($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT sr_id as id, sr_nm as name FROM " . self::$table6;
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
			if (isset($data['filter']['brand'])) {
				$filter = $filter . " WHERE cars_brand.brand_nm like '%" . $data['filter']['brand'] . "%'";
			}
			if (isset($data['filter']['page']) && isset($data['filter']['limit'])) {
				$limit = " LIMIT " . $data['filter']['limit'];
				$offset = " OFFSET " . $data['filter']['limit'] * ($data['filter']['page'] - 1);
			}
			if (isset($data['filter']['search'])) {
				$filter = $filter . " WHERE cars_brand.brand_nm like '%" . $data['filter']['search'] . "%' OR
									cars_prod.name like '%" . $data['filter']['search'] . "%' OR
									cars_stats.status like '%" . $data['filter']['search'] . "%' OR
									cars_model.value like '%" . $data['filter']['search'] . "%' OR
									cars_detail.warna like '%" . $data['filter']['search'] . "%'";
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
						cars_model.cars_model_id as cars_model_id,
						cars_model.value as model,
						cars_detail.warna as warna,
						cars_detail.tahun as tahun,
						usr_lgn.usr_nm as addby,
						cars_detail.km as km,
						showroom.sr_nm as showroom,
						cars_detail.dir_img,
						cars_detail.updated
				FROM " . self::$table1 . " 
				JOIN " . self::$table3 . " ON " . self::$table3 . ".cars_prod_id = " . self::$table1 . ".c_id 
				JOIN " . self::$table2 . " ON " . self::$table2 . ".brand_id = " . self::$table1 . ".brand_id_fk 
				JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id 
				JOIN " . self::$table5 . " ON " . self::$table5 . ".trans_id = " . self::$table3 . ".trans_id 
				JOIN " . self::$table7 . " ON " . self::$table7 . ".cars_model_id = " . self::$table1 . ".cars_model_id 
				JOIN " . self::$table6 . " ON " . self::$table6 . ".sr_id = " . self::$table3 . ".showroom_id 
				JOIN " . self::$sptable . " ON " . self::$sptable . ".usr_id = " . self::$table3 . ".add_by 
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
			if (isset($data['filter']['id'])) {
				$filter = $filter . " WHERE cars_detail.cars_prod_id = " . $data['filter']['id'];
			}			
		}

		$sql = "SELECT * FROM " . self::$table3 . "
				LEFT JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id 
				LEFT JOIN " . self::$table5 . " ON " . self::$table5 . ".trans_id = " . self::$table3 . ".trans_id 
				LEFT JOIN " . self::$table6 . " ON " . self::$table6 . ".sr_id = " . self::$table3 . ".showroom_id" . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getCarSum($data) {
		$model = new Model;
		$model->connect();

		$filter = "";
		
		if (isset($data['filter']['search'])) {
			$filter = $filter . " WHERE cars_brand.brand_nm like '%" . $data['filter']['search'] . "%' OR
									cars_prod.name like '%" . $data['filter']['search'] . "%' OR
									cars_stats.status like '%" . $data['filter']['search'] . "%' OR
									cars_model.value like '%" . $data['filter']['search'] . "%' OR
									cars_detail.warna like '%" . $data['filter']['search'] . "%'";
		}

		$sql = "SELECT showroom.sr_nm as showroom,
						count(cars_prod.c_id) as total
				FROM " . self::$table1 . " 
				JOIN " . self::$table3 . " ON " . self::$table3 . ".cars_prod_id = " . self::$table1 . ".c_id 
				JOIN " . self::$table2 . " ON " . self::$table2 . ".brand_id = " . self::$table1 . ".brand_id_fk 
				JOIN " . self::$table4 . " ON " . self::$table4 . ".stats_id = " . self::$table3 . ".cars_stats_id 
				JOIN " . self::$table5 . " ON " . self::$table5 . ".trans_id = " . self::$table3 . ".trans_id 
				JOIN " . self::$table7 . " ON " . self::$table7 . ".cars_model_id = " . self::$table1 . ".cars_model_id 
				JOIN " . self::$table6 . " ON " . self::$table6 . ".sr_id = " . self::$table3 . ".showroom_id 
				JOIN " . self::$sptable . " ON " . self::$sptable . ".usr_id = " . self::$table3 . ".add_by 
				" . $filter . " GROUP BY showroom.sr_nm ";
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function getCarSold($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT count(cp.c_id) as total 
				FROM " . self::$table1 . " cp
				JOIN " . self::$table3 . " cd ON cd.cars_prod_id = cp.c_id
				WHERE cd.cars_stats_id = 2";
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);

		$model->close();
	}

	function addCar($data) {

		$model = new Model;
		$model->connect();

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
				" . intval($data['data']['brand_id']) . ", 
				" . intval($data['data']['cars_model_id']) . ", 
				'" . $data['data']['name'] . "', 
				" . intval($data['data']['add_by']) . " ) ";

		$q = mysqli_query($model->conn, $sql);

		if ($q) {
			$id = mysqli_insert_id($model->conn);
			$status->data = $id;
		}

		$model->close();

		return $status;
	}

	function addCarDetail($data) {
		$model = new Model;
		$model->connect();

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
													showroom_id, cars_stats_id, dir_img, add_by, updated) VALUES (
				" . intval($data['data']['id']) . ",
				" . intval($data['data']['harga']) . ",
				" . intval($data['data']['tahun']) . ",
				'" . $data['data']['nopol'] . "',
				'" . $data['data']['bbm'] . "',
				" . intval($data['data']['km']) . ",
				" . intval($data['data']['trans_id']) . ",
				" . intval($data['data']['silinder']) . ",
				'" . $data['data']['warna'] . "',
				" . intval($data['data']['showroom_id']) . ",
				" . intval($data['data']['cars_stats_id']) . ",
				'" . $data['data']['dir_img'] . "',
				" . intval($data['data']['add_by']) . ", now() )";

		$q = mysqli_query($model->conn, $sql);

		if ($q) {
			$id = mysqli_insert_id($model->conn);
			$status->data = $id;
		}

		$model->close();

		return $status;
	}

	function delCar($data) {

		$model = new Model;
		$model->connect();

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
		} else {
			return $status;
		}

		$sql = "DELETE FROM " . self::$table3 . " WHERE cars_prod_id = " . $data['data']['id'];
		$q = mysqli_query($model->conn, $sql);
		if ($q) {
			$sql2 = "DELETE FROM " . self::$table1 . " WHERE c_id = " . $data['data']['id'];
			$q2 = mysqli_query($model->conn, $sql);
			if ($q2) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;

	}

	function soldCar($data) {

		$model = new Model;
		$model->connect();

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
		} else {
			return $status;
		}

		$sql = "UPDATE " . self::$table3 . " SET cars_stats_id = 2, updated = now()
				WHERE cars_prod_id = " . $data['id'];

		if (isset($data['id'])) {
			mysqli_query($model->conn, $sql);
			$status->data = true;
		}

		$model->close();

		return $status;

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
			$sql = "UPDATE " . self::$table1 . " SET ";
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

	function addBrand($data) {
		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$model = new Model;
		$model->connect();

		$sql = "INSERT INTO " . self::$table2 . " (brand_nm) 
				VALUES ('" . $data['data'] . "')";
		$q = mysqli_query($model->conn, $sql);
		if ($q) {
			$status->data = true;
		}

		$model->close();

		return $status;
	}

	function delBrand($data) {
		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$model = new Model;
		$model->connect();

		$sql = "DELETE FROM " . self::$table2 . " WHERE 
				brand_id = " . $data['data'];
		$q = mysqli_query($model->conn, $sql);
		if ($q) {
			$status->data = true;
		}

		$model->close();

		return $status;
	}

	function addModel($data) {
		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$model = new Model;
		$model->connect();

		$sql = "INSERT INTO " . self::$table7 . " (value) 
				VALUES ('" . $data['data'] . "')";
		$q = mysqli_query($model->conn, $sql);
		if ($q) {
			$status->data = true;
		}

		$model->close();

		return $status;
	}

	function delModel($data) {
		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$model = new Model;
		$model->connect();

		$sql = "DELETE FROM " . self::$table7 . " WHERE 
				cars_model_id = " . $data['data'];
		$q = mysqli_query($model->conn, $sql);
		if ($q) {
			$status->data = true;
		}

		$model->close();

		return $status;
	}

	function dirCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
			if (file_exists(self::$directory . "assets/cars/" . $data['data']) == false) {
				$status->data = true;
				mkdir(self::$directory . "assets/cars/" . $data['data'], 0755);
				mkdir(self::$directory . "assets/cars/" . $data['data'] . "/ext", 0755);
				mkdir(self::$directory . "assets/cars/" . $data['data'] . "/int", 0755);
			}
		}

		echo json_encode($status);
	}

	function uploadCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);
		$type = $data['type'];
		$dir = '';
		if ($data['type'] == 'preview' || $data['type'] == 'exterior' || $data['type'] == 'interior') {
			$dir = self::$directory . 'assets/cars/' . $data['dir'] . '/';
		} else {
			$dir = self::$directory . 'assets/' . $data['dir'] . '/';
		}

		if ($data['type'] == 'exterior') {
			$dir = $dir . 'ext/';
		}

		if ($data['type'] == 'interior') {
			$dir = $dir . 'int/';
		}

		if ($check) {
			$status->token = true;
			// pathinfo($data->dir . $data->$file,PATHINFO_EXTENSION);
			if (isset($data['image'])) {
				$mime_type = '';
				foreach ($data['image'] as $key => $value) {
					$file = $value['file'];
					$name = $value['name'];

					list($type, $file) = explode(';', $file);
					list(, $file)      = explode(',', $file);
					$fileImage = base64_decode($file);
					$mime_type = finfo_buffer(finfo_open(), $fileImage, FILEINFO_MIME_TYPE);
					$extension = str_replace("image/", "", $mime_type);
					
					if ($data['type'] == 'preview') {
						$name = 'preview.jpg';
					}

					$extension = str_replace("image/", "", $mime_type);
					file_put_contents($dir . $name, $fileImage);
				}
				$status->data = true;
			}
		}

		echo json_encode($status);

	}

	function delDir($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
		} else {
			return $status;
		}

		$dir = self::$directory . "assets/";
		$dirPath = $dir;
		// $dirPath = $dir . $data['dir'];

		if (!$data['dir'] || !$data['type'] || strpos($data['dir'], '.') !== false) {
			return $status;
		}

		if ($data['type'] == 'cars') {
			$dirPath = $dirPath . 'cars/' . $data['dir'];
		}
		if ($data['type'] == 'promo') {
			$dirPath = $dirPath . 'promo/' . $data['dir'];
		}
		if ($data['type'] == 'car-brands') {
			$dirPath = $dirPath . 'car-brands/' . $data['dir'];
		}

		$status->data = $this->Delete($dirPath);

		return $status;
	}

	function Delete($path) {
		$files = glob($path . '/*');
		foreach ($files as $file) {
			is_dir($file) ? $this->Delete($file) : unlink($file);
		}
		rmdir($path);
	 	return;
	}

	function testUpload($data) {
		$mime_type = '';
		if (isset($data['image'])) {
			$file = $data['image'];
			list($type, $file) = explode(';', $file);
			list(, $file)      = explode(',', $file);
			$fileImage = base64_decode($file);
			$mime_type = finfo_buffer(finfo_open(), $fileImage, FILEINFO_MIME_TYPE);
			
			$extension = str_replace("image/", "", $mime_type);
			file_put_contents('../assets/image.' . $extension, $fileImage);
		}

		echo json_encode($mime_type);
	}
}
?>