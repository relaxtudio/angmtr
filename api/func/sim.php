<?php  
/**
	* 
	*/
class Sim
{
	public static $table1 = "premi_as";
	public static $table2 = "bunga_bcaf";
	public static $table3 = "biaya_bcaf";
	public static $table4 = "fixcap_bcaf";

	function calcSim($data) {

		$return = array();
		$return['mobil'] = new stdClass();
		$return['asuransi'] = new stdClass();
		$return['bunga'] = new stdClass();
		$return['prepayment'] = new stdClass();

		$pro = $this->getProvisi($data);

		$provisi = intval($pro) / 100;

		$dp = ($data['c_harga'] * $data['c_dp']) / 100;
		$sisa = ($data['c_harga'] - $dp) * (1 + $provisi);
		$masaangsr = ($data['bunga_thn'] * 12) - 1;

		$return['mobil']->harga = $data['c_harga'];
		$return['mobil']->dpPerc = $data['c_dp'];
		$return['mobil']->dp = $dp;

		$percAs = $this->calcAsuransi($data);
		$return['asuransi']->jenis = $data['jenis_as'];
		$return['asuransi']->premi = $percAs[0];
		$return['asuransi']->masa = $data['thn_as'];
		$return['asuransi']->total = ($sisa * $percAs[0]) / 100;

		$sisaas = $sisa + $return['asuransi']->total;

		if (intval($data['bunga_thn']) > 4) {
			$fix = $this->fixCap($data)[0];
			$return['bunga']->term1 = (($sisaas + ($sisaas * $fix['term1']) / 100)) / ($data['bunga_thn'] * 12);
			$return['bunga']->term2 = (($sisaas + ($sisaas * $fix['term2']) / 100)) / ($data['bunga_thn'] * 12);
			$return['bunga']->angsuran = $return['bunga']->term1;
		} else {
			$percBu = $this->calcBunga($data);
			$return['bunga']->tenor = $data['bunga_thn'];
			$return['bunga']->bunga = $percBu[0];
			$return['bunga']->total = ($sisaas * $percBu[0]) / 100; // * $data['bunga_thn']
			$return['bunga']->masaangsr = $masaangsr;
			$return['bunga']->angsuran = ($sisaas + $return['bunga']->total) / ($data['bunga_thn'] * 12);
		}

		// $percBu = $this->calcBunga($data);
		// $return['bunga']->tenor = $data['bunga_thn'];
		// $return['bunga']->bunga = $percBu[0];
		// $return['bunga']->total = ($sisaas * $percBu[0] * $data['bunga_thn']) / 100;
		// $return['bunga']->masaangsr = $masaangsr;
		// $return['bunga']->angsuran = ($sisaas + $return['bunga']->total) / $masaangsr;

		$preArray = $this->calcPrepayment($data);
		$return['prepayment']->dp = $dp;
		$return['prepayment']->angsuran = $return['bunga']->angsuran;
		$return['prepayment']->fiducia = $preArray[0] + $preArray[1] + $preArray[2];
		$return['prepayment']->aspolis = ($data['c_harga'] * $percAs[0]) / 100;
		$return['prepayment']->crdtpro = ($sisaas * $preArray[3]) / 100;

		return $return;
	}

	function getProvisi($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT provisi_on_loan FROM " . self::$table3 . "
				WHERE " . $data['c_harga'] . " BETWEEN hutang_min AND hutang_max 
				AND tenor_thn = " . $data['bunga_thn'];

		$q = mysqli_query($model->conn, $sql);
		$result = $q->fetch_row();

		$model->close();

		return $result;
	}

	function calcAsuransi($data) {

		$model = new Model;
		$model->connect();

		$sql = "SELECT thn" . $data['thn_as'] . " FROM " . self::$table1 . " 
				WHERE " . $data['thnmobil'] . " BETWEEN min_thn_mobil_fk AND max_thn_mobil_fk 
				AND " . $data['c_harga'] . " BETWEEN min_otr AND max_otr 
				AND jenis_as = '" . $data['jenis_as'] . "'";

		$q = mysqli_query($model->conn, $sql);
		$result = $q->fetch_row();
		$model->close();

		return $result;
	}

	function calcBunga($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT thn" . $data['bunga_thn'] . " FROM " . self::$table2 . " 
				WHERE " . $data['thnmobil'] . " BETWEEN thn_mobil_min AND thn_mobil_max 
				AND min_dp >= " . $data['c_dp'];

		$q = mysqli_query($model->conn, $sql);
		$result = $q->fetch_row();
		$model->close();

		return $result;
	}

	function calcPrepayment($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT biaya_adm_polis, biaya_adm, fiducia, credit_protection FROM " . self::$table3 . " 
				WHERE " . $data['c_harga'] . " BETWEEN hutang_min AND hutang_max 
				AND tenor_thn = " . $data['bunga_thn'];

		$q = mysqli_query($model->conn, $sql);
		$result = $q->fetch_row();

		$model->close();

		return $result;
	}

	function fixCap($data) {
		$model = new Model;
		$model->connect();

		$sql = "SELECT term1, term2 FROM " . self::$table4 . "
				WHERE " . $data['thnmobil'] . " BETWEEN thn_mobil_min AND thn_mobil_max 
				AND tenor_thn = " . $data['bunga_thn'];

		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);

		$model->close();

