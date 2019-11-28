<?php
	$q=$_GET['q'];
	
	//$mysqli=mysqli_connect('localhost','root','','doctorweb') or die("Database Error");
	$mysqli=mysqli_connect('rdsinstancemysql.czvvckkesgis.us-east-2.rds.amazonaws.com:3306','rsaws2019','hcarsAcces2019$','new_redes_peru') or die("Database Error");
	//$my_data=mysql_real_escape_string($q);
	$sql="SELECT descripcion_cie, codigo_cie FROM diagnostico WHERE descripcion_cie LIKE '%$q%' ORDER BY descripcion_cie";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			//echo "CIE ".$row['cod_enf2']." : ".$row['nom_enf2']."\n";
			echo "CIE ".$row['codigo_cie']." : ".utf8_encode($row['descripcion_cie'])."\n";
		}
	}
?>