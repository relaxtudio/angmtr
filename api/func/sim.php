<?php  
/**
	* 
	*/
	class Sim
	{
		public static $table1 = "premi_as";
		public static $table2 = "bunga_bcaf";
		public static $table3 = "biaya_bcaf";

		function calcSim($data) {

			$return = array();
			$return['mobil'] = new stdClass();
			$return['asuransi'] = new stdClass();
			$return['bunga'] = new stdClass();
			$return['prepayment'] = new stdClass();

			$dp = ($data['c_harga'] * $data['c_dp']) / 100;
			$sisa = $data['c_harga'] - $dp;
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

			$percBu = $this->calcBunga($data);
			$return['bunga']->tenor = $data['bunga_thn'];
			$return['bunga']->bunga = $percBu[0];
			$return['bunga']->total = ($sisaas * $percBu[0] * $data['bunga_thn']) / 100;
			$return['bunga']->masaangsr = $masaangsr;
			$return['bunga']->angsuran = ($sisaas + $return['bunga']->total) / $masaangsr;

			$preArray = $this->calcPrepayment($data);
			$return['prepayment']->dp = $dp;
			$return['prepayment']->angsuran = $return['bunga']->angsuran;
			$return['prepayment']->fiducia = $preArray[0] + $preArray[1] + $preArray[2];
			$return['prepayment']->aspolis = ($data['c_harga'] * $percAs[0]) / 100;
			$return['prepayment']->crdtpro = ($sisaas * $preArray[3]) / 100;

			return json_encode($return);
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
	}	
?>