		return $result;
	}

	function getValue($data) {

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

		$sql = '';

		if (isset($data['data'])) {
			$pointer = $data['data']['table'];
			if ($pointer == 'bunga') {
				$table = self::$table2;
				$sql = "SELECT 	id_bbcaf as id, 
								thn_mobil_min as thnmin, 
								thn_mobil_max as thnmax, 
								min_dp as dp, 
								thn1, 
								thn2, 
								thn3, 
								thn4
						FROM " . $table;
			} elseif ($pointer == 'fixcap') {
				$table = self::$table4;
				$sql = "SELECT 	id_fc as id, 
								thn_mobil_min as thnmin, 
								thn_mobil_max as thnmax,
								tenor_thn as tenor,
								min_dp as dp, 
								term1,
								term2
						FROM " . $table;
			} elseif ($pointer == 'biaya') {
				$table = self::$table3;
				$sql = "SELECT 	id_bibcaf as id,
								tenor_thn as tenor, 
								biaya_adm_polis as admpolis,
								biaya_adm as adm,
								hutang_min as hutangmin,
								hutang_max as hutangmax,
								fiducia,
								credit_protection as crdtpro,
								provisi_masuk_tdp,
								provisi_on_loan as provisi
						FROM " . $table;
			} elseif ($pointer == 'premi') {
				$table = self::$table1;
				$sql = "SELECT 	id_as as id,
								jenis_as as jenis,
								min_thn_mobil_fk as thnmin,
								max_thn_mobil_fk as thnmax,
								min_otr as minotr,
								max_otr as maxotr,
								thn1, 
								thn2, 
								thn3, 
								thn4,
								thn5,
								thn6
						FROM " . $table;
			}
		} else {
			$table = self::$table2;
			$sql = "SELECT 	id_bbcaf as id, 
							thn_mobil_min as thnmin, 
							thn_mobil_max as thnmax, 
							min_dp as dp, 
							thn1, 
							thn2, 
							thn3, 
							thn4
					FROM " . $table;
		}

		
		$q = mysqli_query($model->conn, $sql);
		$result = mysqli_fetch_all($q, MYSQLI_ASSOC);

		$model->close();

		return $result;
	}

	function updateSim($data) {
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
			$value = $data['data'];
			if ($value['pointer'] == 'bunga') {
				foreach ($value['data'] as $key => $roll) {
					// return $value;
					$sql = "UPDATE " . self::$table2 . " SET 
							thn_mobil_min = " . intval($roll['thnmin']) . ",
							thn_mobil_max = " . intval($roll['thnmax']) . ",
							min_dp = " . intval($roll['dp']) . ",
							thn1 = " . floatval($roll['thn1']) . ",
							thn2 = " . floatval($roll['thn2']) . ",
							thn3 = " . floatval($roll['thn3']) . ",
							thn4 = " . floatval($roll['thn4']) . "
							WHERE id_bbcaf = " . intval($roll['id']);
					$q = mysqli_query($model->conn, $sql);
					if ($q) {
						$status->data = true;
					} else {
						$status->data = mysqli_errno($q);
					}
				}
			} elseif ($value['pointer'] == 'fixcap') {
				foreach ($value['data'] as $key => $roll) {
					$sql = "UPDATE " . self::$table4 . " SET 
							thn_mobil_min = " . intval($roll['thnmin']) . ",
							thn_mobil_max = " . intval($roll['thnmax']) . ",
							tenor_thn = " . intval($roll['tenor']) . ",
							min_dp = " . intval($roll['dp']) . ",
							term1 = " . floatval($roll['term1']) . ",
							term2 = " . floatval($roll['term2']) . "
							WHERE id_fc = " . intval($roll['id']);
					$q = mysqli_query($model->conn, $sql);
					if ($q) {
						$status->data = true;
					} else {
						$status->data = mysqli_errno($q);
					}
				}
			} elseif ($value['pointer'] == 'premi') {
				foreach ($value['data'] as $key => $roll) {
					$sql = "UPDATE  " . self::$table1 . " SET 
							jenis_as = '" . $roll['jenis'] . "',
							min_thn_mobil_fk = " . $roll['thnmin'] . ",
							max_thn_mobil_fk = " . $roll['thnmax'] . ",
							min_otr = " . $roll['minotr'] . ",
							max_otr = " . $roll['maxotr'] . ",
							thn1 = " . $roll['thn1'] . ", 
							thn2 = " . $roll['thn2'] . ", 
							thn3 = " . $roll['thn3'] . ", 
							thn4 = " . $roll['thn4'] . ",
							thn5 = " . $roll['thn5'] . ",
							thn6 = " . $roll['thn6'] . "
							WHERE id_as = " . intval($roll['id']);
					$q = mysqli_query($model->conn, $sql);
					if ($q) {
						$status->data = true;
					} else {
						$status->data = mysqli_errno($q);
					}
				}
			} elseif ($value['pointer'] == 'biaya') {
				foreach ($value['data'] as $key => $roll) {
					$sql = "UPDATE " . self::$table3 . " SET
							tenor_thn = " . $roll['tenor'] . ", 
							biaya_adm_polis = " . $roll['admpolis'] . ",
							biaya_adm = " . $roll['adm'] . ",
							hutang_min = " . $roll['hutangmin'] . ",
							hutang_max = " . $roll['hutangmax'] . ",
							fiducia = " . $roll['fiducia'] . ",
							credit_protection = " . $roll['crdtpro'] . ",
							provisi_masuk_tdp = " . $roll['provisi_masuk_tdp'] . ",
							provisi_on_loan = " . $roll['provisi'] . "
							WHERE id_bibcaf = " . $roll['id'];
					$q = mysqli_query($model->conn, $sql);
					if ($q) {
						$status->data = true;
					} else {
						$status->data = mysqli_errno($q);
					}
				}
			}
		}

		$model->close();

		return $status;
	}
}	
?>