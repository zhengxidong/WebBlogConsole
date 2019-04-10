jQuery(document).ready(function($) {

    //wow
    if( suxingme_url.wow ){
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 100,
            mobile: true,
            live: true
        });
        wow.init();
    }


    if( suxingme_url.headfixed ){
        $("div.navbar-fixed-top").autoHidingNavbar();
    }

    if( suxingme_url.roll ){

        $(".sidebar .widget:last").stick_in_parent({
            parent : "#page-content",
            offset_top:95
        });

    }

    switch( suxingme_url.slidestyle ){
        case 'index_slide_sytle_1' :
            var owl = $('.top-slide');
            owl.owlCarousel({
                items: 1,
                loop:true,
                animateOut: 'fadeOut',
                autoplay:true,
                autoplayTimeout:3000,
                responsive:{
                    768:{
                      items:1
                    }
                }
            });
            break;
        case 'index_slide_sytle_2' :
            var owl = $('.top-slide-two');
            owl.owlCarousel({
                items: 1,
                loop:true,
                animateOut: 'fadeOut',
                autoplay:true,
                autoplayTimeout:3000,
                nav : true,
                navText:'',
                responsive:{
                    768:{
                      items:1
                    }
                }
            });
            break;
        case 'index_slide_sytle_3' :
            var owl = $('.top-slide-three');
            owl.owlCarousel({
                items:1,
                loop:true,
                margin:10,
                autoplay:true,
                autoplayTimeout:3000,
                responsive: {
                    768 : {
                        items: 1,
                        margin: 0,
                    },
                    992 : {
                        items: 2,
                        margin: 20,
                        center: true,
                        autoWidth:true,
                        nav : true,
                        navText:'',
                    }
                }
            });
            break;
        case 'index_slide_sytle_4' :
            var owl = $('.top-slide-two');
            owl.owlCarousel({
                items: 1,
                loop:true,
                animateOut: 'fadeOut',
                autoplay:true,
                autoplayTimeout:3000,
                nav : true,
                navText:'',
                responsive:{
                    768:{
                      items:1
                    }
                }
            });
                break;
        default:
            break;
    }

    $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
    $(document).on('click', '#comments-navi a',
    function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            beforeSend: function() {
                $('#comments-navi').remove();
                $('.commentlist').remove();
                $('#loading-comments').slideDown()
            },
            dataType: "html",
            success: function(out) {
                result = $(out).find('.commentlist');
                nextlink = $(out).find('#comments-navi');
                $('#loading-comments').slideUp(550);
                $('#loading-comments').after(result.fadeIn(800));
                $('.commentlist').after(nextlink);
                $('.commentlist .avatar').lazyload({
                    event: 'scrollstop',
                });
            }
        })
    })

    /*
    -------------------------
    SHARE
    -------------------------
    */

    $('.J_showAllShareBtn').click(function(){
    	$('.bdsharebuttonbox').slideToggle(300);
        $('.panel-reward').toggle(false)
    });

    /*
    -------------------------
    reward
    -------------------------
    */

    $('.pay-author').click(function(){
        $('.panel-reward').slideToggle(300);
        $('.bdsharebuttonbox').toggle(false)
    });


    /*
    -------------------------
    LIKE
    -------------------------
    */

	$.fn.postLike = function() {
	 // if ($(this).hasClass('current')) {
   //   alert("您已经赞过啦:-)");
	 // return false;
	 // }
   // else
   // {
  	 $(this).addClass('current');
  	 var id = $(this).data("id"),
  	 action = $(this).data('action'),
  	 rateHolder = $(this).children('.count');
  	 var ajax_data =
     {
    	 action: "suxing_like",
    	 um_id: id,
    	 um_action: action
  	 };
     //console.log(suxingme_url.url_ajax);
  	 $.post(suxingme_url.url_ajax, ajax_data,
  	 function(data)
     {
        //如果一天内已经赞过
        if(data.error == 1)
        {
          alert("您已经赞过啦:-)");
        }
        else {
          console.log(data);
    	    $(rateHolder).html(data);
        }

  	 });
  	 return false;
	 //}
	};


	$(document).on("click", "#Addlike",
	function()
  {
	   $(this).postLike();
	});

    /*
    -------------------------
    SEARCH
    -------------------------
    */

    $('.js-toggle-search').on('click', function () {
        $('.search-form').toggleClass('is-visible');
        $("html").addClass("overflow-hidden");
    });
    $('.close-search').click(function(){
        $(".search-form").removeClass("is-visible");
        $("html").removeClass("overflow-hidden");
    });


     /*
    -------------------------
    WEIXIN BOOM
    -------------------------
    */

    $('#tooltip-s-weixin').on('click', function () {
        $('.f-weixin-dropdown').toggleClass('is-visible');
    });
    $('#tooltip-f-weixin').on('click', function () {
        $('.f-weixin-dropdown').toggleClass('is-visible');
    });
    $(".close_tip").click(function() {
        $(".f-weixin-dropdown").toggleClass('is-visible');
    });
    $('.f-weixin-dropdown').click(function(){
        $(this).removeClass("is-visible");

    });


    /*
    -------------------------
    toTop
    -------------------------
    */

    !function(o){"use strict";o.fn.toTop=function(t){var i=this,e=o(window),s=o("html, body"),n=o.extend({autohide:!0,offset:420,speed:500,position:!0,right:38,bottom:38},t);i.css({cursor:"pointer"}),n.autohide&&i.css("display","none"),n.position&&i.css({position:"fixed",right:n.right,bottom:n.bottom}),i.click(function(){s.animate({scrollTop:0},n.speed)}),e.scroll(function(){var o=e.scrollTop();n.autohide&&(o>n.offset?i.fadeIn(n.speed):i.fadeOut(n.speed))})}}(jQuery);
    $(function() {
        $('.to-top').toTop();
     });
    $('body').append('<a class="to-top"><i class="icon-up-small"></i></a>');

    /*
    -------------------------
    MAIN NAV
    -------------------------
    */

    $(".nav-menu ul.main-nav li:has(>ul)").addClass("has-children");

    if($(".nav-menu ul.main-nav li").hasClass("has-children")){
        $(".nav-menu ul.main-nav li.has-children").prepend('<span class="toggle-submenu"></span>')
    }

    $('.burger-menu').click(function(){
        $("html").addClass("overflow-hidden");
        $("body").addClass("overflow-hidden");
        $(".nav-menu").addClass("toggle-nav");
        $(".body-overlay").addClass("show-overlay");
        $(".search-form").removeClass("is-visible");
    });
    $('.body-overlay').click(function(){
        $("html").removeClass("overflow-hidden");
        $("body").removeClass("overflow-hidden");
        $(".nav-menu").removeClass("toggle-nav");
        $(".body-overlay").removeClass("show-overlay");
        $(".nav-menu ul.main-nav li").removeClass("active");
        if($(".nav-menu ul.main-nav li ul").hasClass("opened")){
            $(".nav-menu ul.main-nav li ul").removeClass("opened").slideUp(200);
        }
    });
    $('.close-nav').click(function(){
        $("html").removeClass("overflow-hidden");
        $("body").removeClass("overflow-hidden");
        $(".nav-menu").removeClass("toggle-nav");
        $(".body-overlay").removeClass("show-overlay");
        $(".nav-menu ul.main-nav li").removeClass("active");
        if($(".nav-menu ul.main-nav li ul").hasClass("opened")){
            $(".nav-menu ul.main-nav li ul").removeClass("opened").slideUp(200);
        }
    });
    $('.nav-menu ul.main-nav li span').click(function(){
        if($(this).siblings('ul').hasClass('opened')){
            $(this).siblings('ul').removeClass('opened').slideUp(200);
            $(this).closest('li').removeClass('active');
        }
        else{
            $(this).siblings('ul').addClass('opened').slideDown(200);
            $(this).closest('li').addClass('active');
        }
    });


});


