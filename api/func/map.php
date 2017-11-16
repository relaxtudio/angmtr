<?php  
/**
* 
*/
class Map 
{

	public static $table = "showroom";

	function getMap() {
		$model = new Model;
		$model->connect();
		$sql = "SELECT sr_id as id, sr_nm as lks, sr_alamat as descr, sr_telp as tlp, sr_kota as kota, lat, lng
				FROM " . self::$table;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q,MYSQLI_ASSOC);
		$model->close();
		echo json_encode($result);
	}

	function addMap($data) {

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
			$map = $data['data'];
			$sql = "INSERT INTO " . self::$table . " (sr_nm, sr_alamat, sr_telp, sr_kota, lat, lng) 
					VALUES ('" . $map['nama'] . "',
							'" . $map['alamat'] . "', 
							'" . $map['telp'] . "', 
							'" . $map['kota'] . "', 
							'" . $map['lat'] . "', 
							'" . $map['lng'] . "')";
			$q = mysqli_query($model->conn, $sql);
			if ($q) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;
	}

	function delMap($data) {

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
			$map = $data['data'];
			$sql = "DELETE FROM " . self::$table . " WHERE sr_id = " . $map['id'];
			$q = mysqli_query($model->conn, $sql);
			if ($q) {
				$status->data = true;
			}
		}

		$model->close();

		return $status;
	}

	function editMap($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		$model = new Model;
		$model->connect();
		$sql = "";
		
		if (isset($data->id)) {
			$sql = "UPDATE " . self::$table . " SET sr_nm = '" . $data['nama'] . "', 
									sr_alamat = '" . $data['alamat'] . "', 
									sr_telp = '" . $data['telp'] . "', 
									sr_kota = '" . $data['kota'] . "', 
									lat = '" . $data['lat'] . "', 
									lng = '" . $data['lng'] . "' 
									WHERE sr_id = " . $data['id'];	
		}

		if ($check) {
			$status->token = true;
			if (isset($data->id)) {
				mysqli_query($model->conn, $sql);
				$status->data = true;
			}
		}

		$model->close();
		echo json_encode($status);
	}
}
?>