<?php
include_once('./_common.php');

$mode = $_POST['mode'];
switch ($mode) {
	case 'template':
	 	# code...

		$sql = "SELECT * FROM g5_new_temp WHERE deleted = 'N'";
		$result = sql_query($sql, true);

		for($i=0; $row = sql_fetch_array($result); $i++){

			$attachment == "none";

			switch ($row['attachment']) {
				case 'A':
					$attachment = 'ori';
					break;
				case 'B':
					$attachment = 'full';
					break;
				case 'C':
					$attachment = 'repeat';
					break;

				default:
					# code...
					break;
			}


			echo "
				<div id='temp_".$row['id']."' attachment='".$attachment."' twidth='".$row['width']."' theight='".$row['height']."' padding='".$row['padding']."' class='temp-child text-center col-md-2'>
					<input type='radio' name='template' value='".$row['id']."' class='hidden'>
					<div class='img-box'><img src='".$row['url']."'></div>
					<div class='title'>".$row['title']."</div>
					<div class='template' style='display:none'>".$row['template']."</div>
					<div class='btn-box'><button type='button' class='btn btn-default'>사용</button></div>
				</div>
			";
		}

		die();
		break;

	default:
		echo "error(nodata)";
		break;
}

?>
