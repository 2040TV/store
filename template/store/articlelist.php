<?php
if(!defined('IN_CRONLITE'))exit();
$kw=!empty($_GET['kw'])?trim(daddslashes($_GET['kw'])):null;
if($kw){
	$sql=" title LIKE '%$kw%'";
	$link="&kw=".$kw;
}else{
	$sql=" 1";
}
$msgcount=$DB->getColumn("SELECT count(*) FROM pre_article WHERE active=1");
$pagesize=10;
$pages=ceil($msgcount/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);
$rs=$DB->query("SELECT id,title,content,addtime FROM pre_article WHERE{$sql} AND active=1 ORDER BY top DESC,id DESC LIMIT $offset,$pagesize");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
$class_show_num = intval($conf['index_class_num_style'])?intval($conf['index_class_num_style']):2; //分类展示几组
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 102.4px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title>蓝莓网 - 汇聚全网优质文章</title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
    <?php echo str_replace('body','html',$background_css)?>
</head>
<style type="text/css">
    body {
        position: absolute;;

        margin: auto;
    }
    .fui-page.fui-page-from-center-to-left,
    .fui-page-group.fui-page-from-center-to-left,
    .fui-page.fui-page-from-center-to-right,
    .fui-page-group.fui-page-from-center-to-right,
    .fui-page.fui-page-from-right-to-center,
    .fui-page-group.fui-page-from-right-to-center,
    .fui-page.fui-page-from-left-to-center,
    .fui-page-group.fui-page-from-left-to-center {
        -webkit-animation: pageFromCenterToRight 0ms forwards;
        animation: pageFromCenterToRight 0ms forwards;
    }
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    .fui-goods-item .detail .price .buy {
        color: #fff;
        background: #1492fb;
        border-radius: 3px;
        line-height: 1.1rem;
    }
    .fui-goods-item .detail .sale {
        height: 1.7rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 0.65rem;
        line-height: 0.9rem;
    }
    .goods-category {
        display: flex;
        background: #fff;
        flex-wrap: wrap;
    }

    .goods-category li {
        width: 25%;
        list-style: none;
        margin: 0.4rem 0;
        color: #666;
        font-size: 0.65rem;

    }

    .goods-category li.active p {
        background: #1492fb;
        color: #fff;
    }

    body {
        padding-bottom: constant(safe-area-inset-bottom);
        padding-bottom: env(safe-area-inset-bottom);
    }

    .goods-category li p {
        width: 4rem;
        height: 2rem;
        text-align: center;
        line-height: 2rem;
        border: 1px solid #ededed;
        margin: 0 auto;
        -webkit-border-radius: 0.1rem;
        -moz-border-radius: 0.1rem;
        border-radius: 0.1rem;
    }
    .footer ul {
        display: flex;
        width: 100%;
        margin: 0 auto;
    }

    .footer ul li {
        list-style: none;
        flex: 1;
        text-align: center;
        position: relative;
        line-height: 2rem;
    }

    .footer ul li:after {
        content: '';
        position: absolute;
        right: 0;
        top: .8rem;
        height: 10px;
        border-right: 1px solid #999;


    }

    .footer ul li:nth-last-of-type(1):after {
        display: none;
    }

    .footer ul li a {
        color: #999;
        display: block;
        font-size: .6rem;
    }
.fui-goods-group.block .fui-goods-item .image {
     width: 100%; 
     margin: unset; 
     padding-bottom: unset; 
     <?php if(checkmobile()){ ?>
        height:5.5rem;
     <?php }else{ ?>
        height:8rem;
     <?php } ?>
     

}
.layui-flow-more{
        width: 100%;
    float: left;
}
.fui-goods-group .fui-goods-item .image img{
    border-radius:5px;    
}
.fui-goods-group .fui-goods-item .detail .minprice {
    font-size: .6rem;
}
.fui-goods-group .fui-goods-item .detail .name{
    height: 1.9rem;
}

.swiper-pagination-bullet {
  width: 20px;
  height: 20px;
  text-align: center;
  line-height: 20px;
  font-size: 12px;
  color: #000;
  opacity: 1;
  background: rgba(0, 0, 0, 0.2);
}

