<?php  
/**
	* 
	*/
	class Sim
	{

		function calcSim($data) {
			$model = new Model;
			$model->connect();

			echo json_encode($data);

			$model->close();	
		}
	}	
?>