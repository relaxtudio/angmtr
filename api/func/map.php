<?php  
/**
* 
*/
class Map 
{
	function getMap() {
		$model = new Model;
		$model->connect();
		$sql = "SELECT sr_id, sr_nm, sr_alamat, sr_telp, sr_kota, lat, lng
				FROM showroom";
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q,MYSQLI_ASSOC);
		$model->close();
		echo json_encode($result);
	}

	function addMap() {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);
	}

	function delMap() {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);
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
			$sql = "UPDATE showroom SET sr_nm = '" . $data['name'] . "', 
									sr_alamat = '" . $data['address'] . "', 
									sr_telp = '" . $data['phone'] . "', 
									sr_kota = '" . $data['city'] . "', 
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