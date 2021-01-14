<?php
include_once('./_common.php');


$skinlist = array();
$headlist = array();

$skinlist = get_skin_dir('page', G5_SKIN_PATH);
$headlist = get_skin_dir('header', G5_SKIN_PATH);

include_once(G5_PATH.'/head.sub.php');

?>

<div id="wrapper">
    <div id="container">

		<div class="tbl_head01 tbl_wrap">
			<table>
				<thead>
				<tr>
					<td><h1><b>파비콘 설정</h1></td>
				</tr>
				<tr>
					<td>
						<form id="fileform" method="post" action="config_form_multi_update.php" enctype="multipart/form-data">
						  <input name="filesToUpload[]" id="filesToUpload" type="file" multiple="" onchange="makeFileList();" />
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<ul id="fileList">
						</ul>
					</td>
				</tr>
				<tr>
					<td>
						<a href="javascript:fsubmit();" class="btn_frmline" style="text-decoration:none; width:300px; background: #c44c40; text-align:center;">업로드</a>
					</td>
				</tr>
				<tr>
					<td>
					</td>
				</tr>
				</thead>
			</table>
		</div>



	</div>
</div>
<div style="height:100px;"></div>
<script>
/*
	var win_w = screen.width;
	var win_h = screen.height - 40;
	window.moveTo(0, 0);
	window.resizeTo(win_w, win_h);
*/

	function makeFileList() {
		var input = document.getElementById("filesToUpload");
		var ul = document.getElementById("fileList");
		while (ul.hasChildNodes()) {
			ul.removeChild(ul.firstChild);
		}
		for (var i = 0; i < input.files.length; i++) {
			var li = document.createElement("li");
			li.innerHTML = input.files[i].name;
			ul.appendChild(li);
		}
		if(!ul.hasChildNodes()) {
			var li = document.createElement("li");
			li.innerHTML = 'No Files Selected';
			ul.appendChild(li);
		}
	}

	function fsubmit()
	{
		fileform.submit();
	}
</script>

<?php include_once(G5_PATH.'/tail.sub.php'); ?>