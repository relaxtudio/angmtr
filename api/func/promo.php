<?php  

/**
* 
*/
class Promo {

	public static $table1 = "promo";
	
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

		$sql = "SELECT promo_id as id, promo_name as name, promo_dir as img FROM " . self::$table1 . $filter;
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);

		$model->close();

		return $result;
	}
}

?>