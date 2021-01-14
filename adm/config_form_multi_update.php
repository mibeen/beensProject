<?php
include_once('./_common.php');


$valid_formats = array("jpg", "png", "gif", "zip", "bmp", "xml", "ico", "json");
$max_file_size = 1024*100; //100 kb
$path = G5_DATA_PATH.'/member/ico/'; // Upload directory
$count = 0;


if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){

	foreach ($_FILES['filesToUpload']['name'] as $f => $name) {     
	    if ($_FILES['filesToUpload']['error'][$f] == 4) {
	        continue; // Skip file if any error found
	    }	      

	    if ($_FILES['filesToUpload']['error'][$f] == 0) {	           
	        if ($_FILES['filesToUpload']['size'][$f] > $max_file_size) {
	            $message[] = "$name 파일크기가 너무 큽니다.";
	            continue; // Skip large files
	        }
			elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
				$message[] = "$name 지원하지 않는 포멧입니다.";
				die("size error");
				continue; // Skip invalid file formats
			}
	        else{ // No error found! Move uploaded files 
	        	echo "..............<br>";
				$re1 = @mkdir($path, G5_DIR_PERMISSION);
				$re2 = @chmod($path, G5_DIR_PERMISSION);

				$dest_path = $path.$name;

				if(move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$f], $dest_path))
				{
					chmod($dest_path, G5_FILE_PERMISSION);

					$savemsg .= $name."\\n";

		            $count++; // Number of successfully uploaded file
				}
				
	        }
	    }
	}
}

$msg = '저장하지 못 했습니다.' . $count;
if($count > 0)
{
 $msg = $savemsg.'저장했습니다.';
}
?>
<script>
	alert('<?php echo $msg; ?>');
	opener.document.location.href = opener.document.location.href;
	window.close();
</script>