document.addEventListener('DOMContentLoaded', function(){
   var aluContainer = document.querySelector('.comment-form-smilies');
    if ( !aluContainer ) return;
    aluContainer.addEventListener('click',function(e){
    var myField,
        _self = e.target.dataset.smilies ? e.target : e.target.parentNode,
        tag = ' ' + _self.dataset.smilies + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
            myField = document.getElementById('comment')
        } else {
            return false
        }
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = tag;
            myField.focus()
        } else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            var cursorPos = endPos;
            myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
            cursorPos += tag.length;
            myField.focus();
            myField.selectionStart = cursorPos;
            myField.selectionEnd = cursorPos
        } else {
            myField.value += tag;
            myField.focus()
        }
    });
});

jQuery(document).on("click", ".facetoggle", function($) {
    jQuery(".comment-form-smilies").toggle();
});
// 大于等于20条，则显示加载更多
jQuery(document).ready(function(){
  var articleList = $('.ajax-load-box').children().length;
  if(articleList >= 20)
  {
    $('#fa-loadmore').css('display','inline-block');
  }
})

// 加载更多
jQuery(document).on("click", "#fa-loadmore", function($) {
    var _self = jQuery(this),
        _postlistWrap = jQuery('.posts-con'),
        _button = jQuery('#fa-loadmore'),
        _data = _self.data();
        console.log(_postlistWrap)
    if (_self.hasClass('is-loading')) {
        return false
    } else {
        _button.html('<i class="icon-spin6 animate-spin"></i> 加载中...');
        _self.addClass('is-loading');
        var but = document.getElementById("fa-loadmore");
        jQuery.ajax({
            url: suxingme_url.url_ajax,
            data: _data,
            type: 'post',
            dataType: 'json',
            success: function(data) {

                if (data.code == 500) {
                  //console.log(data.code)
                    _button.data("paged", data.next).html('加载更多');
                    alert('服务器正在努力找回自我  o(∩_∩)o')
                }
                else if (data.code == 200) {

                  //判断是否为空数据
                  console.log(data.postlist);
                  if(data.postlist == 0)
                  {
                    but.style.display = "none";
                    var footer = document.getElementById('box');
                    footer.style.display="block";
                    //删除button
                    //var child=document.getElementById("fa-loadmore");
                    //child.parentNode.removeChild(child);
                    //添加div
                    //var node=document.createTextNode('');
                    //para.appendChild(node);

                    //but.innerHTML = '我是有底线的！！';
                  }
                  else {
                        //console.log(data.code)
                          console.log(data);
                          _postlistWrap.append(data.postlist);
                          if( jQuery.isFunction(jQuery.fn.lazyload) ){
                              jQuery("img.lazy,img.avatar").lazyload({ effect: "fadeIn",});
                          }

                          //console.log(div);
                          var paged = but.dataset.paged;//获取data-appid的值
                          var total = but.dataset.total;//获取data-myname的值
                          but.dataset.paged = data.next;
                          but.dataset.total = data.total;

                          if (data.next) {
                              if( suxingme_url.wow ){
                                  var btn = new WOW({
                                      boxClass: 'button-more',
                                      animateClass: 'animated',
                                      offset: 0,
                                      mobile: true,
                                      live: true
                                  });
                                  btn.init();
                              }
                              //_button.data('paged',data.next);
                              //_button.data('total',data.total);
                              _button.data("paged", data.next).html('加载更多')
                          } else {
                              _button.hide()
                      }

                      //改变data-paged的值
                      //$("#fa-loadmore").data("data-paged",data.next);
                      //改变data-total的值
                      //$("#fa-loadmore").data("data-total",data.total);
                  }
                  _self.removeClass('is-loading')
                  }

            },
            error:function(data){
                console.log(data.responseText);
                console.log(data);
            }
        })
    }
});


