function mid_slider($id, $option){

  /*
   * 일단 PC일 경우 이미지 600px로 고정시키자.
   *
   *
   */

  if($id == 'undefined'){
    $id = ('.slider-visible');
  }

  if($option == 'undefined'){
    $option = {};
  }

  this.vis    = $($id).css('width', 610);
  this.box    = this.vis.find('.mid-slider');
  this.ul     = this.box.find('ul');
  this.autoplay = $option['autoplay'] ? true : false; // 자동재생 사용여부
  this.sec    = $option['interval'] ? $option['interval'] : 5000; //인터벌 시간
  this.speed  =  $option['speed'] ? $option['speed'] : 500; //스피드

  this.clone  = this.ul.find('li').clone(true).addClass('clone');
  this.clone2 = this.ul.find('li').clone(true).addClass('clone');
  this.clone3 = this.ul.find('li').clone(true).addClass('clone');
  this.clone4 = this.ul.find('li').clone(true).addClass('clone');
  this.clone5 = this.ul.find('li').clone(true).addClass('clone');
  this.ul.append(this.clone);
  this.ul.append(this.clone2);
  this.ul.append(this.clone3);
  this.ul.append(this.clone4);
  this.ul.append(this.clone5);


  // pc, mobile
  this.pc = 1;

  // this.ul.find('li').eq(0).appendTo(this.ul);
  this.img     = this.ul.find('img');
  this.imgLeng = this.img.length;

  var _this = this;


  // 첫번째 요소가 중앙에 올때까지 위치를 섞는다.
  (function() {
    var bo = false;
    while (!bo) {
      if (_this.ul.find('li').eq(3).hasClass('whatson0')) {
        bo = true;
      }
      else {
        _this.ul.find('li').eq(0).appendTo(_this.ul);
      }
    }
  })();


  //_this.play();

  $(window).resize(function(){
    _this.winWidth = parseInt($(window).outerWidth());
    if(_this.winWidth < 650){
      _this.setSize();
    }else{
      _this.play();
    }
  })

  $(window).load(function(){
    
    _this.winWidth = parseInt($(window).outerWidth());
    if(_this.winWidth < 650){
      _this.setSize();
    }else{
      _this.play();
    }

  })


  if (_this.autoplay) {
    _this.setInt = setInterval(function(){_this.left()},_this.sec);
  }

  $(document).on('click','.mid-slider-caption .btn_right', function(e){
    if (_this.autoplay) {
      clearInterval(_this.setInt);
      _this.setInt = setInterval(function(){_this.left()},_this.sec);
    }
    _this.left();
  })

  $(document).on('click','.mid-slider-caption .btn_left', function(e){
    _this.right();
    if (_this.autoplay) {
      clearInterval(_this.setInt);
      _this.setInt = setInterval(function(){_this.left()},_this.sec);
    }
  })

  // var ox;
  // var nx;
  // $(document).on('touchstart', '.mid-slider ul', function(e){
  //   ox = e.originalEvent.touches[0].clientX;
  // })
  // $(document).on('touchmove', '.mid-slider ul', function(e){
  //   var event = e.originalEvent;
  //   nx = event.touches[0].screenX; //position, 39
  //   if(ox > nx){
  //     //left
  //   }else{
  //     //right
  //   }
  //   $(this).css('margin-left' : )
  // })


}

mid_slider.prototype.play = function(){
  var _this = this;
  _this.pc = 1;

  var img0 = parseInt(_this.img.eq(0).width()) + 10;
  _this.vis.width(img0).height('auto');
  _this.img.width('auto').height('auto').css('max-width',600).css('max-heigth', 338);


  // 이미지의 너비를 조정한 후에 박스의 너비 조정
  _this.ul.find('li').css('margin-right',10);
  _this.box_width = _this.getWidth();
  //_this.box.width = 
  _this.box.width(_this.box_width).css('margin-left', '-300%');

  _this.setCaption();

  // 모든 작업이 끝나면 항목 노출
  _this.vis.css('overflow' , 'visible').find('img').addClass('visible');
  //_this.ul.find('img').show(0);



}

mid_slider.prototype.getWidth = function(){
  var _this = this;

  var totalWidth = 0;

  /*_this.vis.find('img').each(function(){
    totalWidth += parseInt($(this).outerWidth()) + 10;
  })*/

  // img 각각을 일일히 계산하기 보다는, 한 개로 연산.
  if(_this.pc == 1){
    totalWidth = 610 * _this.imgLeng;
  }else{
    totalWidth = _this.imgLeng * (parseInt(_this.ul.find('li:nth-child(1) img').outerWidth()) + 10);
  }

  return totalWidth;

}

mid_slider.prototype.left = function(){
  var _this = this;

  var imgLeft = parseInt(_this.ul.find('img').eq(0).width()) + 10;
  if(_this.pc == 0){
    imgLeft -= 10;
  }

  _this.box.stop(false,true).animate({
    'margin-left' : '-=' + imgLeft
  }, _this.speed , 'swing', function(){
    _this.box.find('li').eq(0).appendTo(_this.ul);
    _this.box.css('margin-left','-300%');
    _this.setCaption();
  })

}

mid_slider.prototype.right = function(){
  var _this = this;

  var imgLeft = parseInt(_this.ul.find('img').eq(0).width()) + 10;
  if(_this.pc == 0){
    imgLeft -= 10;
  }

  _this.box.find('li').last(0).prependTo(_this.ul);
  _this.box.css('margin-left','-400%');

  _this.box.stop().animate({
    'margin-left' : '+=' + imgLeft
  }, _this.speed , 'swing', function(){
    _this.setCaption();
  })
}

mid_slider.prototype.setSize = function(){
  var _this = this;
  _this.pc = 0;

  _this.img.outerWidth(_this.winWidth - 20);
  _this.ul.find('li').css('margin-right',0)

  _this.vis.outerWidth(_this.winWidth - 20);
  _this.vis.outerHeight(parseInt(_this.img.eq(0).height()));

  _this.box_width = _this.getWidth();
  _this.box.width(_this.box_width).css('margin-left', '-300%');
  _this.box.find('img')

  _this.vis.css('overflow' , 'visible').find('img').addClass('visible');  

}

mid_slider.prototype.setCaption = function(){
  var _this = this;

  var this_li  = _this.ul.find('li:nth-child(4) img');
  var caption  = this_li.attr('caption1');
  var caption2 = this_li.attr('caption2');

  if(caption.length < 1){
    caption = " ";
  }
  if(caption2.length < 1){
    caption2 = " ";
  }

  _this.vis.next('.mid-slider-caption').find('p.title').text(caption);
  _this.vis.next('.mid-slider-caption').find('p.sub').text(caption2);
}




