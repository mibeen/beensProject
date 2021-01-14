<?php
include_once(G5_PATH."/gseek/api.php");

	$Menu_Html_Tag ="";
	$Menu_Left_Tag = "";
	$Select_Menu_Text = "";
	$select_ch_on = "off";

	$response_json_obj = GetCategory();

	$itms = $response_json_obj[result];
	$itms_len = Count($itms);

	if($itms_len > 0)
	{
		//$Menu_Html_Tag .= "<div class='sub-slide sub-1div' style='display: none; visibility: visible;'><ul class='sub-1dul'>";

		for($i = 0; $i < $itms_len; $i++)
		{
			$itm = $itms[$i];

			if($_REQUEST['SC_CC_CODE'] == $itm['CC_CODE'])
			{
				$select_on = "on";
				$Select_Menu_Text = $itm['CC_NAME'];
			}
			else
			{
				$select_on = "off";
			}

			$Menu_Html_Tag .= "<li class='sub-1dli ". $select_on ."'>";
			$Menu_Html_Tag .= "<a href='".  G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=".  $itm['CC_CODE'] ."' class='sub-1da'>".  $itm['CC_NAME'] ."</a>";
			$Menu_Html_Tag .= "</li>";

			$Menu_Left_Tag .= "<div class='ca-sub1 ". $select_on ."'>";
			$Menu_Left_Tag .= "<a href='".  G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=".  $itm['CC_CODE'] ."' class='no-sub'>".  $itm['CC_NAME'] ."</a>";
			$Menu_Left_Tag .= "</div>";

			$Menu_Side_Tag .= "<li class='". $select_on ."'>";
			$Menu_Side_Tag .= "<a href='".  G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=".  $itm['CC_CODE'] ."'>".  $itm['CC_NAME'] ."</a>";
			$Menu_Side_Tag .= "</li>";
		}

		//$Menu_Html_Tag .= "</ul></div>";
	}

	if(!$_REQUEST['SC_CC_CODE'] && strpos($_SERVER['REQUEST_URI'], "/gseek/nx_learn_") !== false) $select_on = "on";
	else{$select_on = "off";}

	if($_REQUEST['SC_CC_CODE'] == "ch"){$select_ch_on = "on";}
	else {$select_ch_on = "off";}

	$Menu_Html_Tag = "<div class='sub-slide sub-1div' style='display: none; visibility: visible;'><ul class='sub-1dul'><li class='sub-1dli ".$select_on."'><a href='". G5_URL ."/gseek/nx_learn_list.php' class='sub-1da'>전체 보기</a></li><li class='sub-1dli ".$select_ch_on."'><a href='". G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=ch' class='sub-1da'>추천 학습</a></li>". $Menu_Html_Tag ."</ul></div>";
	
	$Menu_Left_Tag = "<div class='ca-sub1 ".$select_on."'><a href='". G5_URL ."/gseek/nx_learn_list.php' class='no-sub'>전체 보기</a></div><div class='ca-sub1 ".$select_ch_on."'><a href='". G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=ch' class='no-sub'>추천 학습</a></div>". $Menu_Left_Tag;

	$Menu_Side_Tag = "<li class='". $select_on ."'><a href='". G5_URL ."/gseek/nx_learn_list.php'>전체 보기</a></li><li class='". $select_ch_on ."'><a href='". G5_URL ."/gseek/nx_learn_list.php?SC_CC_CODE=ch'>추천 학습</a></li>". $Menu_Side_Tag;
?>