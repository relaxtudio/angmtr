<?php

class Login {
	function testData() {
		$model = new Model;
		$model->connect();
		$model->close();
	}

	function checkToken($data) {
		$model = new Model;
		$model->connect();
		$sql = "SELECT * FROM token WHERE token = '" . $data . "'";
		$result = mysqli_query($model->conn, $sql);
		$status = false;
		if (mysqli_num_rows($result) > 0) {
			$status = true;
		}
		$model->close();
		return $status;
	}

	function createUser($data) {
		$model = new Model;
		$model->connect();
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $data['password'] . $salt);
	    for($i = 0; $i < 65536; $i++) {
	        $password = hash('sha256', $password . $salt);
	    }
	    $check = "SELECT * FROM usr_lgn WHERE usr_nm = '" . $data['username'] . "'";
	    $q = mysqli_query($model->conn, $check);
	    $result = mysqli_fetch_all($q,MYSQLI_ASSOC);
	    if (empty($result)) {
	    	$result = true;
	    	$sql = "INSERT INTO usr_lgn (usr_nm, usr_pass, usr_salt, uid_cred_fk) VALUES ('" . $data['username'] . "', '" . $password . "', '" . $salt . "', '1')";
	    	mysqli_query($model->conn, $sql);
	    } else {
	    	$result = false;
	    }
	    $model->close();
		echo json_encode($result);
	}

	function loginUser($data) {
		$model = new Model;
		$model->connect();
		$get = "SELECT usr_id, usr_nm, usr_pass, usr_salt 
				FROM usr_lgn WHERE usr_nm = '" . $data['username'] . "'";
		$result = mysqli_fetch_array(mysqli_query($model->conn, $get));
		$salt = $result['usr_salt'];
		$usrid = $result['usr_id'];
		$usrname = $result['usr_nm'];
    	$check = hash('sha256', $data['password'] . $salt);
    	for ($i = 0; $i < 65536; $i++) { 
    		$check = hash('sha256', $check . $salt);
    	}
    	$status = false;
    	if ($check == $result['usr_pass']) {
    		$user = new stdClass();
    		$token = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    		$token = hash('sha256', $token);
    		$sql = "INSERT INTO token (token,usr_id) 
    				VALUES ('" . $token . "'," . $usrid . ")";
    		mysqli_query($model->conn, $sql);
    		$user->id = $usrid;
    		$user->name = $usrname;
    		$user->token = $token;
    		$status = $user;
    	}

		echo json_encode($status);
		$model->close();
	}

	function editUser($data) {
		$model = new Model;
		$model->connect();
		
		$model->close();
	}

	function changePass($data) {
		$model = new Model;
		$model->connect();

		$model->close();
	}
}