.swiper-pagination-bullet-active {
  color: #fff;
  background: #ed414a;
}
.swiper-pagination{
    position: unset;
}
.swiper-container{
    --swiper-theme-color: #ff6600;/* 设置Swiper风格 */
    --swiper-navigation-color: #007aff;/* 单独设置按钮颜色 */
    --swiper-navigation-size: 18px;/* 设置按钮大小 */
}
.goods_sort {
    position: relative;
    width: 100%;

    -webkit-box-align: center;
    padding: .4rem 0;
    background: #fff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
}

.goods_sort:after {
    content: " ";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    border-bottom: 1px solid #e7e7e7;
}

.goods_sort .item {
    position: relative;
    width: 1%;
    display: table-cell;
    text-align: center;
    font-size: 0.7rem;
    border-left: 1px solid #e7e7e7;
    color: #666;
}
.goods_sort .item .sorting {
    width: .2rem;
    height: .2rem;
    position: relative;
}
.goods_sort .item:first-child {
    border: 0;
}

.goods_sort .item.on .text {
    color: #fd5454;
}
.goods_sort .item .sorting .icon {
    /*font-size: 11px;*/
    position: absolute;
    -webkit-transform: scale(0.6);
    -ms-transform: scale(0.6);
    transform: scale(0.6);
}

.goods_sort .item-price .sorting .icon-sanjiao1 {
    top: .15rem;
    left: 0;
}

.goods_sort .item-price .sorting .icon-sanjiao2 {
    top: -.15rem;
    left: 0;
}

.goods_sort .item-price.DESC .sorting .icon-sanjiao1 {
    color: #ef4f4f
}