// jQuery(document).on("click", ".post-nav span", function($) {
//     var _self = jQuery(this),
//         _postlistWrap = jQuery('.posts-con'),
//         _button = jQuery('#fa-loadmore'),
//         _data = _self.data();
//     if (_self.hasClass('is-loading')) {
//         return false
//     } else {
//         _postlistWrap.html('<div class="wait-tips"><i class="icon-spin6 animate-spin"></i> 加载中...</div>');
//         _self.addClass('is-loading');
//         _self.addClass("current").siblings().removeClass("current");
//         _button.hide();
//         jQuery.ajax({
//             url: suxingme_url.url_ajax,
//             data: _data,
//             type: 'post',
//             dataType: 'json',
//             success: function(data) {
//                 if (data.code == 500) {
//                     _button.data("paged", data.next).html('加载更多');
//                     alert('服务器正在努力找回自我  o(∩_∩)o')
//                 } else if (data.code == 200) {
//                     _postlistWrap.html(data.postlist);
//                     if( jQuery.isFunction(jQuery.fn.lazyload) ){
//                         jQuery("img.lazy,img.avatar").lazyload({ effect: "fadeIn",});
//                     }
//                     if (data.next && _self.data("total") > 1) {
//                         _button.show();
//                         if( suxingme_url.wow ){
//                             var btn = new WOW({
//                                 boxClass: 'button-more',
//                                 animateClass: 'animated',
//                                 offset: 0,
//                                 mobile: true,
//                                 live: true
//                             });
//                             btn.init();
//                         }
//                         _button.data("paged", data.next).html('加载更多');
//                         if( _self.hasClass("new-post") ){
//                            _button.data("home", true);
//                         } else {
//                             _button.removeAttr("data-home");
//                             _button.data("category",_self.data("category"));
//                             _button.data("total",_self.data("total"));
//                         }
//                     } else {
//                         _button.hide()
//                     }
//                 }
//                 _self.removeClass('is-loading')
//             },
//             error:function(data){
//                 console.log(data.responseText);
//                 console.log(data);
//             }
//         })
//     }
// });
