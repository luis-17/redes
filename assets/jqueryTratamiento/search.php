<?php
/**
*	@author 	Ing. Israel Barragan C.  Email: ibarragan at behstant dot com
*	@since 		07-Nov-2013
*	##########################################################################################
*	Comments:
*	This file is to show how to retrieve records from a database with PDO
*	The records are shown in a HTML table.
*
*	Requires:	
*	Connection.simple.php, get this file here: http://behstant.com/blog/?p=413
*   jQuery and Boostrap.
*
* 	LICENCE:
*	You can use this code to any of your projects as long as you mention where you
* 	downloaded it and the author which is me :) Happy Code.
*
* 	LICENCIA:
*	Puedes usar este código para tus proyectos, pero siempre tomando en cuenta que
* 	debes de poner de donde lo descargaste y el autor que soy yo :) Feliz Codificación.
*	##########################################################################################
*	@version
*	##########################################################################################
*	1.0	|	07-Nov-2013	|	Creation of new file to search a record.
*	##########################################################################################
*/
	require_once 'Connection.simple.php';
	$conn = dbConnect();
	$OK = true; // We use this to verify the status of the update.
	// If 'buscar' is in the array $_POST proceed to make the query.
	if (isset($_GET['dianostico_temp'])) {
		// Create the query	

		/*$data = $_GET['session_name()'];*/
		$dianostico_temp = $_GET['dianostico_temp'];
		$idsiniestro = $_GET['idsiniestro'];
		$idplandetalle = $_GET['idplandetalle'];
		
		//$sin_diagnosticoSec = $_GET['sin_diagnosticoSec'];
		//$sin_dosisSecundaria = $GET['sin_dosisSecundaria'];
		


		/*$idsiniestro = $_GET['idsiniestro'];*/
		$cadena_buscada = ":";
		//buscamos posicion de :
		$posicion_coincidencia = strpos($dianostico_temp, $cadena_buscada, 0);
		$resultado = substr($dianostico_temp, 4, ($posicion_coincidencia-4));

		//$sql = "SELECT * FROM medicamentos WHERE cod_enf2='$resultado'";
		$sql = "SELECT * FROM medicamento M inner join diagnostico_medicamento DM on DM.idmedicamento = M.idmedicamento inner join diagnostico D on D.iddiagnostico = DM.iddiagnostico WHERE codigo_cie='$resultado'";
		
		// we have to tell the PDO that we are going to send values to the query
		$stmt = $conn->prepare($sql);
		// Now we execute the query passing an array toe execute();
		$results = $stmt->execute(array($dianostico_temp));
		// Extract the values from $result
		$rows = $stmt->fetchAll();
		$error = $stmt->errorInfo();
		
		//echo $error[2];
	}
	// If there are no records.
	if(empty($rows)) {
		
		echo "<div class='alert alert-danger' role='alert'> <tr>";
			echo "<td colspan='4'>En éste plan, No existen medicamentos para el diagnóstico elegido.</td>";
			
		echo "</tr></div>";
				
	}
	else {
			 echo "<form method='post' action='https://www.red-salud.com/redes/index.php/guardar_medicamentos'>";
			 echo "<br><div >";
             echo "<table class='col-12 col-sm-12 col-md-12'>";
             $cont=0;
             $cont2=3;

		foreach ($rows as $row) {
			if($cont2==3){ echo "<tr>"; }
			echo '<td width="2%"><input onclick="valida_med();" type="checkbox" name="chk[]" value="'.$row['idmedicamento'].'"></td>';				
			echo '<td width="48%">'.$row['nombre_med'].' / '.$row['presentacion_med'].'</td>';
			$cont= $cont +1; 
			if($cont==2){ 
				$cont2=3; 
				$cont=0; 
			}else{ 
				$cont2 = 0; 
			} 
			if($cont2==3){ echo "</tr>"; }
							
		}		
		echo "</table>";
		echo "</div>";
		echo "</div>";
		echo '<br><input type="submit" id="btn_med" name="" value="Validar Medicamentos Cubiertos" class="btn btn-custom btn-celeste" disabled>';
		echo "<input type='hidden' name='idplandetalle' id='idplandetalle' value='".$idplandetalle."'>";
		echo "<input type='hidden' name='idsiniestro' id='idsiniestro' value='".$idsiniestro."'>";
		echo "<input type='hidden' name='dianostico_temp' id='dianostico_temp' value='$dianostico_temp'>";
		echo "<input type='hidden' name='tipo' id='tipo' value='1'>";
		echo "</form>";
		
		//echo "<input type='hidden' name='sin_dosisSecundaria' id='sin_dosisSecundaria' value='$sin_dosisSecundaria'>";
		
	}
	
?>