<?php
include_once('./_common.php');

$path = G5_DATA_PATH.'/member/ico/';
$files1 = scandir($path);
for($i=0; $i < count($files1); $i++)
{
	$fileitem = $path.$files1[$i];
	unlink($fileitem);
	if(!is_file($fileitem))
		$count++;
}

$msg = '삭제하지 못 했습니다.';
if($count > 0)
{
 $msg = '삭제했습니다.';
}
?>
<script>
	alert('<?php echo $msg; ?>');
	opener.document.location.href = opener.document.location.href;
	window.close();
</script>