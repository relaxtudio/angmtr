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

	}

	function delMap() {

	}

	function editMap($data) {
		$model = new Model;
		$model->connect();

		$check = checkToken($data['token']);
		if ($check) {
			echo "go";
		}
		$model->close();
	}
}
?>