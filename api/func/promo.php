<?php  

/**
* 
*/
class Promo {

	public static $table1 = "promo";
	public static $table2 = "socmed";
	
	function getPromo($data) {
		$model = new Model;
		$model->connect();

		$filter = "";

		if (isset($data['filter'])) {
			$filter = " WHERE ";
			$filterArray = array();
			if (isset($data['filter']['active'])) {
				array_push($filterArray, "active = '" . $data['filter']['active'] . "'");
			}
			if (isset($data['filter']['id'])) {
				array_push($filterArray, "promo_id = " . $data['filter']['id']);
			}
			$filter = $filter . implode(" AND ",$filterArray);
		}

		$sql = "SELECT promo_id as id, 
					   promo_name as name, 
					   promo_dir as img,
					   active
				FROM " . self::$table1 . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);

		$model->close();

		return $result;
	}

	function addPromo($data) {
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

		if (isset($data['image'])) {
			$sql = "INSERT INTO " . self::$table1 . " (promo_name, promo_dir, active) VALUES ";
			$fileArray = array();
			foreach ($data['image'] as $key => $value) {
				$file = $value['name'];
				array_push($fileArray, "('" . $file . "','" . $file . "','Y')");
			}
			$sql = $sql . implode(", ", $fileArray);
			$q = mysqli_query($model->conn, $sql);
			if ($q) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;
	}

	function delPromo($data) {
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

		if (isset($data['data'])) {
			$promo = $data['data'];
			$sql = "DELETE FROM " . self::$table1 . " WHERE promo_id = " . $promo['id'];
			$q = mysqli_query($model->conn, $sql);
			if ($q) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;
	}

	function promoToggle($data) {
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

		if (isset($data['data'])) {
			$promo = $data['data'];
			$active = 'Y';
			if ($promo['active'] == 'Y') {
				$active = 'N';
			}
			
			$sql = "UPDATE " . self::$table1 . " SET active = '" . $active . "'
					WHERE promo_id = " . $promo['id'];
			$q = mysqli_query($model->conn, $sql);
			if ($q) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;
	}

	function getSocmed($data) {

		$model = new Model;
		$model->connect();

		$sql = "SELECT * FROM " . self::$table2;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);

		$model->close();

		return $result;
	}
}

?>