.goods_sort .item-price.ASC .sorting .icon-sanjiao2 {
    color: #ef4f4f
}
.content-slide .shop_active .icon-title {
    color: #ff5555;
}
.xz {
    background-color: #3399ff;
    color: white !important;
    border-radius: 5px;
}
.tab_con > ul > li.layui-this{
    background: linear-gradient(to right, #73b891, #53bec5);
    color: #fff;
    border-radius: 6px;
    text-align: center;
}
#audio-play #audio-btn{width: 44px;height: 44px; background-size: 100% 100%;position:fixed;bottom:5%;right:6px;z-index:111;}
#audio-play .on{background: url('assets/img/music_on.png') no-repeat 0 0;-webkit-animation: rotating 1.2s linear infinite;animation: rotating 1.2s linear infinite;}
#audio-play .off{background:url('assets/img/music_off.png') no-repeat 0 0}
@-webkit-keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotating{from{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}
</style>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 650px;">
<div id="body">
    <div style="position: fixed;    z-index: 100;    top: 30px;    left: 20px;       color: white;    padding: 2px 8px;      background-color: rgba(0,0,0,0.4);    border-radius: 5px;display: none" id="xn_text">
    </div>
    <div class="fui-page-group " style="height: auto">
        <div class="fui-page  fui-page-current " style="height:auto; overflow: inherit">
            <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">
                <div class="default-items">
                    <div class="fui-swipe">
                        <style>
                            .fui-swipe-page .fui-swipe-bullet {
                                background: #ffffff;
                                opacity: 0.5;
                            }

                            .fui-swipe-page .fui-swipe-bullet.active {
                                opacity: 1;
                            }
                        </style>
                        <div class="fui-swipe-wrapper" style="transition-duration: 500ms;">
                            <?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item" href="' . $image_url[1] . '">
                                <img src="' . $image_url[0] . '" style="display: block; width: 100%; height: auto;" />
                            </a>';
                            }
                            ?>
                        </div>
                        <div class="fui-swipe-page right round" style="padding: 0 5px; bottom: 5px; ">
                        </div>
                    </div>
                    <div class="device">
                        <div class="swiper-container swiper-container-initialized swiper-container-horizontal">
                            <div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;" id="swiper-wrapper-c31a72d9cfb64373" aria-live="polite">
                                <div class="swiper-slide swiper-slide-visible swiper-slide-active" data-swiper-slide-index="1" style="margin-left: auto; margin-top: 0px; width: 650px;" role="group" aria-label="1 / 2">
                                        <div class="content-slide">
										<a href="https://lanmeidg.52dg.cn" class="get_cat">
                                               <div class="mbg">
                                                   <p class="ico"><img src="https://lanmeidg.52dg.cn/favicon.ico"></p>
                                                   <p class="icon-title">蓝莓代挂网</p>
                                              </div>
                                          </a>
										  </div>
                                        </div>                            </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0">1</span></div>
                                <div class="swiper-button-next" style="" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-c31a72d9cfb64373" aria-disabled="false"></div>
                                <div class="swiper-button-prev swiper-button-disabled" style="" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-c31a72d9cfb64373" aria-disabled="true"></div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                    </div>
                    <div class="fui-notice">
                        <div class="text" style="height: 1.2rem;line-height: 1.2rem">
                            <ul>
                                <li>
                                        <marquee behavior="alternate">
                                            <span style="color:red">❤️欢迎访问蓝莓网！❤️</span>
                                        </marquee>
                                    </li>
                            </ul>
                        </div>
                    </div>
                        <div class="fui-searchbar bar">
                            <div class="searchbar center searchbar-active" style="padding-right:2.5rem">
							<span class="searchbar-cancel searchbtn" id="doSearch">搜索</span>
                                <div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;">
                                    <i class="icon icon-search"></i>
									<input type="text" name="kw" value="" class="search" placeholder="输入文章关键词" onkeydown="if(event.keyCode==13){doSearch.click()}" required/>
                                </div>
                            </div>
                        </div>
		<div class="table-responsive">
<?php
foreach($msgrow as $row){
	 $content = strip_tags($row['content']);
	 if (mb_strlen($content) > 80)
		 $content = mb_substr($content, 0, 80, 'utf-8') . '......';
	echo '<a class="fui-goods-item" title="'.strip_tags($row['title']).'" href="'.article_url($row['id']).'"><div class="detail" style="height:unset;"><div class="name" style="color: #ff5555;"><p class="minprice">'.strip_tags($row['title']).'</p></div><div style="line-height:0.7rem;height:0.7rem;color:#b2b2b2;font-size:0.6rem;margin-top: .2rem;"></div><div class="price" style="margin-top: 0.2rem;"><span class="text" style="color: #000000;"> '.$content.'</span><div style="height: 1rem"><span class="buy" style="background-color: yellowgreen;color:#fff;display: inline-block;height: 1.1rem;line-height: 1rem;color: white;float: right;   padding: 0rem 0.35rem;width: 100%;border-radius: 0.1rem;   border: 1px solid transparent;text-align:center;">查看内容</span></div></div></div></a>';
}
if($msgcount==0){
	echo '<tr><td class="text-center"><font color="grey">文章列表空空如也</font></td></tr>';
}
?>			
		<?php if($msgcount>$pagesize){
		if($page>1){
			echo '<a href="'.article_url(0, 'page='.($page-1).$link).'" class="btn btn-default">上一页</a>';
		}
		if($page<$pages){
			echo '<a href="'.article_url(0, 'page='.($page+1).$link).'" class="btn btn-default pull-right">下一页</a>';
		}
		}?>
			</div>		

        <div class="fui-navbar" style="max-width: 650px;z-index: 100;">
            <a href="./" class="nav-item  "> <span class="icon icon-home"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">购物首页</span>
            </a>
            <a href="./?mod=query" class="nav-item "> <span class="icon icon-dingdan1"></span> <span
                        class="label" style="color:#999;line-height: unset;font-weight: inherit;">订单售后</span> </a>
			<a href="./?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">购物车</span> </a>
            <a href="javascript:void(0);" class="nav-item active"> <span class=" icon icon-service1"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">更多资源</span>
            </a>
            <a href="./user/" class="nav-item "> <span class="icon icon-person2"></span> <span
                        class="label" style="color:#999;line-height: unset;font-weight: inherit;">会员中心</span> </a>
        <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<?php echo $conf['footer']?>
<script>
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
$(document).ready(function(){
if($_GET['kw']){
	$("input[name='kw']").val(decodeURIComponent($_GET['kw']))
}
$("#doSearch").click(function () {
	var kw = $("input[name='kw']").val();
	window.location.href="./?mod=zy&kw="+encodeURIComponent(kw);
});
});
</script>
</body>
</html>