<?php
    //$sub_menu = "100000";
    include_once('./_common.php');


    # 아미나빌더 설치체크
    if(!isset($config['as_thema'])) { 
    	goto_url(G5_ADMIN_URL.'/apms_admin/apms.admin.php');
    }


    # 공통 변수 
    $is_index    = true;
    $g5['title'] = '대시보드';
    $get_rows    = 10; //가져올 열의 수
    $colspan     = 12; //정보가 없을 경우, 노출시킬 td의 colspan


    include_once './admin.head.php';


    $sql_common = " FROM {$g5['member_table']} ";
    $sql_search = " WHERE (1) ";

    if ($is_admin != 'super')
        $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

    if (!$sst) {
        $sst = "mb_datetime";
        $sod = "DESC";
    }

    $sql_order = " ORDER by {$sst} {$sod} ";

    $sql = " SELECT count(*) AS cnt {$sql_common} {$sql_search} {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];


    # 신규 가입 회원
    $sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} LIMIT {$get_rows} ";
    $result = sql_query($sql);

    # 사업관리
    # 권한을 체크한다. 사업관리는 990100
    $event_list = "<tr><td colspan=\"4\" align=\"center\">권한이 없습니다.</td></tr>";
    if( $auth['990100'] || ($is_admin == 'super') ){


        $sql = " SELECT 
                    a.EM_IDX, a.EJ_IDX, a.EJ_NAME, a.EJ_STATUS, a.EJ_WDATE, b.EM_TITLE
                 FROM 
                    NX_EVENT_JOIN as a
                    INNER JOIN NX_EVENT_MASTER as b 
                        ON a.EM_IDX = b.EM_IDX
                 WHERE 
                    a.EJ_DDATE is null
                 ORDER BY 
                    a.EJ_IDX DESC
                 LIMIT
                    0, {$get_rows}
                ";
        $row = sql_query($sql);

        if(sql_num_rows($row) <= 0){
            $event_list = "<tr><td colspan=\"4\">등록된 행사가 없습니다.</td></tr>";
        }
        else{
            $event_list = "";
            while($rs1 = sql_fetch_array($row)){

                $rs1['link'] = "/adm/evt/evt.join.list.php?EM_IDX=".$rs1['EM_IDX'];
                $rs1['EJ_link'] = "/adm/evt/evt.join.read.php?EM_IDX={$rs1['EM_IDX']}&EJ_IDX={$rs1['EJ_IDX']}";

                $event_list .= "<tr>";
                $event_list .= "<td><a href=\"".$rs1['link']."\">".F_hsc($rs1['EM_TITLE'])."</a></td>";
                $event_list .= "<td class=\"newwin\"><a href=\"".$rs1['EJ_link']."\">".F_hsc($rs1['EJ_NAME'])."</a></td>";
                $event_list .= "<td><a href=\"".$rs1['link']."\">". (($rs1['EJ_STATUS']=='2') ? '승인' : '미승인') ."</a></td>";
                $event_list .= "<td class=\"td_datetime\"><a href=\"".$rs1['link']."\">".date('Y-m-d', strtotime($rs1['EJ_WDATE']))."</a></td>";
                $event_list .= "</tr>";
            }
            # END LOOP
        }        
    }

    # 대관 관리 
    # 권한을 체크한다. 행사관리는 990200
    $rent_list = "<tr><td colspan=\"4\" align=\"center\">권한이 없습니다.</td></tr>";
    if( $auth['990200'] || ($is_admin == 'super') ){


        $sql = " SELECT 
                    a.*, b.PM_IDX 
                 FROM
                    {$g5['place_rental_req_table']} a
                    LEFT JOIN PLACE_RENTAL_SUB b 
                    ON (a.PS_IDX = b.PS_IDX) 
                 WHERE (1)
                    AND PR_DDATE is null
                 ORDER BY 
                    a.PR_SDATE DESC
                 LIMIT
                    0, {$get_rows}  
        ";

        $rent_result = sql_query($sql);

        

        if(sql_num_rows($rent_result) <= 0){
            $rent_list = "<tr><td colspan=\"4\">등록된 행사가 없습니다.</td></tr>";
        }
        else{
            $rent_list = "";
            for ($i = 0; $rent=sql_fetch_array($rent_result); $i++) {
                // GET PS_NAME
                $sql = "SELECT PS_NAME, PS_GUBUN from {$g5['place_rental_sub_table']} where (1) and PS_IDX = {$rent['PS_IDX']}";
                $result_PS = sql_fetch($sql);

                // GET USER NAME
                $sql = "SELECT mb_nick from {$g5['member_table']} where (1) and mb_no = {$rent['mb_no']}";
                $result_member = sql_fetch($sql);

                $sdate = date('Y-m-d', strtotime($rent['PR_SDATE']));
                $year = date('Y', strtotime($rent['PR_SDATE']));
                $month = date('m', strtotime($rent['PR_SDATE']));

                if($sdate < 0) {
                    $day_list[$i]['PR_SDATE'] = '-';
                }
                else {
                    $day_list[$i]['PR_SDATE'] = $sdate;
                }
                
                $day_list[$i]['PR_IDX'] = $rent['PR_IDX'];
                $day_list[$i]['PS_IDX'] = $rent['PS_IDX'];
                $day_list[$i]['PS_NAME'] = $result_PS['PS_NAME'];

                if($rent['PR_STATUS'] == 'A')
                    $day_list[$i]['PR_STATUS'] = '신청';
                else if($rent['PR_STATUS'] == 'B')
                    $day_list[$i]['PR_STATUS'] = '승인';
                else if($rent['PR_STATUS'] == 'C')
                    $day_list[$i]['PR_STATUS'] = '보류';
                else if($rent['PR_STATUS'] == 'D')
                    $day_list[$i]['PR_STATUS'] = '삭제';

                $day_list[$i]['link_href'] = '/adm/rent/place_rental_req_list.php?PM_IDX='.$rent['PM_IDX'].'&PS_IDX='.$rent['PS_IDX'].'&PR_IDX='.$rent['PR_IDX'].'&year='.$year.'&month='.$month.'&popup=1';
                


                $rent_list .= "<tr>";
                $rent_list .= "<td><a href=\"".$day_list[$i]['link_href']."\">".$day_list[$i]['PS_NAME']."</a></td>"; //장소명
                $rent_list .= "<td><a href=\"".$day_list[$i]['link_href']."\">".$day_list[$i]['mb_nick']."</a></td>"; //신청자
                $rent_list .= "<td><a href=\"".$day_list[$i]['link_href']."\">".$day_list[$i]['PR_STATUS']."</a></td>"; //상태
                $rent_list .= "<td class=\"td_datetime\"><a href=\"".$day_list[$i]['link_href']."\">".$day_list[$i]['PR_SDATE']."</a></td>"; //날짜
                $rent_list .= "</tr>";
            }
            # END LOOP
        } 
    }


    # Visitor Count
    $sql       = " SELECT vs_date, vs_count FROM g5_visit_sum ORDER BY vs_date DESC LIMIT 0, 7";
    $vs_result = sql_query($sql);
    $vs_date   = "";
    $vs_count  = "";
    for($i=0; $vs_row = sql_fetch_array($vs_result); $i++){
        $vs_date  = ('"' . $vs_row['vs_date'] . '"' . (($i == 0) ? '' : ',')) . $vs_date;
        $vs_count = ($vs_row['vs_count'] . (($i == 0) ? '' : ',')) . $vs_count;
    }

    # Add Chart Script
    echo "<script src=\"/js/Chart.js\"></script>";
    echo "<style>.show-grid h2{padding: 10px 0;}</style>";
    
    # Visitor login user Count Day 일별 접속자(최근 일주일) 접속자 가입자
    $vs_count_joinuser_day = "";
    $vs_date_joinuser_day = "";
    $vs_count_loginuser_day = "";
    $vs_date_loginuser_day = "";
    $vs_count_conuser_day = "";
    $vs_date_conuser_day = "";
    $user_day_table = "";
    for ($i=0; $i<10; $i++){
        ${"todayDay_".$i} = date("Y-m-d", strtotime("-".$i." days"));
        ${"todayDaytest_".$i} = date("Y-m-d", strtotime("+".$i." days"));

        #방문자
        ${"sql_conuser_day".$i}       = "SELECT sum(vs_count) as cnt FROM g5_visit_sum WHERE vs_date like '%".${"todayDay_".$i}."%'";
        ${"result_conuser_day_".$i} = sql_query(${"sql_conuser_day".$i});
        ${"$conuser_daysql_".$i} = sql_fetch_array(${"result_conuser_day_".$i} );
        $vs_count_conuser_day  = ('"' .${"$conuser_daysql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_conuser_day;
        $vs_date_conuser_day = ('"' .${"todayDay_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_conuser_day;
        $user_day_table .= "<tr>";
        $user_day_table .= "<td>".${"todayDay_".$i}."</td>";
        $user_day_table .= "<td>".((${"$conuser_daysql_".$i}['cnt'] == null) ? '0' : ${"$conuser_daysql_".$i}['cnt']).  "</td>";
        
        #접속자
        ${"sql_loginuser_day_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE 1=1  AND mb_today_login like '%".${"todayDay_".$i}."%'";
        ${"result_loginuser_day_".$i} = sql_query(${"sql_loginuser_day_".$i});
        ${"$loginuser_daysql_".$i} = sql_fetch_array(${"result_loginuser_day_".$i} );
        $vs_count_loginuser_day  = ('"' .${"$loginuser_daysql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_loginuser_day;
        $vs_date_loginuser_day = ('"' .${"todayDay_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_loginuser_day;
        $user_day_table .= "<td>".((${"$loginuser_daysql_".$i}['cnt'] == null) ? '0' : ${"$loginuser_daysql_".$i}['cnt']).  "</td>";
        
        #가입자
        ${"sql_joinuser_day_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_datetime like '%".${"todayDay_".$i}."%'";
        ${"result_joinuser_day_".$i} = sql_query(${"sql_joinuser_day_".$i});
        ${"$joinuser_daysql_".$i} = sql_fetch_array(${"result_joinuser_day_".$i} );
        $vs_count_joinuser_day  = ('"' .${"$joinuser_daysql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_joinuser_day;
        $vs_date_joinuser_day = ('"' .${"todayDay_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_joinuser_day;
        $user_day_table .= "<td>".((${"$joinuser_daysql_".$i}['cnt'] == null) ? '0' : ${"$joinuser_daysql_".$i}['cnt']).  "</td>";
        $user_day_table .= "</tr>";
    }
    
    
    # Visitor login user Count Month 월별 접속자
    $vs_date_joinuser_YearMonth = "";
    $vs_count_joinuser_YearMonth = "";
    $vs_date_loginuser_YearMonth = "";
    $vs_count_loginuser_YearMonth = "";
    $vs_date_conuser_YearMonth = "";
    $vs_count_conuser_YearMonth = "";
    $user_YearMonth_table = "";
    for ($i=0; $i<13; $i++){
        ${"todayYearMonth_".$i} = date("Y-m", strtotime("-".$i." months"));
        #방문자
        ${"sql_conuser_month_".$i}       = "SELECT sum(vs_count) as cnt FROM g5_visit_sum WHERE vs_date like '%".${"todayYearMonth_".$i}."%'";
        ${"result_conuser_month_".$i} = sql_query(${"sql_conuser_month_".$i});
        ${"$conuser_monthsql_".$i} = sql_fetch_array(${"result_conuser_month_".$i} );
        $vs_count_conuser_YearMonth  = ('"' .${"$conuser_monthsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_conuser_YearMonth;
        $vs_date_conuser_YearMonth = ('"' .${"todayYearMonth_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_conuser_YearMonth;
        $user_YearMonth_table .= "<tr>";
        $user_YearMonth_table .= "<td>".${"todayYearMonth_".$i}."</td>";
        $user_YearMonth_table .= "<td>".((${"$conuser_monthsql_".$i}['cnt'] == null) ? '0' : ${"$conuser_monthsql_".$i}['cnt']).  "</td>";
        #접속자
        ${"sql_loginuser_month_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE 1=1  AND mb_today_login like '%".${"todayYearMonth_".$i}."%'";
        ${"result_loginuser_month_".$i} = sql_query(${"sql_loginuser_month_".$i});
        ${"$loginuser_monthsql_".$i} = sql_fetch_array(${"result_loginuser_month_".$i} );
        $vs_count_loginuser_YearMonth  = ('"' .${"$loginuser_monthsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_loginuser_YearMonth;
        $vs_date_loginuser_YearMonth = ('"' .${"todayYearMonth_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_loginuser_YearMonth;
        $user_YearMonth_table .= "<td>".${"$loginuser_monthsql_".$i}['cnt']."</td>";
        #가입자
        ${"sql_joinuser_month_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_datetime like '%".${"todayYearMonth_".$i}."%'";
        ${"result_joinuser_month_".$i} = sql_query(${"sql_joinuser_month_".$i});
        ${"$joinuser_monthsql_".$i} = sql_fetch_array(${"result_joinuser_month_".$i} );
        $vs_count_joinuser_YearMonth  = ('"' .${"$joinuser_monthsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_joinuser_YearMonth;
        $vs_date_joinuser_YearMonth = ('"' .${"todayYearMonth_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_joinuser_YearMonth;
        $user_YearMonth_table .= "<td>".${"$joinuser_monthsql_".$i}['cnt']."</td>";
        $user_YearMonth_table .= "</tr>";
    }
    
    # Visitor login user Count Year 년별 접속자
    $vs_date_joinuser_Year = "";
    $vs_count_joinuser_Year = "";
    $vs_date_loginuser_Year = "";
    $vs_count_loginuser_Year = "";
    $vs_date_conuser_Year = "";
    $vs_count_conuser_Year = "";
    $user_Year_table = "";
    for ($i=0; $i<3; $i++){
        ${"todayYear_".$i} = date("Y", strtotime("-".$i." years"));
        #방문자
        ${"sql_conuser_year_".$i}       = "SELECT sum(vs_count) as cnt FROM g5_visit_sum WHERE vs_date like '%".${"todayYear_".$i}."%'";
        ${"result_conuser_year_".$i} = sql_query(${"sql_conuser_year_".$i});
        ${"$conuser_yearsql_".$i} = sql_fetch_array(${"result_conuser_year_".$i} );
        $vs_count_conuser_Year  = ('"' .${"$conuser_yearsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_conuser_Year;
        $vs_date_conuser_Year = ('"' .${"todayYear_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_conuser_Year;
        $user_Year_table .= "<tr>";
        $user_Year_table .= "<td>".${"todayYear_".$i}."</td>";
        $user_Year_table .= "<td>".${"$conuser_yearsql_".$i}['cnt'].  "</td>";
        #접속자
        ${"sql_loginuser_year_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE 1=1  AND mb_today_login like '%".${"todayYear_".$i}."%'";
        ${"result_loginuser_year_".$i} = sql_query(${"sql_loginuser_year_".$i});
        ${"$loginuser_yearsql_".$i} = sql_fetch_array(${"result_loginuser_year_".$i} );
        $vs_count_loginuser_Year  = ('"' .${"$loginuser_yearsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_loginuser_Year;
        $vs_date_loginuser_Year = ('"' .${"todayYear_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_loginuser_Year;
        $user_Year_table .= "<td>".${"$loginuser_yearsql_".$i}['cnt']."</td>";
        #가입자
        ${"sql_joinuser_year_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE 1=1  AND mb_datetime like '%".${"todayYear_".$i}."%'";
        ${"result_joinuser_year_".$i} = sql_query(${"sql_joinuser_year_".$i});
        ${"$joinuser_yearsql_".$i} = sql_fetch_array(${"result_joinuser_year_".$i} );
        $vs_count_joinuser_Year  = ('"' .${"$joinuser_yearsql_".$i}['cnt']. '"' . (($i == 0) ? '' : ',')) .$vs_count_joinuser_Year;
        $vs_date_joinuser_Year = ('"' .${"todayYear_".$i}. '"' . (($i == 0) ? '' : ',')) .$vs_date_joinuser_Year;
        $user_Year_table .= "<td>".${"$joinuser_yearsql_".$i}['cnt']."</td>";
        $user_Year_table .= "</tr>";
        
    }
    
    #월별 가입 회원수
    
    #년별 가입 회원수
    
    # 전체 유저 
    $sql_Alluser       = "SELECT count(*) as cnt FROM gill.g5_member";
    $result_Alluser = sql_query($sql_Alluser);
    $row_Alluser = sql_fetch_array($result_Alluser);
    $cnt_Alluser = $row_Alluser['cnt'];
    
    # 활성화 유저 (현재 기준 1년안에 로그인 유저)
    $sql_Onuser       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_today_login >= SUBDATE(NOW(), INTERVAL 1 YEAR)";
    $result_Onuser = sql_query($sql_Onuser);
    $row_Onuser = sql_fetch_array($result_Onuser);
    $cnt_Onuser = $row_Onuser['cnt'];
    
    # 휴면 유저 (1년 이상 미접속)
    $sql_Offuser       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_today_login < SUBDATE(NOW(), INTERVAL 1 YEAR)";
    $result_Offuser = sql_query($sql_Offuser);
    $row_Offuser = sql_fetch_array($result_Offuser);
    $cnt_Offuser = $row_Offuser['cnt'];
    //////////////////////////////////////////////////////////////////////////////////////////
    
    #우리동네 학습공간 실적
    $today = date("Y-m-d");
    $tomonth = date("Y-m");
    $toyear = date("Y");
    
    $sql_udong_day       = "SELECT count(*) as cnt FROM gill.local_place_req where lr_wdate like '%".$today."%'";
    $result_udong_day  = sql_query($sql_udong_day);
    $row_udong_day  = sql_fetch_array($result_udong_day);
    $cnt_udong_day  = $row_udong_day['cnt'];
    
    $sql_udong_month       = "SELECT count(*) as cnt FROM gill.local_place_req where lr_wdate like '%".$tomonth."%'";
    $result_udong_month  = sql_query($sql_udong_month);
    $row_udong_month  = sql_fetch_array($result_udong_month);
    $cnt_udong_month  = $row_udong_month['cnt'];
    
    $sql_udong_year       = "SELECT count(*) as cnt FROM gill.local_place_req where lr_wdate like '%".$toyear."%'";
    $result_udong_year  = sql_query($sql_udong_year);
    $row_udong_year  = sql_fetch_array($result_udong_year);
    $cnt_udong_year  = $row_udong_year['cnt'];
    
    #모집정보 학습공간 실적
    $sql_evt_day       = "SELECT count(*) as cnt FROM gill.NX_EVENT_JOIN where EJ_WDATE like '%".$today."%'";
    $result_evt_day  = sql_query($sql_evt_day);
    $row_evt_day  = sql_fetch_array($result_evt_day);
    $cnt_evt_day  = $row_evt_day['cnt'];
    
    $sql_evt_month       = "SELECT count(*) as cnt FROM gill.NX_EVENT_JOIN where EJ_WDATE like '%".$tomonth."%'";
    $result_evt_month  = sql_query($sql_evt_month);
    $row_evt_month  = sql_fetch_array($result_evt_month);
    $cnt_evt_month  = $row_evt_month['cnt'];
    
    $sql_evt_year       = "SELECT count(*) as cnt FROM gill.NX_EVENT_JOIN where EJ_WDATE like '%".$toyear."%'";
    $result_evt_year  = sql_query($sql_evt_year);
    $row_evt_year  = sql_fetch_array($result_evt_year);
    $cnt_evt_year  = $row_evt_year['cnt'];
    
?>

<section>
<style>
    .h4 .more{float: right; font-size: 15px;}
    table{table-layout: fixed;}
    td{overflow: hidden; -ms-text-overflow: ellipsis; text-overflow: ellipsis; white-space: nowrap;}
</style>
<script>
Chart.plugins.register({
	afterDatasetsDraw: function(chart) {
    	var ctx = chart.ctx;
    
    	chart.data.datasets.forEach(function(dataset, i) {
        	var meta = chart.getDatasetMeta(i);
        	if (!meta.hidden) {
        		meta.data.forEach(function(element, index) {
                	// Draw the text in black, with the specified font
                	ctx.fillStyle = 'rgb(0, 0, 0)';
                
                	var fontSize = 12;
                	var fontStyle = 'normal';
                	var fontFamily = 'Helvetica Neue';
                	ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                
                	// Just naively convert to string for now
                	var dataString = dataset.data[index].toString();
                
                	// Make sure alignment settings are correct
                	ctx.textAlign = 'center';
                	ctx.textBaseline = 'middle';
                
                	var padding = 5;
                	var position = element.tooltipPosition();
                	ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
				});
			}
		});
	}
});
window.onload = function(){
    new Chart(
                document.getElementById("chartjs-0"),
                {"type":"line",
                 "data":
                    {"labels":
                        [<?=$vs_date_conuser_day?>],
                        "datasets":
                            [
                                {"label":"일별 방문자 수",
                                 "data":[<?=$vs_count_conuser_day?>],
                                 "fill":false,
                                 "borderColor":"rgb(75, 192, 192)",
                                 "lineTension":0.1
                              }
                            ]
                    },
                    "options":{
                        'responsive' : true
                        
                    }
                }
            );
    new Chart(
            document.getElementById("chartjs-1"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_conuser_YearMonth?>],
                    "datasets":
                        [
                            {"label":"월별 방문자 수",
                             "data":[<?=$vs_count_conuser_YearMonth?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-2"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_conuser_Year?>],
                    "datasets":
                        [
                            {"label":"년별 방문자자 수",
                             "data":[<?=$vs_count_conuser_Year?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-10"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_loginuser_YearMonth?>],
                    "datasets":
                        [
                            {"label":"월별 접속자 수",
                             "data":[<?=$vs_count_loginuser_YearMonth?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-15"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_joinuser_YearMonth?>],
                    "datasets":
                        [
                            {"label":"월별 가입자 수",
                             "data":[<?=$vs_count_joinuser_YearMonth?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-11"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_loginuser_Year?>],
                    "datasets":
                        [
                            {"label":"년별 접속자 수",
                             "data":[<?=$vs_count_loginuser_Year?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-14"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_joinuser_Year?>],
                    "datasets":
                        [
                            {"label":"년별 가입자 수",
                             "data":[<?=$vs_count_joinuser_Year?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-12"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_loginuser_day?>],
                    "datasets":
                        [
                            {"label":"일별 접속자 수",
                             "data":[<?=$vs_count_loginuser_day?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    new Chart(
            document.getElementById("chartjs-13"),
            {"type":"line",
             "data":
                {"labels":
                    [<?=$vs_date_joinuser_day?>],
                    "datasets":
                        [
                            {"label":"일별 가입자 수",
                             "data":[<?=$vs_count_joinuser_day?>],
                             "fill":false,
                             "borderColor":"rgb(75, 192, 192)",
                             "lineTension":0.1
                          }
                        ]
                },
                "options":{
                    'responsive' : true

                }
            }
        );
    document.getElementById("policyTab_2").style.display = "none";
    document.getElementById("policyTab_3").style.display = "none";
    document.getElementById("policyTab2").style.color = "black";
    document.getElementById("policyTab3").style.color = "black";
}

function changePolicyTab(arg){ 
	for(i=1;i<=3;i++) { 
		document.getElementById("policyTab_"+i).style.display = "none"; 
		document.getElementById("policyTab"+i).style.backgroundColor = "white";
		document.getElementById("policyTab"+i).style.color = "black";
		document.getElementById("policyTab"+i).style.fontWeight = "normal";
	} 
	document.getElementById("policyTab_"+arg).style.display = "block";
	document.getElementById("policyTab"+arg).style.backgroundColor = "black"; 
	document.getElementById("policyTab"+arg).style.color = "white";
	document.getElementById("policyTab"+arg).style.fontWeight = "bold";
} 


</script>
<ul>
    <li onmouseover="changePolicyTab('1'); return false;"  href="#" id="policyTab1" style="width:120px; height:30px; float:left; text-align:center; padding-top:5px; background-color:black; font-weight:bold; color:white;">일간 현황</li> 
    <li onmouseover="changePolicyTab('2'); return false;"  href="#" id="policyTab2" style="width:120px; height:30px; float:left; text-align:center; padding-top:5px;">월간 현황</li> 
    <li onmouseover="changePolicyTab('3'); return false;"  href="#" id="policyTab3" style="width:120px; height:30px; float:left; text-align:center; padding-top:5px;">년간 현황</li> 
</ul>
<div style="border-bottom: 1px solid black; clear: both;"></div>
<!-- bootstrapK Start -->
<ul id="policyTab_1"> 
<li>
	<div class="row show-grid">
        <div class="col-md-12"> 
            <h2 class="h4">방문자 수</h2>
            <canvas id="chartjs-0" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="col-md-12"> 
            <h2 class="h4">접속자 수</h2>
            <canvas id="chartjs-12" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="col-md-12"> 
            <h2 class="h4">가입자 수</h2>
            <canvas id="chartjs-13" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
	</div>
</li>
</ul>

<ul id="policyTab_2" > 
<li>
	<div class="row show-grid">
    	<div class="col-md-12"> 
            <h2 class="h4">방문자 수</h2>
            <canvas id="chartjs-1" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12"> 
            <h2 class="h4">접속자 수</h2>
            <canvas id="chartjs-10" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
         <div class="col-md-12"> 
            <h2 class="h4">가입자 수</h2>
            <canvas id="chartjs-15" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
	</div>
</li>
</ul>

<ul id="policyTab_3" > 
<li>
	<div class="row show-grid">
    	<div class="col-md-12"> 
            <h2 class="h4">방문자 수</h2>
            <canvas id="chartjs-2" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12"> 
            <h2 class="h4">접속자 수</h2>
            <canvas id="chartjs-11" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12"> 
            <h2 class="h4">가입자 수</h2>
            <canvas id="chartjs-14" class="chartjs" style="display: block; width: 100%; height: 225px;"></canvas>
        </div>
        <div class="clearfix"></div>
	</div>
</li>
</ul>


<div class="row show-grid">
<!--  -->
	<div class="col-md-6">
        <h2 class="h4">일별 방문자, 접속자, 가입자 
			<a href="./userday.excel.php?year=2019" class="more">&nbsp;2019 )</a>        	
            <a href="./userday.excel.php?year=2018" class="more">&nbsp;2018 |</a>
            <a href="./userday.excel.php?year=2017" class="more">( 2017 |</a>
            <span style="float:right; font-size:15px;">엑셀 다운로드</span>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <caption>일별 방문자, 접속자, 가입자</caption>
            <thead>
                <tr>
                    <th scope="col">날짜/일</th>
                    <th scope="col">방문자</th>
                    <th scope="col">접속자</th>
                    <th scope="col">가입자</th>
                </tr>
            </thead>
            <tbody>
				<?php echo $user_day_table; ?>
            </tbody>
            </table>
    </div>
    
    <div class="col-md-6">
        <h2 class="h4">월별 방문자, 접속자, 가입자 
            <a href="./useryearmonth.excel.php" class="more">엑셀 다운로드</a>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <caption>월별 방문자, 접속자, 가입자</caption>
            <thead>
                <tr>
                    <th scope="col">날짜/월</th>
                    <th scope="col">방문자</th>
                    <th scope="col">접속자</th>
                    <th scope="col">가입자</th>
                </tr>
            </thead>
            <tbody>
				<?php echo $user_YearMonth_table; ?>
            </tbody>
            </table>
    </div>
    
    <div class="col-md-6">
        <h2 class="h4">년별 방문자, 접속자, 가입자
            <a href="./useryear.excel.php" class="more">엑셀 다운로드</a>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <caption>년별 방문자, 접속자, 가입자</caption>
            <thead>
                <tr>
                    <th scope="col">날짜/년</th>
                    <th scope="col">방문자</th>
                    <th scope="col">접속자</th>
                    <th scope="col">가입자</th>
                </tr>
            </thead>
            <tbody>
				<?php echo $user_Year_table; ?>
            </tbody>
            </table>

    </div>
    
    <div class="col-md-6" style="maer">
        <h2 class="h4">활성 회원, 휴먼회원
            <a href="./usertest.excel.php" class="more">엑셀 다운로드</a>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <caption>전체, 활성, 휴먼 회원</caption>
            <thead>
                <tr>
                    <th scope="col">종류</th>
                    <th scope="col">인원</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
					<td>전체 회원</td>
					<td><?php echo $cnt_Alluser?></td>
				</tr>
				<tr>
					<td>활성 회원</td>
					<td><?php echo $cnt_Onuser?></td>
				</tr>
				<tr>
					<td>휴먼 회원</td>
					<td><?php echo $cnt_Offuser?></td>
				</tr>
            </tbody>
            </table>
    </div>
    
    <div class="col-md-6" style="maer">
        <h2 class="h4">우리동네 학습공간 실적
            <a href="./userudong.excel.php?year=2019" class="more">&nbsp;2019 )</a>        	
            <a href="./userudong.excel.php?year=2018" class="more">&nbsp;2018 |</a>
            <a href="./userudong.excel.php?year=2017" class="more">( 2017 |</a>
            <span style="float:right; font-size:15px;">엑셀 다운로드</span>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <caption>당일, 당월, 당해년</caption>
            <thead>
                <tr>
                    <th scope="col">종류</th>
                    <th scope="col">예약인원</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
					<td>당일(<?php echo $today?>)</td>
					<td><?php echo $cnt_udong_day?></td>
				</tr>
				<tr>
					<td>당월(<?php echo $tomonth?>)</td>
					<td><?php echo $cnt_udong_month?></td>
				</tr>
				<tr>
					<td>당해년(<?php echo $toyear?>)</td>
					<td><?php echo $cnt_udong_year?></td>
				</tr>
            </tbody>
            </table>
    </div>
    
     <div class="col-md-6" style="maer">
        <h2 class="h4">모집정보 학습공간 실적
            <a href="./userevt.excel.php?year=2019" class="more">&nbsp;2019 )</a>        	
            <a href="./userevt.excel.php?year=2018" class="more">&nbsp;2018 |</a>
            <a href="./userevt.excel.php?year=2017" class="more">( 2017 |</a>
            <span style="float:right; font-size:15px;">엑셀 다운로드</span>
        </h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <caption>당일, 당월, 당해년</caption>
            <thead>
                <tr>
                    <th scope="col">종류</th>
                    <th scope="col">신청인원</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
					<td>당일(<?php echo $today?>)</td>
					<td><?php echo $cnt_evt_day?></td>
				</tr>
				<tr>
					<td>당월(<?php echo $tomonth?>)</td>
					<td><?php echo $cnt_evt_month?></td>
				</tr>
				<tr>
					<td>당해년(<?php echo $toyear?>)</td>
					<td><?php echo $cnt_evt_year?></td>
				</tr>
            </tbody>
            </table>
    </div>
	
<!--  -->

    <div class="col-md-6">
        <h2 class="h4">신규가입회원 <?php echo $get_rows ?>건 목록 
            <a href="./member_list.php" class="more">더보기</a>
        </h2>

        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col style="width: 154px;">
            </colgroup>
            <caption>신규가입회원</caption>
            <thead>
                <tr>
                    <th scope="col">회원아이디</th>
                    <th scope="col">이름</th>
                    <th scope="col">닉네임</th>
                    <th scope="col">가입일</th>
                </tr>
            </thead>
            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++)
            {
                // 접근가능한 그룹수
                $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                $row2 = sql_fetch($sql2);
                $group = "";
                if ($row2['cnt'])
                    $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

                if ($is_admin == 'group')
                {
                    $s_mod = '';
                    $s_del = '';
                }
                else
                {
                    $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id='.$row['mb_id'].'">수정</a>';
                    $s_del = '<a href="./member_delete.php?'.$qstr.'&amp;w=d&amp;mb_id='.$row['mb_id'].'&amp;url='.$_SERVER['SCRIPT_NAME'].'" onclick="return delete_confirm(this);">삭제</a>';
                }
                $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">그룹</a>';

                $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
                $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

                #$mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);
                $mb_nick = get_text($row['mb_nick']);
                $mb_nick = "<a href=\"/adm/member_form.php?sst=&sod=&sfl=&stx=&page=&w=u&mb_id=".$row['mb_id']."\">".$mb_nick."</a>";

                ///adm/member_form.php?sst=&sod=&sfl=&stx=&page=&w=u&mb_id=57f89d5dcf0a5

                $mb_id = $row['mb_id'];
                if ($row['mb_leave_date'])
                    $mb_id = $mb_id;
                else if ($row['mb_intercept_date'])
                    $mb_id = $mb_id;

                $mb_datetime = date("Y-m-d", strtotime($row['mb_datetime']));

            ?>
            <tr>
                <td class="td_mbid"><?php echo $mb_id ?></td>
                <td class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
                <td class="td_mbname sv_use"><div><?php echo $mb_nick ?></div></td>
                <td class="td_datetime"><?=$mb_datetime?></td>
            </tr>
            <?php
                }
            if ($i == 0)
                echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
            </table>

    </div>

<?php
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id AND a.wr_id = a.wr_parent ";

if ($gr_id)
$sql_common .= " and b.gr_id = '$gr_id' ";
if ($view) {
if ($view == 'w')
    $sql_common .= " and a.wr_id = a.wr_parent ";
else if ($view == 'c')
    $sql_common .= " and a.wr_id <> a.wr_parent ";
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$colspan = 5;
?>

<?php
# 최근 로그인 한 사용자 목록

$query = "SELECT mb_no, mb_id, mb_name, mb_nick, mb_today_login, mb_login_ip FROM {$g5['member_table']} ORDER BY mb_today_login DESC LIMIT 0, 10";
$result = sql_query($query, true);
$logined = "";
for($i=0; $login = sql_fetch_array($result); $i++){
$logined .= "<tr>";
$logined .= "<td>{$login['mb_id']}</td>";
$logined .= "<td>{$login['mb_name']}</td>";
$logined .= "<td>{$login['mb_nick']}</td>";
$logined .= "<td class=\"td_datetime\">".date('Y-m-d', strtotime($login['mb_today_login']))."</td>";
$logined .= "</tr>";
}
?>

    <div class="col-md-6">
        <h2 class="h4">최근 로그인한 사용자 10건 목록</h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col style="width: 154px;">
            </colgroup>
            <caption>최근 로그인한 사용자</caption>
            <thead>
                <tr>
                    <th>회원아이디</th>
                    <th>이름</th>
                    <th>닉네임</th>
                    <th>로그인 일자</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $logined; ?>
            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
</div>


<div class="row show-grid">

    <div class="col-md-6">
        <h2 class="h4">행사 신청 10건 목록</h2>
        <table class="table table-response table-striped">
            <colgroup>
                <col>
                <col>
                <col>
                <col style="width: 154px;">
            </colgroup>
            <caption>행사 신청 10건 목록</caption>
            <thead>
                <tr>
                    <th>행사명</th>
                    <th>신청자명</th>
                    <th>신청 상태</th>
                    <th>신청 일자</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $event_list ?>
            </tbody>
            
        </table>
    </div>

    <div class="col-md-6">
        <h2 class="h4">대관 관리 10건 목록</h2>
        <table class="table table-response table-striped">
            <caption>대관 관리 10건 목록</caption>
            <colgroup>
                <col>
                <col>
                <col>
                <col style="width: 154px;">
            </colgroup>
            <thead>
                <tr>
                    <th>장소명</th>
                    <th>신청자</th>
                    <th>상태</th>
                    <th>날짜</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $rent_list ?>                
            </tbody>
            
        </table>
    </div>

</div>


<div class="row show-grid">

    <div class="col-md-6">
        <h2 class="h4">최근게시물 10건 목록</h2>
            <table class="table table-response table-striped">
                <caption>신규가입회원</caption>
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col style="width: 154px;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">그룹</th>
                        <th scope="col">게시판</th>
                        <th scope="col">제목</th>
                        <th scope="col">등록일</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$get_rows} ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

        if ($row['wr_id'] == $row['wr_parent']) // 원글
        {
            $comment = "";
            $comment_link = "";
            $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ");

            $name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])), $row2['wr_email'], $row2['wr_homepage']);
            // 당일인 경우 시간으로 표시함
            $datetime = substr($row2['wr_datetime'],0,10);
            $datetime2 = $row2['wr_datetime'];
            if ($datetime == G5_TIME_YMD)
                $datetime2 = substr($datetime2,11,5);
            else
                $datetime2 = substr($datetime2,5,5);

        }
    ?>

    <tr>
        <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo cut_str($row['gr_subject'],10) ?></a></td>
        <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>"><?php echo cut_str($row['bo_subject'],20) ?></a></td>
        <td><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>&amp;wr_id=<?php echo $row2['wr_id'] ?><?php echo $comment_link ?>"><?php echo conv_subject($row2['wr_subject'], 30) ?></a></td>
<!--             <td class="td_mbname"><div><?php echo $name ?></div></td>
-->            <td class="td_datetime"><?php echo $datetime ?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
            </table>
        </div>


<?php
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id AND a.wr_id != a.wr_parent ";

if ($gr_id)
$sql_common .= " and b.gr_id = '$gr_id' ";
if ($view) {
if ($view == 'w')
    $sql_common .= " and a.wr_id = a.wr_parent ";
else if ($view == 'c')
    $sql_common .= " and a.wr_id <> a.wr_parent ";
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$colspan = 4;
?>

    <div class="col-md-6">
        <h2 class="h4">최근댓글 10건 목록 </h2>
            <table class="table table-response table-striped">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col style="width: 154px;">
                </colgroup>
                <caption>최근댓글 10건 목록</caption>
                <thead>
                    <tr>
                        <th scope="col">그룹</th>
                        <th scope="col">게시판</th>
                        <th scope="col">제목</th>
                        <th scope="col">등록일</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$get_rows} ";
    $result = sql_query($sql);

    
    # 새글 DB와 행사댓글 DB를 10개씩 불러와 배열에 저장 후 최신 10개만 보여줌.
    $_arr_cmt = array();


    # 새글 DB 댓글
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

        if ($row['wr_id'] == $row['wr_parent']) // 원글
        {
            $row2 = sql_fetch(" select wr_subject, wr_datetime from $tmp_write_table where wr_id = '{$row['wr_id']}' ");

            $_arr_cmt[$row2['wr_datetime']] = array(
                'gr_subject' => $row['gr_subject'],
                'bo_subject' => $row['bo_subject'],
                'subject' => $row2['wr_subject'],
                'gr_link' => G5_BBS_URL . '/new.php?gr_id=' . $row['gr_id'],
                'bo_link' => G5_BBS_URL . '/board.php?bo_table=' . $row['bo_table'],
                'wr_link' => G5_BBS_URL . '/board.php?bo_table=' . $row['bo_table'] . '&wr_id=' . $row['wr_id'],
                'datetime' => substr($row2['wr_datetime'],0,10)
                );
        }
        else // 코멘트
        {
            $row2 = sql_fetch(" SELECT wr_id, wr_subject FROM {$tmp_write_table} WHERE wr_id = '{$row['wr_parent']}' ");
            $row3 = sql_fetch(" SELECT wr_datetime FROM {$tmp_write_table} WHERE wr_id = '{$row['wr_id']}' ");

            $_arr_cmt[$row3['wr_datetime']] = array(
                'gr_subject' => $row['gr_subject'],
                'bo_subject' => $row['bo_subject'],
                'subject' => $row2['wr_subject'],
                'gr_link' => G5_BBS_URL . '/new.php?gr_id=' . $row['gr_id'],
                'bo_link' => G5_BBS_URL . '/board.php?bo_table=' . $row['bo_table'],
                'wr_link' => G5_BBS_URL . '/board.php?bo_table=' . $row['bo_table'] . '&wr_id=' . $row['wr_id'] . '#c_' . $row['wr_id'],
                'datetime' => substr($row3['wr_datetime'],0,10)
                );
        }
    }


    # get : 행사 댓글
    $sql = "Select C.C_TBPK, C.C_WDATE"
        . " , EM.EM_TITLE"
        . " From NX_COMMENT As C"
        . "     Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = C.C_TBPK"
        . " Where C.C_DDATE is null And EM.EM_DDATE is null"
        . "     And C.C_WDATE > DATE_ADD(now(), INTERVAL -1 MONTH)"
        . " Order By C.C_WDATE Desc"
        . " Limit 10"
        ;
    $result = sql_query($sql);

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $_arr_cmt[$row['C_WDATE']] = array(
            'gr_subject' => '모집/대관',
            'bo_subject' => '모집 정보',
            'subject' => $row['EM_TITLE'],
            'gr_link' => G5_URL . '/evt/evt.list.php',
            'bo_link' => G5_URL . '/evt/evt.list.php',
            'wr_link' => G5_URL . '/evt/evt.read.php?EM_IDX=' . $row['C_TBPK'],
            'datetime' => substr($row['C_WDATE'],0,10)
            );
    }

    unset($result, $row);


    # 날짜 최신순으로 재정렬
    krsort($_arr_cmt);
    ?>


    <?php
    if (count($_arr_cmt) == 0) {
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    }
    else {
        $s = 1;
        foreach ($_arr_cmt as $itm) {
            ?>
            <tr>
                <td class="td_category"><a href="<?php echo $itm['gr_link'] ?>"><?php echo cut_str($itm['gr_subject'],10) ?></a></td>
                <td class="td_category"><a href="<?php echo $itm['bo_link'] ?>"><?php echo cut_str($itm['bo_subject'],20) ?></a></td>
                <td><a href="<?php echo $itm['wr_link'] ?>"><?php echo conv_subject($itm['subject'], 100) ?></a></td>
                <td class="td_datetime"><?php echo $itm['datetime'] ?></td>
            </tr>    

            <?php /*
        <tr>
            <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo cut_str($row['gr_subject'],10) ?></a></td>
            <td class="td_category"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>"><?php echo cut_str($row['bo_subject'],20) ?></a></td>
            <td><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $row['bo_table'] ?>&amp;wr_id=<?php echo $row2['wr_id'] ?><?php echo $comment_link ?>"><?php echo $comment ?><?php echo conv_subject($row2['wr_subject'], 100) ?></a></td>
            <td class="td_datetime"><?php echo $datetime ?></td>
        </tr>
            */ ?>

            <?php
            $s++;

            if ($s > 10) break;
        }
    }

    unset($_arr_cmt, $s);
    ?>
    </tbody>
            </table>
        </div>


        <div class="clearfix"></div>
</div>
<!-- bootstrapK End -->

    <?php
    if ($is_admin == 'super') {

        include_once G5_PLUGIN_PATH . '/nx/class.NX_LOGIN_FAIL.php';
        ?>

    <div class="row show-grid">
        <div class="col-md-6">
            <h2 class="h4">접속중단 관리자 회원</h2>
                <table id="login_fail_list" class="table table-response table-striped">
                    <caption>접속중단 관리자 회원</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">남은시간</th>
                            <th scope="col">실패횟수</th>
                            <th scope="col">중단해제</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $LF = LOGIN_FAIL::GET_BLOCK_LIST(array('LEVEL_MIN' => 9, 'LEVEL_MAX' => 10));
                            $LF_LEN = Count($LF['itms']);

                            if ($LF_LEN == 0) {
                                echo '<tr><td colspan="4">접속중단 상태의 회원이 없습니다.</td></tr>';
                            }
                            else {
                                for ($i = 0; $i < $LF_LEN; $i++) {
                                    $itm = $LF['itms'][$i];

                                    ?>
                                    <tr id="LF_<?php echo($itm['mb_no'])?>">
                                        <td><?php echo($itm['mb_id'])?></td>
                                        <td><?php echo(ceil(((strtotime($itm['LF_WDATE']) + 1800) - time()) / 60))?>분</td>
                                        <td><?php echo($itm['LF_CNT'])?></td>
                                        <td><a href="javascript:UnblockLogin(<?php echo($itm['mb_no'])?>);">중단해제</a></td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
        <?php
    }
    ?>

    <div style="margin-top: 100px;"></div>

</section>


<script>
$(function(){
    $('section a').attr('target','_blank');

    $(document).on('click', '.newwin a', function(e){
        window.open($(this).attr('href'), '', 'width=500, height=600, top=15, left=15 scrollbars=yes');
    })
});

function UnblockLogin(mb_no) {
    if (mb_no == '') {
        alert('접속중단 해제에 실패했습니다.'); return;
    }

    $.ajax({
        url: './unblock_login.php', type: 'POST', dataType: 'json',
        data: {mb_no: mb_no}
    })
    .done(function(json) {
        try { 
            if (json.success) {
                $('#LF_' + mb_no).remove();

                if ($('#login_fail_list tbody').children().length == 0) {
                    $('#login_fail_list tbody').html('<tr><td colspan="4">접속중단 상태의 회원이 없습니다.</td></tr>');
                }
                alert('정상적으로 접속중단 해제되었습니다.'); return;
            }
            else {
                if (json.msg) alert(json.msg);
                else alert('접속중단 해제에 실패했습니다.');

                return;
            }
        }
        catch(e){
            alert('접속중단 해제에 실패했습니다.'); return;
        }
    })
    .fail(function($xhr) {
        var data = $.parseJSON($xhr.responseText);
        // console.log(data);
    });
}
</script>



<?php
include_once ('./admin.tail.php');
?>
