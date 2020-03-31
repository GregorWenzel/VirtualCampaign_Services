<?php
	echo "<!DOCTYPE html><html lang='de'><head><meta charset='utf-8'/></head><body><table border='1'><thead><tr><td colspan='4'></td><td colspan='2'>Render time [s]</td><td colspan='2'>Render time [s] / frame*</td><td colspan='2'>File size [MB]</td><td colspan='2'>File size [MB] / frame*</td><td colspan='2'>Complexity Data</td></tr><tr><td>Product ID</td><td>Name</td><td>frames</td><td># rendered</td><td>mean</td><td>std</td><td>mean</td><td>std</td><td>mean</td><td>std</td><td>mean</td><td>std</td><td>Proc Power</td><td>Complexity**</td><td>Total Complexity</td></tr></thead><tbody>";//session_start();

	//header('Access-Control-Allow-Origin: *');

	require 'includes/config.php';
	ob_start();

	require 'classes/dbconn.class.php';
	$dbconn = new dbconn($db_arr, 'utf8');
	$mysqli = $dbconn->get_dbconn();

	$byte_in_a_megabyte = 1024*1024;

	$sql = "SELECT MIN(std_complexity) AS min_complexity FROM render_time";
	$res = $mysqli->query($sql);
	$ds = mysqli_fetch_array($res);
	$min_complexity = $ds['min_complexity'];

	$sql = "SELECT render_time.product_id, COUNT(*) AS cnt, AVG(seconds_rendered) as average, STD(seconds_rendered) as std_dev, AVG(filesize) AS avg_size, STD(filesize) AS std_size, AVG(processor_power) AS avg_proc, AVG(std_complexity) AS avg_complex, product.in_frame, product.out_frame, product.name FROM render_time, product WHERE product.product_id = render_time.product_id GROUP BY render_time.product_id";

	$res = $mysqli->query($sql);
	$outputArr = [];
	$min_complexity = 99999999;
	$complexityArr = [];
	$frameArr = [];

	while ($ds = mysqli_fetch_array($res)) 
	{
		$str = "";
		$frame_count = $ds['out_frame'] - $ds['in_frame'] + 1;
		$str .= "<tr><td>".$ds['product_id']."</td>";
		$str .= "<td>".$ds['name']."</td>";
		$str .= "<td>".$frame_count."</td>";
		$str .= "<td>".$ds['cnt']."</td>";
		$str .= "<td>".round($ds['average'],2)."</td>";
		$str .= "<td>".round($ds['std_dev'],3)."</td>";		
		$str .= "<td>".round(100*$ds['average'] / $frame_count,2)."</td>";
		$str .= "<td>".round(100*$ds['std_dev'] / $frame_count,3)."</td>";
		$str .= "<td>".round($ds['avg_size'] / $byte_in_a_megabyte ,2)."</td>";
		$str .= "<td>".round($ds['std_size'] / $byte_in_a_megabyte, 3)."</td>";		
		$str .= "<td>".round((100/$byte_in_a_megabyte)*$ds['avg_size'] / $frame_count,2)."</td>";
		$str .= "<td>".round((100/$byte_in_a_megabyte)*$ds['std_size'] / $frame_count,3)."</td>";
		$str .= "<td>".round($ds['avg_proc'],2)."</td>";
		
		array_push($outputArr, $str);
		array_push($complexityArr, $ds['avg_complex']);
		array_push($frameArr, $frame_count);
	}

	$min_complexity = min($complexityArr);
	for ($i=0; $i<count($outputArr); $i++)
	{
		echo $outputArr[$i]."<td>".round($complexityArr[$i] / $min_complexity,2)."</td>";
		echo "<td>".round($frameArr[$i]*$complexityArr[$i] / $min_complexity,2)."</td></tr>";
	}

	echo "<tr><td colspan='14'>*per 100 frames    **relative to product with lowest complexity (=1)</td></tr>";
	echo "</tbody></table></body></html>";
?>