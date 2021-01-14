<?php
$sub_menu = '100310';
include_once('./_common.php');
include_once(G5_EDITOR_LIB); /* 라이브러리에 check 함수 등이 들어가 있다. 제거 시 PHP에러 발생하여 tail이 나오지 않는다. */

auth_check($auth[$sub_menu], "w");

$id = $_GET['id'];

$html_title = "팝업레이어";
if ($w == "u")
{
    $html_title .= " 수정";
    #$sql = " select * from {$g5['new_temp_table']} where id = '$id' ";
    #$nw = sql_fetch($sql);
    #if (!$nw['id']) alert("등록된 자료가 없습니다.");
}
else
{
    $html_title .= " 입력";
}

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');

#$id         = "";
$title      = "";
$url        = "";
$attach     = "";
$template   = "";
$created_by = "";
$created_at = "";
$deleted    = "";
$deleted_by = "";
$deleted_at = "";
$padding    = 15;

if(isset($_GET['id'])){

    $query = "SELECT * FROM g5_new_temp WHERE id = '{$id}' ORDER BY id DESC LIMIT 0, 1";

    $rows = sql_fetch($query);
    $cnt  = sql_num_rows(sql_query($query));

    if($cnt < 1){
        alert('등록된 자료가 없습니다.');
        die();
    }

    $id         = $rows['id'];
    $title      = $rows['title'];
    $url        = $rows['url'];
    $attach     = $rows['attachment'];
    $template   = $rows['template'];
    $created_by = $rows['created_by'];
    $created_at = $rows['created_at'];
    $width      = $rows['width'];
    $height     = $rows['height'];
    $padding    = isset($rows['padding']) ? $rows['padding'] : $padding;

    switch ($attach) {
        case 'A':
            $attach   = 'initial';
            $bgrepeat = 'no-repeat';
            $onresize = 'none';
            break;
        case 'B':
            $attach   = 'cover';
            $bgrepeat = 'no-repeat';
            $onresize = 'both';
            break;
        case 'C':
            $attach   = 'initial';
            $bgrepeat = 'repeat';
            $onresize = 'both';
            break;

        default:
            $attach = 'initial';
            break;
    }

    $divStyle = " style='width:{$width}px; height:{$height}px;background-image:url({$url});background-size:{$attach};background-repeat:{$bgrepeat};resize:{$onresize} '";


}

?>

<style>
    label{margin: 0 10px;}
    .editor_wrap{min-height: 500px; display: table; width:100%;}
    #editor1{border: 1px solid #DDD; display: table-cell; vertical-align: top; border-radius: 3px; text-align: auto; text-align: initial; background-position: center;}
    #editor1 *{font-size: auto; font-size: initial; color: initial; line-height: initial; text-align: auto; text-align: initial;}
</style>
<!--<script src="<?php echo G5_URL ?>/plugin/editor/ckeditor/ckeditor.js"></script>-->
<script src="<?php echo G5_URL ?>/plugin/editor/ckeditor4/ckeditor.js"></script>
<script>

window.onload = function(){
    /*
    InlineEditor
        .create( document.querySelector('#editor1'),{
            toolbar : ['headings', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blackQuote']
        })
        .catch(
            error => {
                console.error(error);
        })
    */


}
</script>


<form name="frmnewwin" id="frmnewwin" action="./newtempformupdate.php" onsubmit="return frmnewwin_check(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc">
    <p>팝업레이어 템플렛을 설정합니다.</p>
</div>

<div class=" tbl_wrap">
    <table class="table table-responsive table-striped">
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="nw_disable_hours">템플렛 제목<strong class="sound_only"> </strong></label></th>
        <td>
            <input type="text" name="title" value="<?php echo $rows['title']; ?>" id="title" required class="frm_input required col-md-12" required >
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="nw_disable_hours">배경이미지<strong class="sound_only"> </strong></label></th>
        <td>
            <input type="hidden" name="src" value="<?php echo $rows['url']; ?>" id="src" required class="frm_file required" >
            <div class="imgbox"><?php
                if(strlen($url) > 1){
                    echo "<img src='".$url."' alt='' width='150'>";
                }
            ?></div>
            <div>
                <input type="file" id="upload" name="upload[]" class="frm_file"><button type="button" id="img_upload" class="btn btn-default">UPLOAD</button>
            </div>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="nw_disable_hours">배경방식<strong class="sound_only"> </strong></label></th>
        <td>
            <input type="radio" name="attach" value="ori" <?php echo get_checked($rows['attachment'], 'A', true) ?> id="atta_ori"><label for="atta_ori">이미지사이즈</label>
            <input type="radio" name="attach" value="full" <?php echo get_checked($rows['attachment'], 'B') ?> id="atta_full"><label for="atta_full">꽉 차게</label>
            <input type="radio" name="attach" value="repeat" <?php echo get_checked($rows['attachment'], 'C') ?> id="atta_repeat"><label for="atta_repeat">반복</label>
        </td>
    </tr>
    <tr>
        <th scope="row">사이즈</th>
        <td>
            <label for="width">너비</label><input class="frm_input" type="text" name="width" id="width" value="<?php echo $width ?>">
            <label for="height">높이</label><input class="frm_input" type="text" name="height" id="height" value="<?php echo $height ?>">
        </td>
    </tr>
    <tr>
      <th socpe="row">여백</th>
      <td>
        <label for="padding">내부 여백</label><input type="text" name="padding" id="padding" class="frm_input" value="<?php echo $padding ?>">
      </td>
    </tr>
    <tr style="display: none;">
      <th socpe="row">세로 정렬</th>
      <td>
        <label for="vtop">상단 정렬</label>
        <input type="radio" name="valign" id="vtop" value="top" <?php echo ($valign == 'top' || strlen($valign) < 1) ? 'checked' : '' ?>>
        <label for="vmiddle">중앙 정렬</label>
        <input type="radio" name="valign" id="vmiddle" value="middle" <?php echo $valign == 'middle' ? 'checked' : '' ?>>
        <label for="vbottom">하단 정렬</label>
        <input type="radio" name="valign" id="vbottom" value="bottom" <?php echo $valign == 'bottom' ? 'checked' : '' ?>>
      </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_content">내용</label></th>
        <?php /* <td><?php echo editor_html('nw_content', get_text($rows['nw_content'], 0)); ?></td> */ ?>
        <td><div class="editor_wrap"><div id="editor1" contenteditable="true" <?php echo $divStyle ?> ><?php echo $template ?></div></div></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="hidden">
    <textarea name="template" id="template" cols="30" rows="10"><?php echo $template ?></textarea>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./newtemplist.php">목록</a>
