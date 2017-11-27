<?php 
/**
* 
*/
class Upload
{
	
	public static $directory = "../../ng-amproj/";
	
	function dirCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;

		$check = checkToken($data['token']);

		if ($check) {
			$status->token = true;
		} else {
			return $status;
		}

		if (!file_exists(self::$directory . "assets/cars/" . $data['data'])) {
			mkdir(self::$directory . "assets/cars/" . $data['data'], 0755);
			mkdir(self::$directory . "assets/cars/" . $data['data'] . "/ext", 0755);
			mkdir(self::$directory . "assets/cars/" . $data['data'] . "/int", 0755);
			$status->data = true;
		}

		return $status;
	}

	function uploadCar($data) {

		$status = new stdClass();
		$status->data = false;
		$status->token = false;
		$status->file = array();

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

					if ($data['type'] == 'interior') {
						$name = 'interior.jpg';
					}

					$extension = str_replace("image/", "", $mime_type);
					file_put_contents($dir . $name, $fileImage);
					array_push($status->file, $name); 
				}
				$status->data = true;
			}
		}
		return $status;
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

	function delFiles($data) {
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

		$dirPath = $dir . $data['dir'];

		$status->data = $this->DeleteFiles($dirPath);

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

	function DeleteFiles($path) {
		$files = glob($path . '/*');
		foreach ($files as $file) {
			unlink($file);
		}
		return;
	}
}

?>