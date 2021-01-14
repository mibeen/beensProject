<?php
$sub_menu = '100310';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

$nw_id = preg_replace('/[^0-9]/', '', $nw_id);

$html_title = "팝업레이어";
if ($w == "u")
{
    $html_title .= " 수정";
    $sql = " select * from {$g5['new_win_table']} where nw_id = '$nw_id' ";
    $nw = sql_fetch($sql);
    if (!$nw['nw_id']) alert("등록된 자료가 없습니다.");

}
else
{
    $html_title .= " 입력";
    $nw['nw_device'] = 'both';
    $nw['nw_disable_hours'] = 24;
    $nw['nw_left']   = 10;
    $nw['nw_top']    = 10;
    $nw['nw_width']  = 450;
    $nw['nw_height'] = 500;
    $nw['nw_content_html'] = 2;
}

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
    input[type=radio],
    input[type=checkbox]{
        margin: 0 10px;
    }
    label{margin-bottom: 0;}
    #template_case{ margin-top: 10px; padding: 10px 10px 5px; background: #FFF; border: 1px solid #DDD;}
    #template_case .img-box {overflow: hidden; max-height: 200px;}

    .temp-child{cursor:pointer; margin-bottom: 10px; padding: 5px 0; border: 1px solid transparent; -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;    box-sizing: border-box; }
    .temp-child:hover{border-color: #DDD;}
</style>

<form name="frmnewwin" action="./newwinformupdate.php" onsubmit="return frmnewwin_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="nw_id" value="<?php echo $nw_id; ?>">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc">
    <p>초기화면 접속 시 자동으로 뜰 팝업레이어를 설정합니다.</p>
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
        <th scope="row"><label for="nw_height">팝업레이어 스타일<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="radio" name="nw_view_status" id="status_layer" value="layer" <?php echo ($nw['nw_view_status'] == 'layer') || !isset($nw['nw_view_status']) ? 'checked' : '' ?>><label for="status_layer">레이어팝업</label>
            <input type="radio" name="nw_view_status" id="status_window" value="window" <?php echo $nw['nw_view_status'] == 'window' ? 'checked' : '' ?>><label for="status_window">새창팝업</label>
            <input type="radio" name="nw_view_status" id="status_top" value="top" <?php echo $nw['nw_view_status'] == 'top' ? 'checked' : '' ?>><label for="status_top">상단팝업 (이미지 전용)</label>
        </td>
    </tr>
    <tr>
        <th scope="row">템플릿</th>
        <td>
            <div id="template_case">
                <div class="clearfix"></div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_device">접속기기</label></th>
        <td>
            <?php echo help("팝업레이어가 표시될 접속기기를 설정합니다."); ?>
            <select name="nw_device" id="nw_device">
                <option value="both"<?php echo get_selected($nw['nw_device'], 'both', true); ?>>PC와 모바일</option>
                <option value="pc"<?php echo get_selected($nw['nw_device'], 'pc'); ?>>PC</option>
                <option value="mobile"<?php echo get_selected($nw['nw_device'], 'mobile'); ?>>모바일</option>
            </select>
        </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_disable_hours">시간<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <?php echo help("고객이 다시 보지 않음을 선택할 시 몇 시간동안 팝업레이어를 보여주지 않을지 설정합니다."); ?>
            <input type="text" name="nw_disable_hours" value="<?php echo $nw['nw_disable_hours']; ?>" id="nw_disable_hours" required class="frm_input required" size="5"> 시간
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_begin_time">시작일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_begin_time" value="<?php echo $nw['nw_begin_time']; ?>" id="nw_begin_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="nw_begin_chk" onclick="if (this.checked == true) this.form.nw_begin_time.value=this.form.nw_begin_chk.value; else this.form.nw_begin_time.value = this.form.nw_begin_time.defaultValue;">
            <label for="nw_begin_chk">시작일시를 오늘로</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_end_time">종료일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_end_time" value="<?php echo $nw['nw_end_time']; ?>" id="nw_end_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME+(60*60*24*7)); ?>" id="nw_end_chk" onclick="if (this.checked == true) this.form.nw_end_time.value=this.form.nw_end_chk.value; else this.form.nw_end_time.value = this.form.nw_end_time.defaultValue;">
            <label for="nw_end_chk">종료일시를 오늘로부터 7일 후로</label>
        </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_left">팝업레이어 좌측 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
           <input type="text" name="nw_left" value="<?php echo $nw['nw_left']; ?>" id="nw_left" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_top">팝업레이어 상단 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_top" value="<?php echo $nw['nw_top']; ?>" id="nw_top" required class="frm_input required"  size="5"> px
        </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_width">팝업레이어 넓이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_width" value="<?php echo $nw['nw_width'] ?>" id="nw_width" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_height">팝업레이어 높이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_height" value="<?php echo $nw['nw_height'] ?>" id="nw_height" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr id="layer_bg">
        <th scope="row">
            <label for="nw_height">팝업레이어 배경</label>
        </th>
        <td>
            <input type="hidden" name="nw_url" value="<?php echo $nw['nw_background'] ?>" id="nw_url" required class="frm_input required col-md-12" size="5">
            <div class='img-box'>
            <?php
            $bg_display = "block";
            if(strlen($nw['nw_background']) > 2){
                echo "<img src='" . $nw['nw_background'] . "' alt='' style='max-width:90%; max-height:55px;'><button type='button' class='btn btn-default' id='nw_background_detach'>삭제</button>";
                $bg_display = "none";
            }
            ?>
            </div>
            <div class="upload-box" style="display: <?php echo $bg_display ?>; padding: 15px 0;"><input type="file" name="upload[]" id="upload" style="display: inline-block;"><button type="button" id="img_upload" class="btn btn-default">업로드</button></div>
        </td>
    </tr>
    <tr class="noTop">
        <th>배경이미지 형태</th>
        <td>
            <input type="radio" name="nw_attachment" id="nw_ori" value="A" <?php echo ($nw['nw_attachment'] == 'A' OR strlen($nw['nw_attachment']) < 1) ? 'checked':''; ?>><label for="nw_ori">일반</label>
            <input type="radio" name="nw_attachment" id="nw_full" value="B" <?php echo $nw['nw_attachment'] == 'B' ? 'checked':''; ?>><label for="nw_full">꽉차게</label>
            <input type="radio" name="nw_attachment" id="nw_repeat" value="C" <?php echo $nw['nw_attachment'] == 'C' ? 'checked':''; ?>><label for="nw_repeat">반복</label>
        </td>
    </tr>
    <tr>
        <th>링크</th>
        <td>
            <input type="text" class="frm_input required col-md-12" name="nx_href" id="nx_href" value="<?php echo $nw['nx_href'] ?>">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_subject">팝업 제목<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_subject" value="<?php echo stripslashes($nw['nw_subject']) ?>" id="nw_subject" required class="frm_input required col-md-12" size="80">
        </td>
    </tr>
    <tr class="noTop">
      <th socpe="row">여백</th>
      <td>
        <input type="text" name="nw_padding" id="nw_padding" class="frm_input" value="<?php echo $nw['nw_padding'] ?>">
      </td>
    </tr>
    <tr class="noTop">
        <th scope="row"><label for="nw_content">내용</label></th>
        <td><?php echo editor_html('nw_content', get_text($nw['nw_content'], 0)); ?></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <button type="button" id="btn-preview" class="btn btn-primary">미리보기</button>
    <input type="submit" value="확인" class="btn btn-danger" accesskey="s">
    <a href="./newwinlist.php" class="btn btn-default">목록</a>
</div>
</form>

<script>

function frmnewwin_check(f)
{
    var f = document.frmnewwin;

    f.action = './newwinformupdate.php';
    f.target = '_self';

    errmsg = "";
    errfld = "";

    <?php echo get_editor_js('nw_content'); ?>

    check_field(f.nw_subject, "제목을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}


</script>
<script>
$(function(){

<?php
if(strlen($nw['nw_background']) > 2){
?>

$(window).load(function(){
    var backgroundsrc = "<?php echo $nw['nw_background'] ?>";
    var attachment = "<?php echo $nw['nw_attachment'] ?>";

    $('#nw_content').next('iframe').css('background-image','url(<?php echo $nw['nw_background'] ?>)');

    switch(attachment){

        case 'A' :
            $('#nw_content').next('iframe').css('background-repeat','no-repeat')
                        .css('background-size','auto')
                        .css('resize', 'none');
        break;
        case 'C' :
            $('#nw_content').next('iframe').css('background-repeat','repeat')
                        .css('background-size','auto')
                        .css('resize','both');
        break;
        case 'B' :
            $('#nw_content').next('iframe').css('background-repeat','no-repeat')
                        .css('background-size','cover')
                        .css('resize','both');
        break;
    }
})

<?php
}
?>

    // 템플렛을 가져온 후, 레이어를 오픈한다.
    getTemplate();
    setPadding(<?php echo $nw['nw_padding'] ?>);

    //패딩 이벤트
    $(document).on('change', '#nw_padding', function(e){
      setPadding($(this).val());
    })

    //템플렛의 정보를 적용한다.
    $(document).on('click', '#template_case button', function(e){

        if(!confirm('적용하시겠습니까?')){
            return false;
        }

        var child       = $(this).closest('.temp-child');
        var id          = child.attr('id').replace('temp_');
        var temp_width  = child.attr('twidth');
        var temp_height = child.attr('theight');
        var attachment  = child.attr('attachment');
        var nw_padding  = child.attr('padding');
        var src         = child.find('img').attr('src');
        var template    = child.find('.template').html();
        var title       = child.find('.title').text();

        child.find('input[name=template]').prop('checked',true);

        $('#nw_width').val(temp_width);
        $('#nw_height').val(temp_height);
        $('#nw_url').val(src);
        $('#nw_attachment').val(attachment);
        $('#nw_padding').val(nw_padding);

        $('#nw_content').text(template);
        oEditors.getById['nw_content'].exec('LOAD_CONTENTS_FIELD', [template]);

        $('#nw_content').next('iframe').css({
            'background-image' : 'url(' + src +')',
            'background-position' : 'center'
        })

        setImage(src);
        setPadding(nw_padding);

        switch(attachment){

            case 'ori' :
                $('#nw_content').next('iframe').css('background-repeat','no-repeat')
                            .css('background-size','auto')
                            .css('resize', 'none');
            break;
            case 'repeat' :
                $('#nw_content').next('iframe').css('background-repeat','repeat')
                            .css('background-size','auto')
                            .css('resize','both');
            break;
            case 'full' :
                $('#nw_content').next('iframe').css('background-repeat','no-repeat')
                            .css('background-size','cover')
                            .css('resize','both');
            break;
        }

    })

    $(document).on('click', 'button#btn-preview', function(e){
        preview();
    })

    $(document).on('click', 'button#nw_background_detach', function(e){
        $('#nw_url').val('').siblings('.upload-box').show().siblings('.img-box').hide();
        //$(this).detach();
    })


    //function nx_img_upload(idname, callback);
    $('#upload').on('change', function(e) {
        //이미지가 업로드되면
        nx_img_upload("upload", setImage);
        //setImage(dir);
    });

    function setImage(dir){
        if(dir == undefined){
            console.log("setImage의 인수가 정상적으로 넘어 오지 않았습니다.");
            return false;
        }
        $('input[name=nw_url]')
            .val(dir)
            .next('.img-box').show().html("<img src='" + dir + "' alt='' style='max-width:90%; max-height:55px;'><button type='button' class='btn btn-default' id='nw_background_detach'>삭제</button>");
            //.find('img').attr('src',dir);
        $('.upload-box').hide().find('input').val('');
    }

    $(document).on('click', 'input[name=nw_view_status]', function(){
        var stat = $(this).attr('value');
        if(stat == 'top'){
            $('.noTop').hide();
        }else{
            $('.noTop').show();
        }
    })

    <?php if($nw['nw_view_status'] == 'top'){
        echo "$('.noTop').hide()";
    }
    ?>

})

/*
 * function getTemplate()
 */
function getTemplate(){
    console.log('getTemplate start');
    $.ajax({
        url : '/adm/nx/layerpopup/util.php',
        type : 'POST',
        data : {'mode':'template'},
        success : function(data){
            //console.log(data);
            makeTemplate(data);
        }
    })
}

/*
 * function makeTemplate(html)
 */
function makeTemplate(obj){
    var temp_div = $('#template_case');
    //var temp_leng = obj.length;
    temp_div.html("<div class='clearfix'></div>");
    temp_div.prepend(obj);
}

 function preview(){
    var p_width  = document.getElementById('nw_width').value;
    var p_height = document.getElementById('nw_height').value;

    //에디터의 값을 textarea로 옮겨 온다.
    oEditors.getById["nw_content"].exec("UPDATE_CONTENTS_FIELD", []);

    if(p_width == undefined || p_height == undefined){
        alert('모든 값을 입력해주세요.');
        return false;
    }

    var newWin   = window.open('/adm/nx/layerpopup/preview.php','previewer','width=' + p_width + ', height=' + p_height);
    var frm      = document.frmnewwin;

    frm.action = '/adm/nx/layerpopup/preview.php';
    frm.target = 'previewer';
    frm.submit();

 }


 function setPadding(num){
   $('#nw_content + iframe').contents().find('iframe').contents().find('body').css('padding',num);
 }




 // function submit(){

 //    //에디터의 값을 textarea로 옮겨 온다.
 //    oEditors.getById["nw_content"].exec("UPDATE_CONTENTS_FIELD", []);

 //    var frm      = document.frmnewwin;

 //    //frm.action   = './newwinformupdate.php';
 //    //frm.target   = '_self';
 //    //frm.submit();
 //    frmnewwin_check(frm);

 // }


</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