</div>
</form>
<script>


CKEDITOR.disableAutoInline = true;
CKEDITOR.inline( 'editor1' );

var $imgWidth, $imgHeight;

$(function(){
    $('#img_upload').on('click', function() {
        //버튼 누르면

        if($('#upload').val().length < 1){
            //이미지 없을 때
            alert('이미지 파일을 업로드해주세요.');
            return false;
        }

        var file_data = $('#upload').prop('files')[0];
        //console.log(file_data);

        //폼 데이터 설정
        var form_data = new FormData();
        form_data.append('file', file_data);


        $.ajax({
            //비동기로 파일 업로드
            url: '/plugin/editor/ckeditor/upload.php', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response){
                console.log(php_script_response);

                if(php_script_response.length < 1){
                    alert('정상적으로 업로드해 주세요.');
                    return false;
                }
                //alert(php_script_response); // display response from the PHP script, if any
                setImage(php_script_response);
            }
         });
    });

})

var editor_1 = document.getElementById('editor1');


document.getElementById('width').onkeyup = function(){
    editor_1.style.width =  document.getElementById('width').value + 'px';
}
document.getElementById('height').onkeyup = function(){
    editor_1.style.height =  document.getElementById('height').value + 'px';
}
/*
document.getElementById('height').onkeyup = function(){
    var user_height = this.value;
    //editor_1.style.height = user_height + 'px';
}
*/
function setImage(dir){

    $('input[name=src]')
        .val(dir)
        .next('.imgbox').html('<img src=\'' + dir + '\' id=\'setImg\' >');

    $('#setImg').load(function(){

        $imgWidth = parseInt($('#setImg').outerWidth());
        $imgHeight = parseInt($('#setImg').outerHeight());

        $('#editor1').width( $imgWidth ).height( $imgHeight )
            .css('background-image','url(' + dir + ')')
            .css('min-height','auto')
            .css('min-width','auto')
            .closest('form').find('#atta_ori').prop('checked',true);


    })
}

$(document).on('change','input[name=attach]', function(e){
    $var = $('input[name=attach]:checked').val();
    //console.log($var);
    switch($var){
        case 'ori' :
            $('#editor1').css('background-repeat','no-repeat')
                        .css('background-size','auto')
                        .css('resize', 'none')
                        .width( $imgWidth ).height( $imgHeight );
        break;
        case 'repeat' :
            $('#editor1').css('background-repeat','repeat')
                        .css('background-size','auto')
                        .css('resize','both');
        break;
        case 'full' :
            $('#editor1').css('background-repeat','no-repeat')
                        .css('background-size','cover')
                        .css('resize','both');
        break;
    }
})

$(window).load(function(){
  padding_change('#editor1', '#padding');
})

$(document).on('change', '#padding', function(e){
  padding_change('#editor1', '#padding');
})
$(document).on('change', 'input[name=valign]', function(){
  verticalAlign('valign');
})

function padding_change($target, $getPadding){
  var target = $($target);
  var getPadding = $($getPadding);

  if(target == 'undefined'){
    console.log('padding_change 함수에는 타겟이 있어야 합니다.');
    return fasle;
  }
  if(getPadding == 'undefined'){
    console.log('padding_change 함수에는 패딩값을 불러올 대상이 있어야 합니다.');
    return fasle;
  }

  var $ele_pad = parseInt(getPadding.val());
  target.css('padding', $ele_pad);
}


function verticalAlign(name){
  var radio = $('input[name=' + name + ']:checked').val();
  $('#editor1').css('vertical-align', radio)
}

</script>
<script>
function frmnewwin_check(f)
{
    errmsg = "";
    errfld = "";

    var editor = document.getElementById('editor1');
    //alert(editor);
    var template = editor.innerHTML;
    //alert(template);
    document.getElementById('template').innerHTML = template;

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
