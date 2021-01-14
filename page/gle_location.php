<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<style>

 @keyframes bling{
   from {fill: #B9B9B9; background-color: #B9B9B9;}
   to {fill : #ffce06; background-color: #ffce06;}
 }

 .location-wrap{padding-top: 30px;}
 .location-wrap:after{content:'';clear: both;display: block;}

 .clearfix{display: block; clear: both; display: block;}
 .clearfix:after{content:'';clear: both;display: block;}

 .location-wrap h3.title{ border-bottom: 1px solid #E5E5E5; padding: 10px; text-align: center; color: #333;}

 .svg{text-align: center; float: left; width: 48%; position: relative; border: 1px solid #E5E5E5;; height: 503px; border-radius: 5px;}
 .svg svg{width: 100%;}
 .svg path{transition: all .2s ease-in-out; perspective-origin: 50% 50%;}
 .svg path:hover{fill: #8ec700;}
 .svg path.active{fill: #8EC700; animation: bling .5s infinite alternate;}

 .select-box{float: right; width: 48%; border: 1px solid #E5E5E5; height: 503px; border-radius: 5px; position: relative;}
 .select-box ul{margin:10px 0 0; padding: 0 4%; list-style: none; text-align: center;}
 .select-box ul:after{content:'';clear: both;display: block;}
 .select-box li{display: inline-block; width: 30%; margin-bottom: 2%; margin-left: 2%; float: left;}
 .select-box a{
   display: block;
   background: #B9B9B9;
   color: #FFF;
   padding: 1% 0; text-align: center; border-radius: 5px;
   transition: all .2s ease-in-out;
 }
 .select-box li:hover a{
   background: #8ec700; color: #333;
 }
 .select-box li.active a{
   animation: bling .5s infinite alternate;
 }
 .select-box .text-right{
   position: absolute; right:10px; bottom: 10px;
 }

 .location-board-wrap{margin: 35px 0; position: relative;}
 .location-board{width: 100%;}
 .location-board th,
 .location-board td{
   border-bottom: 1px solid #DDD;
   border-right: 1px solid #DDD;
   padding: 5px; text-align: center;
   font-size: 11pt;
   word-break : keep-all;
   -webkit-box-sizing: border-box;
   box-sizing: border-box;
 }
 .location-board thead th{border-top: 1px solid #DDD; background: #EEE; white-space: nowrap;}
 .location-board th:nth-child(1),
 .location-board td:nth-child(1){border-left: 1px solid #DDD;}
 .location-board td:nth-child(2){text-align: left;}
 .location-board td.tel{white-space: nowrap;}
 .location-board td.addr{text-align: left;}
 .location-board td.web{text-align: center;}

 .tooltip {z-index: 9999;}
 .tooltipster-base {font-size: 24px; padding: 5px 10px;}

 .loading {display: none; position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: rgba(0,0,0,.5);}
 .loading .table{ display: table; width: 100%; height: 100%; }
 .loading .tcell{ display: table-cell; vertical-align: middle; text-align: center; color: #FFF;}

 .location-board-wrap .list-page{padding-top: 30px;}

.list-page .pagination a, .view-wrap .pagination a {
  color: #444 !important;
}
.list-page .pagination .active a, .view-wrap .pagination .active a {
    color: #fff !important;
    background: #444 !important;
    border-color: #444 !important;
}
 @media screen and (max-width: 667px){
   .location-wrap .svg{display: none;}
   .location-wrap .select-box{float: none; clear: both; display: block; width: 100%; height: auto;}
   .select-box li{width: 19%; margin-left: 1%; font-size: 11px;}
   .location-board th,
   .location-board td{font-size: 11px; line-height: 1.2;}
   .location-board th:nth-child(3),
   .location-board td:nth-child(3),
   .location-board th:nth-child(6),
   .location-board td:nth-child(6) {display: none;}
 }

 @media screen and (max-width: 350px){
   .select-box li{width: 24%;}
 }

</style>
<link rel="stylesheet" href="/<?php echo G5_LIB_DIR ?>/tooltipster-master2/dist/css/tooltipster.bundle.min.css">
<link rel="stylesheet" href="/<?php echo G5_LIB_DIR ?>/tooltipster-master2/dist/js/plugins/tooltipster-follower/dist/css/tooltipster-follower.min.css">
<script src="/<?php echo G5_LIB_DIR ?>/tooltipster-master2/dist/js/tooltipster.bundle.min.js"></script>
<script src="/<?php echo G5_LIB_DIR ?>/tooltipster-master2/dist/js/plugins/tooltipster-follower/dist/js/tooltipster-follower.min.js"></script>
<div class="location-wrap">
  <div class="svg">
    <h3 class="title">지도로 보기</h3>
    <svg width="338" height="452" viewBox="0 0 169 226" xmlns="http://www.w3.org/2000/svg">

      <g id="경기도">
       <path title="김포시" d="m37.417,110.105l-16.167,-11l2.833,-5.5l-1.833,-3.833l-0.667,-6.333l-3.167,0.333l-3.333,4.333l-6.333,-0.5l-1.833,-2.167l-3.667,0.167l1,2.833l0.333,2.333l-0.5,0.833l1,5.333l0,2.667l0.833,3.333l-0.167,3l2.833,2.833l0,2.667l0.833,2.5l7.667,-3.333l2.5,-4.5l10.833,8l6.667,0.667l1.667,-3.167l-1.332,-1.499z" stroke="#FFFFFF" fill="#B9B9B9" id="김포시"/>
       <path title="고양시" d="m23.083,96.772l2.5,0.333l4.167,-1l6.167,-0.5l2.333,-4.333l2.333,0.833l4.5,-0.833l3.5,2l3,-3.667l2.5,0.333l-0.833,2.5l0,5.667l-0.833,2.5l3.667,-0.5l2,-2.5l4,2.333l-0.833,5.333l-0.833,2l-2.333,-2l-1.667,-2.833l-2.667,3l-2,0.667l-0.333,6.5l-2.5,0.667l-2,2l-3.5,-0.167l-4.5,-3l-1.5,-2l-16.167,-11l1.832,-2.333z" stroke="#FFFFFF" fill="#B9B9B9" id="고양시"/>
       <path title="파주시" d="m22.583,75.439l3.167,-1.667l7.333,-0.667l-6,-9.833l-5.5,-0.333l0.167,-6.5l5.833,3.667l2.833,-3l-0.167,-1.833l2.5,-3.5l1.667,2.333l1.167,5.667l-0.667,3l2.333,0.167l0.833,3.333l2.5,-0.833l1.667,-10.333l2.167,-3.167l5.167,2.5l0.5,-5l3.5,2.167l3.833,-0.167l2.667,-2.833l4.5,2.833l-5.333,7.667l-2.667,3.333l-1,9.667l-0.833,2.667l-2.333,-0.333l-0.667,3.167l0.5,4.5l2.5,1.667l-0.167,6l-3,-0.167l-3,3.667l-3.5,-2l-4.5,0.833l-2.333,-0.833l-2.333,4.333l-6.167,0.5l-4.167,1l-3,-0.333l1.5,-3.167l-1.833,-3.833l-0.667,-6.333l1,-8.003z" stroke="#FFFFFF" fill="#B9B9B9" id="파주시"/>
       <path title="양주시" d="m64.083,60.272l-4.333,-1.333l-3.167,3.5l-1,9.667l-0.833,2.667l-2.333,-0.333l-0.667,3.167l0.5,4.5l2.5,1.667l-0.167,6l-1.333,2.667l0,5.667l-0.833,2.5l3.667,-0.5l2,-2.5l4,2.333l3.333,-3.833l-1.5,-4.833l-1.833,-3.333l3,-2.333l3.833,0.833l4,-1.5l3.667,-1.667l1.167,-6l-1.5,-6l-7.833,-1.667l-1.333,-3.667l-3.833,-1.833l0.831,-3.836z" stroke="#FFFFFF" fill="#B9B9B9" id="양주시"/>
       <path title="의정부시" d="m80.583,87.939l-2,2.833l-0.5,2l-4.167,5.167l-3,-0.333l-1.667,1.333l-3.833,-2.833l-1.5,-4.833l-1.833,-3.333l3,-2.333l3.833,0.833l7.667,-3.167l2.667,2.167l1.333,2.499z" stroke="#FFFFFF" fill="#B9B9B9" id="의정부시"/>
       <path title="동두천시" d="m69.917,52.939l-6.167,7.833l-0.5,3.333l3.667,2.333l1.5,3.167l7.833,1.667l2.333,-2.167l2.167,0l0.667,-3.167l-1.833,-3.333l-5.167,-5l0,-3.833l-4.5,-0.833z" stroke="#FFFFFF" fill="#B9B9B9" id="동두천시"/>
       <path title="연천군" d="m50.25,37.439l-1.667,5.833l-0.667,3.667l-5.333,-2.667l-1.167,5l-2.333,1.5l1,4.5l2.167,-0.167l2.167,-3.167l5.167,2.5l0.5,-4.5l3.5,1.667l3.833,-0.167l2.667,-2.833l4.5,2.833l-4.833,7.5l3.833,1.167l6.333,-7.167l4.5,0.833l0.167,-2.5l2.167,0l2.5,0.333l1.667,-2l-0.833,-6.333l-2.333,-0.5l-0.167,-2.333l3.667,-2.833l4.167,1.333l0,-6.833l0.5,-12.167l-2.167,-3.5l-2.167,-5.333l-3.5,1.833l-1.833,-2.333l0.167,-6.833l-9.333,4.667l-6.333,4.833l-2.167,4.333l-2.167,-1.5l-4.167,2l-2.333,-0.667l-3,-4l-15.667,16l3.833,3.667l1.333,3.167l6.167,0.333l4.333,-2.167l3.332,3.001z" stroke="#FFFFFF" fill="#B9B9B9" id="연천군"/>
       <path title="포천시" d="m80.583,87.939l4.5,-0.167l3.667,-1l2.5,0.667l5.167,-3.333l2.667,-14.167l3.833,0l1.167,-8.167l5.833,-1.833l0.333,-6.167l4.667,-6.333l1.667,-4.167l2.5,-0.833l-1.667,-10.167l-1.667,-1.167l-1.167,1.667l-5.5,-2l-3.667,2.667l-1.5,1.5l-2,-3.5l-4.167,-1l1.5,-8.5l-2.167,-1.333l-7.333,6.333l-4.167,-3.833l0,8l-0.333,7.333l-4,-0.833l-3.667,2.833l0.167,2.333l2.667,1.833l0.5,5l-1.667,2l-4.667,-0.333l-0.167,6.333l4.667,4.333l2,3.167l-0.333,4l-2.167,0l-2.333,2.167l1.667,6.5l-1.333,5.5l3.167,2.833l0.833,1.834z" stroke="#FFFFFF" fill="#B9B9B9" id="포천시"/>
       <path title="구리시" d="m84.417,116.605l1.5,-3.5l-4,-1.667l-1.167,-6.667l-2.167,1.167l-3,-0.333l1.833,4.167l0.167,3.667l-2,2l1.667,2.833l3.5,-2l3.667,0.333z" stroke="#FFFFFF" fill="#B9B9B9" id="구리시"/>
       <path title="남양주시" d="m99.75,84.939l5,3.667l2.167,4.833l1,4.167l2.167,2.5l-0.667,4.5l-3.833,4.667l-0.333,6.833l-4.333,6.167l0.167,3.5l-2.667,0.167l-3.333,-4l-3.333,-3.5l-4,-5l-1.833,-0.333l-4,-1.667l-1.167,-6.667l-2.167,1.167l-3,-0.333l-1.167,-1.833l-0.5,-5.833l4.167,-5.167l0.5,-2l2,-2.833l5.167,-0.167l3,-1l2.5,0.667l5,-2.833l3.498,0.331z" stroke="#FFFFFF" fill="#B9B9B9" id="남양주시"/>
       <path title="하남시" d="m80.417,131.439l3.167,0.667l2.5,-1.5l6,0.333l1.5,1l1.667,-2.333l-0.5,-2.667l3.667,-1l-3.333,-4l-3,-2.833l-4.333,-5.667l-1.833,-0.333l-1.5,3.5l0.833,1.667l0.333,2.5l-3.833,2.833l-0.333,2l1.167,3l-2.169,2.833z" stroke="#FFFFFF" fill="#B9B9B9" id="하남시"/>
       <path title="양평군" d="m116.917,101.605l-3.833,2.333l-3.667,0.667l-3.833,4.667l-0.333,6.833l-4.333,6.167l0.167,3.5l3.667,-3.167l2.667,0.167l2.667,3.333l1.167,4.833l1,1.833l-1.5,1.833l-1.667,0.5l4,5.667l3.167,-3.667l5.833,2.833l4.5,-2.5l4,3.833l5,0.167l3.167,-1l4,1.5l4.167,-0.5l4,1.333l0.167,3l0.667,2.333l6,0l5.5,-10.667l-1.333,-6.333l-4.667,-3.333l7.333,-6.833l4.334,-1.167l-6.334,-5l-4,-1.667l-8,0l-12.333,-8.167l-6.167,3l-0.167,6.5l-4,1l-5.667,0l-1,-6.167l-3.5,-4.833l-0.836,-2.831z" stroke="#FFFFFF" fill="#B9B9B9" id="양평군"/>
       <path title="가평군" d="m119.917,46.939l-0.833,-4.5l-2.5,0.833l-1.667,4.167l-4.667,6.333l-0.333,6.167l-5.833,1.833l-1.167,8.167l-3.833,0l-2.833,14.667l3.5,0.333l5,3.667l2.167,4.833l1,4.167l2,3.5l-0.5,3.5l3.667,-0.667l3.833,-2.333l0.833,2.833l3.5,4.833l1,6.167l5.667,0l4,-1l0.167,-6.5l-2.833,-4.333l2.333,-1.167l0.5,-9.667l-4.833,0.667l0.333,-3.833l2.833,-3.333l-2.5,-4.5l1.833,-2.833l-0.833,-4.667l6.667,-6.333l2.833,0l1,-5.667l-2.167,-2.833l-0.333,-3.667l-8,-0.333l0.333,-6.333l-8.667,-0.667l-0.667,-1.501z" stroke="#FFFFFF" fill="#B9B9B9" id="가평군"/>
       <path title="여주시" d="m118.25,150.605l0,-5.167l-5.167,-4.667l3.167,-3.667l5.833,2.833l4.5,-2.5l4,3.833l5,0.167l3.167,-1l3.333,1.667l4.833,-0.667l4,1.333l0.167,3l0.667,2.333l6,0l0.334,8.5l-1.5,10.667l-1.667,8l-5.833,9.5l-7.333,-3.667l-5.5,-4.833l-2.5,2.5l-5.167,-0.5l0.333,-2.833l-0.167,-6.167l1.5,-2.167l-0.833,-4.333l1.833,-3.167l-1.833,-2.667l-2.833,-3.5l-2,-0.833l-3,-2.333l-3.334,0.335z" stroke="#FFFFFF" fill="#B9B9B9" id="여주시"/>
       <path title="광주시" d="m104.917,163.272l4.833,-7l2.5,-0.333l6,-5.333l0,-5.167l-5.167,-4.667l-4,-5.667l1.667,-0.5l1.333,-1.167l-0.833,-2.5l-1.167,-4.833l-2.667,-3.333l-2.667,-0.167l-3.667,3.167l-3.167,0.333l-3.167,0.833l0.5,2.667l-1.667,2.333l-1.5,-1l-6,-0.333l-1,1.333l1,1.667l2.167,4.333l-1.667,3.5l-2,2.833l-0.667,3.5l-4.5,4l0,2.333l2.5,-0.333l4,-2.5l1.167,1.833l2.833,0.667l0.5,-3.667l5,1.833l3.167,0.833l-0.167,11l6.503,-0.498z" stroke="#FFFFFF" fill="#B9B9B9" id="광주시"/>
       <path title="성남시" d="m68.583,139.439l-2,8l6.167,3.167l4.333,3.5l2.333,0l0,-2.333l4.5,-4l0.667,-3.5l3.667,-6.333l-3.5,-6.5l-1.167,0.667l-3.167,-0.667l-1.667,2l-3,0.167l-4.167,5.833l-2.5,-1l-0.499,0.999z" stroke="#FFFFFF" fill="#B9B9B9" id="성남시"/>
       <path title="과천시" d="m60.917,142.605l7.167,-2.5l1,-1.667l-1.667,-4l-6.167,-0.5l-2.833,3.833l0,2.333l2.5,2.501z" stroke="#FFFFFF" fill="#B9B9B9" id="과천시"/>
       <path title="의왕시" d="m59.583,145.272l-1.333,5.333l-2.5,5.667l-1,2.167l3.5,0.5l1,-3.167l3.167,-1.833l2.833,-2.833l1.333,-3.667l1.5,-7.333l-6.667,2.5l-1.333,0.833l-0.5,1.833z" stroke="#FFFFFF" fill="#B9B9B9" id="의왕시"/>
       <path title="용인시" d="m67.417,152.939l-2.167,-1.833l1.333,-3.667l6.167,3.167l4.333,3.5l4.833,-0.333l4,-2.5l1.167,1.833l2.833,0.667l0.5,-3.667l8.167,2.667l-0.167,11l6.5,-0.5l-0.167,6.833l1.333,3.5l4.333,1.667l0.167,2.833l3.833,0.167l1.333,3.5l1.833,1.667l-1.667,3.833l-0.833,3.833l-3.833,-1l-5,-0.333l-2.833,-2.333l-2.167,1.667l-4.333,-4l-1.667,-2.167l-1.167,3.5l-4.667,3l-2.833,3.5l-5.167,-1.333l-4.333,-2.167l0.5,-4.5l2.5,-4.667l2.833,-2l-1.5,-5.667l-8.167,-0.667l-1.833,-2.333l2.667,-4.833l-2.5,-1.667l2.333,-2.667l-3.167,-0.667l-0.667,-2.167l-2.167,-0.667l-0.496,-3.999z" stroke="#FFFFFF" fill="#B9B9B9" id="용인시"/>
       <path title="안양시" d="m55.25,146.939l-2.5,0l-2.333,2.667l-3,-4.667l1,-3.667l2.167,-3.5l3.167,-2l2.5,2.167l2.167,-0.167l0,2.333l2.5,2.5l-0.833,0.833l-1.667,5.667l-3.168,-2.166z" stroke="#FFFFFF" fill="#B9B9B9" id="안양시"/>
       <path title="부천시" d="m40.917,132.105l-0.5,1.833l-5.167,0.167l-3.833,-3.833l-0.333,-4l2.167,-1.5l0.667,-5.333l6.333,2.5l1.5,4.833l-2,1.667l1.166,3.666z" stroke="#FFFFFF" fill="#B9B9B9" id="부천시"/>
       <path title="광명시" d="m42.583,134.605l-2.167,-0.667l0.5,-1.833l3,-1.167l2.5,-1.667l1.333,1.833l2.833,6.667l-2.167,3.5l-0.667,1.667l-3.667,-0.5l-0.833,-6.833l-0.665,-1z" stroke="#FFFFFF" fill="#B9B9B9" id="광명시"/>
       <path title="시흥시" d="m27.583,148.439l-4,4.5l4.667,3.5l4.333,-3.5l5,-0.5l3.5,-2.833l5.667,-1.333l1,-2.167l-0.333,-1.167l0.333,-2l-3.667,-0.5l-1,-6.333l-0.5,-1.5l-2.167,-0.667l-5.167,0.833l-5.667,10.833l-1.999,2.834z" stroke="#FFFFFF" fill="#B9B9B9" id="시흥시"/>
       <path title="이천시" d="m127.25,196.605l1,-5.167l-2.833,-4.833l-4,-0.833l-1.667,-2.5l-2.167,0.167l-1.833,-1.667l-1.333,-3.5l-3.833,-0.167l-0.333,-2.333l-4.167,-2.167l-1.333,-3.5l0.167,-6.833l4.833,-7l2.5,-0.333l6,-5.333l3.333,-0.333l2.333,2.167l2.667,1l4.667,6.167l-1.833,3.167l0.833,4.333l-1.5,2.167l0,6.833l-0.167,2.167l5.167,0.5l2.5,-2.5l5.5,4.833l0.333,9.5l-3.5,1.167l0.167,2.667l-2.167,-0.833l-2.167,0.5l-0.833,4.833l-2.5,-1.333l-3.834,-1.003z" stroke="#FFFFFF" fill="#B9B9B9" id="이천시"/>
       <path title="수원시" d="m55.375,160.355l-1.5,2.625l0.5,2.5c0,0 2.5,0.375 2.5,0.75s3.25,4.625 3.25,4.625l6,-0.25l2,-1.875l3.292,0.875l2.667,-4.833l-2.5,-1.667l2.333,-2.667l-3.167,-0.667l-0.667,-2.167l-2.167,-0.667l-0.5,-4l-2.042,-1.333l-2.625,2.125l-3.75,2.375l-0.75,2.833l-2.75,-0.333l-0.124,1.751z" stroke="#FFFFFF" fill="#B9B9B9" id="수원시"/>
       <path title="군포시" d="m54.75,158.439l-3.75,-1.709l-3.375,-1.25l1.5,-5.875l1.25,-0.375l2.125,-2l2.75,-0.292l3.167,2.167l-0.167,1.5l-3.5,7.834z" stroke="#FFFFFF" fill="#B9B9B9" id="군포시"/>
       <path title="안산시" d="m28.25,156.439l5.625,3.042l5.875,0.375l1.5,-0.125l2.75,3.875l5.625,-2l4.25,1.375l1.5,-2.625l0.125,-1.75l-7.5,-3.125l1.125,-5.875l1.25,-0.375l-2.625,-3.125l-1,2.167l-5.667,1.333l-3.083,2.874l-5.417,0.458l-4.333,3.501z" stroke="#FFFFFF" fill="#B9B9B9" id="안산시"/>
       <path title="안산시" d="m15.625,167.73l-1.625,-2.375l-3.125,-0.5l-1.625,-2.75l-4.5,-0.625l2.25,4l1,3l-2.5,1.375l0.125,5.375l3,0.125l2.625,-4l3.75,2.75l-0.125,-3.875l1.25,-1.125l-0.5,-1.375z" stroke="#FFFFFF" fill="#B9B9B9"/>
       <path title="오산시" d="m69,185.98l5.5,-0.5l1.375,-1.75l-0.5,-2.125l-2.5,-0.375l-1.5,-3.625l-1.25,-2.375l-4.875,0l-2.375,4.125l3.625,2.375l0.25,3l2.25,1.25z" stroke="#FFFFFF" fill="#B9B9B9" id="오산시"/>
       <path title="평택시" d="m52.625,220.48l10.125,-5.125l4.875,1.625l6.5,-2.875l3.25,-4.25l2.75,0l0.875,-3.625l1,-1.125l-0.25,-2.125l0.25,-1.5l-5.5,-1.375l3.125,-3.625l-2.375,-1.375l0.75,-5.125l-0.75,-0.875l0,-4.25l-1.375,-1.125l-1.375,1.75l-5.125,0.375l-2.625,-1.125l-3.75,1.875l-1.625,7.75l-6.125,1.375l-6.125,-0.625l-5.5,7.875l-5.375,0.75l-1.75,1.875l5.375,1.625l1.375,5.5l6.75,4l1.5,3.75l1.125,0l0,0z" stroke="#FFFFFF" fill="#B9B9B9" id="평택시"/>
       <path title="안성시" d="m99.375,221.48l-4.75,-4.125l-4.25,-0.375l-3.25,-4.25l-7,-2.875l0.875,-3.625l1,-1.125l-0.5,-2.625l0.5,-1l-5.5,-1.375l3.125,-3.625l-2.375,-1.75l0.75,-4.75l3.417,1.625l5.167,1.333l2.542,-3.583l4.958,-2.917l1.167,-3.5l2,2.417l4,3.75l2.167,-1.667l3.208,2.667l4.625,0l3.833,1l0.833,-3.833l1.708,-3.417l2.125,-0.583l1.667,2.5l4.333,1.083l2.5,4.583l-1,5.167l-7,1.375l0,3.75l-3.75,3l-4.75,0.75l-0.25,3.875l1.5,1.875l-3.75,3.375l-5.875,0l-2.625,4.875l-1.375,2z" stroke="#FFFFFF" fill="#B9B9B9" id="안성시"/>
       <path title="화성시" d="m41.375,162.98l-4.125,0.125l-1.125,3.125l-0.875,2.5l-3,-2.75l-1.625,0.375l-3.625,-0.375l-1.75,-0.875l-2.375,0.75l-2.25,2.875l1.75,2.75l-2,4.875l-0.375,3l0.125,2.625l4,-0.375l-1.375,3.375l1.875,3.125l4,-3.125l3.125,-4.125l4.5,0l1.75,-2.375l-0.125,4.125l1.75,1l-1.625,2.375l-5.25,0.5l0.25,2.875l0,2.625l-0.125,4.125l-2.625,2.5l0,2.25l6.875,1.125l1.125,1.75l5.375,-0.75l5.5,-7.875l6.125,0.625l6.5,-1.25l1.25,-7.875l3.75,-1.875l-0.25,-3l-3.625,-2.375l2.375,-4.125l4.875,0l1.25,2.375l1.5,3.625l2.625,0l0.375,2.5l1.708,1.208l2.5,-4.667l2.833,-2l-1.5,-5.667l-8.167,-0.667l-1.833,-2.333l-3.292,-0.5l-2,1.5l-6,0.25l-3.25,-4.625l-2.5,-0.75l-0.5,-2.5l-4.25,-1.375l-5.625,2l-2.624,-0.624z" stroke="#FFFFFF" fill="#B9B9B9"/>
      </g>

    </svg>

  </div>


  <div class="select-box">
    <h3 class="title">지역명으로 보기</h3>
    <ul>
      <li><a href="#가평군">가평군<span> (0)</span></a></li>
      <li><a href="#고양시">고양시<span> (0)</span></a></li>
      <li><a href="#과천시">과천시<span> (0)</span></a></li>
      <li><a href="#광명시">광명시<span> (0)</span></a></li>
      <li><a href="#광주시">광주시<span> (0)</span></a></li>
      <li><a href="#구리시">구리시<span> (0)</span></a></li>
      <li><a href="#군포시">군포시<span> (0)</span></a></li>
      <li><a href="#김포시">김포시<span> (0)</span></a></li>
      <li><a href="#남양주시">남양주시<span> (0)</span></a></li>
      <li><a href="#동두천시">동두천시<span> (0)</span></a></li>
      <li><a href="#부천시">부천시<span> (0)</span></a></li>
      <li><a href="#성남시">성남시<span> (0)</span></a></li>
      <li><a href="#수원시">수원시<span> (0)</span></a></li>
      <li><a href="#시흥시">시흥시<span> (0)</span></a></li>
      <li><a href="#안산시">안산시<span> (0)</span></a></li>
      <li><a href="#안성시">안성시<span> (0)</span></a></li>
      <li><a href="#안양시">얀양시<span> (0)</span></a></li>
      <li><a href="#양주시">양주시<span> (0)</span></a></li>
      <li><a href="#양평군">양평군<span> (0)</span></a></li>
      <li><a href="#여주시">여주시<span> (0)</span></a></li>
      <li><a href="#연천군">연천군<span> (0)</span></a></li>
      <li><a href="#오산시">오산시<span> (0)</span></a></li>
      <li><a href="#용인시">용인시<span> (0)</span></a></li>
      <li><a href="#의왕시">의왕시<span> (0)</span></a></li>
      <li><a href="#의정부시">의정부시<span> (0)</span></a></li>
      <li><a href="#이천시">이천시<span> (0)</span></a></li>
      <li><a href="#파주시">파주시<span> (0)</span></a></li>
      <li><a href="#평택시">평택시<span> (0)</span></a></li>
      <li><a href="#포천시">포천시<span> (0)</span></a></li>
      <li><a href="#하남시">하남시<span> (0)</span></a></li>
      <li><a href="#화성시">화성시<span> (0)</span></a></li>
    </ul>

    <div class="text-right"><i class="fa fa-refresh fa-spin fa-2x fa-fw" aria-hidden="true"></i></div>
  </div>

  <div class="clearfix"></div>
  <?php //echo get_paging(10, 1, 10, G5_URL, ''); ?>

  <div class="location-board-wrap">
    <table class="location-board">
      <caption class="hidden">센터 위치</caption>
      <colgroup>
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
      </colgroup>
      <thead>
        <tr>
          <th>NO</th>
          <th>기관명</th>
          <th>학력 인정</th>
          <th>전화번호</th>
          <th>주소</th>
          <th>홈페이지</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td colspan="6">센터를 선택해주세요.</td>
        </tr>
      </tbody>
    </table>

    <div class="list-page text-center">
				<ul class="pagination en no-margin"></ul>
		</div>

    <div class="loading" style="display: none;">
      <div class="table"><div class="tcell"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div></div>
    </div>

  </div>
</div>

<script>
$(function(){
  <?php
    $rs1 = sql_query("SELECT location, count(location) as cnt FROM gle_location WHERE deleted = 'N' GROUP BY location");
    $list= array();
    while($row = sql_fetch_array($rs1)){
      $list[$row['location']] = $row['cnt'];
    }
  ?>

  function get_location(){
    var location_list = new Array();
        location_list = <?php echo json_encode($list, JSON_UNESCAPED_UNICODE); ?>;

        for( var i in location_list){
          $('.select-box a[href=#' + i + ']').find('span').text(' (' + location_list[i] + ')');
        }
    $('.select-box .text-right').fadeOut(300);
  }

  get_location();

  $(document).on('click', 'svg path:not(.active)', function(e){

    $('path.active').attr('class','');
    var id = $(this).attr('id');
    $('path#' + id).attr('class', 'active');

    $('.select-box li.active').attr('class','');
    $('a[href=#' + id + ']').closest('li').attr('class','active');

    get_station(id);

  })
  

  $(document).on('click', '.location-board-wrap .list-page a', function(e){
    // 페이징 클릭할 때,
    e.preventDefault();

    if(!$(this).attr('href')){
      return false;
    }
    var active = $('.select-box li.active a').attr('href');
        active = active.replace('#','');
    var href   = $(this).attr('href').trim();
    //var posit  = href.indexOf('&page=');
    //var num    = href.substr(posit + 6, 1);
    get_station(active, href);
  })

  $(document).on('click', '.select-box li a', function(e){

    e.preventDefault();

    var id = $(this).attr('href');
    id = id.replace('#','');

    $('path.active').attr('class','');
    $('path#' + id).attr('class', 'active');

    $('.select-box li.active').attr('class','');
    $('a[href=#' + id + ']').closest('li').attr('class','active');

    get_station(id);


  })

  $('path#가평군').attr('class','active');
  $('a[href=#가평군]').closest('li').attr('class','active');
  get_station('가평군');

  $('svg path').tooltipster({
    plugins : ['follower'],
    delay : 0
  });

 

})

function get_station(id, page){

  var start = (page != 'undefined') ? page : 1;

  $('.loading').fadeIn(300);

  var id = (id != 'undefined') ? id : '';
  if (id.length < 1) {
    console.log('no id value. error');
    return false;
  }

  $.ajax({
    url : './getStation.php',
    type : 'POST',
    data : {'id' : id, 'page' : start},
    success : function(data){
      //console.log(data);

      var Data = JSON.parse(data);

      $('.location-board tbody').html(Data.html);
      $('.pg_wrap').detach();
      $('.location-board-wrap .list-page ul').html(Data.paging);
      $('.loading').fadeOut(300);
    },
    error : function(msg){
      console.log(msg);
      alert('에러가 발생했습니다. 잠시 후 다시 시도해주세요. 에러가 계속된다면 관리자에게 문의해주세요.');
      $('.loading').fadeOut(300);
    }
  })

}
</script>
