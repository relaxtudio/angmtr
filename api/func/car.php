<?php
/**
* 
*/
class Car
{
	function getCar($data) {
		$model = new Model;
		$model->connect();
		$filter = "";
		if ($data['id']) {
			$filter += "WHERE c_id = " . $data['id'] . "";
		}
		$sql = "SELECT * FROM cars_prod" . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);
		echo json_encode($result);
		$model->close();
	}	
}
?>