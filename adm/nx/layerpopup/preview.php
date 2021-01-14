<?php
include_once('./_common.php');

//print_r($_POST);

$nw_id      = clean_xss_tags(trim($_POST['nw_id']));
$nw_left    = clean_xss_tags(trim($_POST['nw_left']));
$nw_top     = clean_xss_tags(trim($_POST['nw_top']));
$nw_width   = clean_xss_tags(trim($_POST['nw_width']));
$nw_height  = clean_xss_tags(trim($_POST['nw_height']));
$nw_url     = clean_xss_tags(trim($_POST['nw_url']));
$nw_subject = clean_xss_tags(trim($_POST['nw_subject']));
$nw_content = clean_xss_tags(trim($_POST['nw_content']));
$nw_link    = clean_xss_tags(trim($_POST['nw_link']));
$nw_target  = clean_xss_tags(trim($_POST['nw_target']));
$nw_padding = clean_xss_tags(trim($_POST['nw_padding']));

$attachment = clean_xss_tags(trim($_POST['nw_attachment']));

$divStyle = "";

switch ($attachment) {
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

$divStyle = " style='width:{$nw_width}px; height:{$nw_height}px;background-image:url({$nw_url});background-size:{$attach};background-repeat:{$bgrepeat};background-position:center;resize:{$onresize}; padding:{$nw_padding}px;box-sizing:border-box; '";
?>

<style>
*{padding: 0; margin: 0 ;}
</style>

<div <?php echo $divStyle ?>>
	<?php echo $nw_content;?>
</div>
