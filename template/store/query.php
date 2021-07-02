<?php
if (!defined('IN_CRONLITE')) die();

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>有异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退单</font>';
	else
		return '<font color=blue>待处理</font>';
}

if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)==17 && is_numeric($data))
	{
	   $sql=" A.tradeno='{$data}'"; 
	}else{
	   $sql=" A.input='{$data}'";
	}
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
}
else $sql=" A.userid='{$cookiesid}'";

$q_status=isset($_GET['status'])?trim(daddslashes($_GET['status'])):"";
if(isset($q_status) && $q_status != ""){
	$qu_status = intval($q_status);
	$sql .= " AND A.status = '{$qu_status}'";
}
$limit = 10;
$start = $limit * ($page-1);

$total = $DB->getColumn("SELECT count(*) FROM `pre_orders` A WHERE{$sql} ");
$total_page = ceil($total/$limit);
$sql = "SELECT A.*,B.`name`,B.`shopimg` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
while($res = $rs->fetch()){
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'money'=>$res['money'],'name'=>$res['name'],'shopimg'=>$res['shopimg'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}
$qqlink = 'https://wpa.qq.com/msgrd?v=3&uin='.$conf['kfqq'].'&site=qq&menu=yes';
if($is_fenzhan && !empty($conf['kfwx']) && file_exists(ROOT.'assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png')){
	$qrcodeimg = './assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png';
	$qrcodename = '微信';
}elseif(!empty($conf['kfwx']) && file_exists(ROOT.'assets/img/wxqrcode.png')){
	$qrcodeimg = './assets/img/wxqrcode.png';
	$qrcodename = '微信';
}else{
	$qrcodeimg = '//api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data='.urlencode($qqlink);
	$qrcodename = 'QQ';
}
?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $conf['sitename'].($conf['title']==''?'':' - '.$conf['title'])  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/detail.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
	<?php echo str_replace('body','html',$background_css)?>
</head>
<style>
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    body{
        width: 100%;
        max-width: 650px;
        margin: auto;
    }
    .fui-tab.fui-tab-primary a.active {
        color: #1492fb;
        border-color: #1492fb;
    }

    .qt-header {
        height: 10vh;
        background: #11998e;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #38ef7d, #11998e);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #38ef7d, #11998e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        line-height: 10vh;
    }

    .qt-header > input {
        height: 5vh;
        width: 100%;
        border: none;
        text-indent: 2.5em;
        line-height: 5vh;
        border-radius: 0.5em;
        font-size: 0.7rem;
    }

    .qt-header > span {
        position: absolute;
        margin-left: 0.6rem;
        font-size: 0.7rem;
    }

    .qt-card {
        box-shadow: 0px 0px 6px #eee;
        border-radius: 0.5em;
    }

    .qt-card img {
        width: 6em;
        max-width: 100%;
        height: 6em;
        border-radius: 0.5em;
        box-shadow: 3px 3px 16px #eee;
    }

    .qt-btn {
        border-radius: 0.5em;
        border: solid 1px #eee;
    }
td.stitle{max-width:380px;}
td.wbreak{max-width:420px;word-break:break-all;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
#orderItem .btn{height: 100%;margin: 0;}
#orderItem .orderContent img{max-width:100%}
a, a:focus, a:hover, a:active {
    outline: none;
    text-decoration: none;
}
.btn.btn-primary-o {
    color: #1492fb;
    border: 1px solid #1492fb;
}
.elevator_item {
    position: fixed;
    right: 5px;
    bottom: 95px;
    z-index: 11;
}
.elevator_item .feedback {
    width: 36px;
    font-size: 12px;
    padding: 5px 6px;
    display: block;
    border-radius: 5px;
    text-align: center;
    margin-top: 10px;
    box-shadow: 0 1px 2px rgba(0,0,0,.35);
    cursor: pointer;
}
.graHover {
    position: relative;
    overflow: hidden;
}
.toplan div .choose {
    border-radius: .3rem;
}
</style>


<body>
<div id="body" style="width: 100%;max-width: 650px">
    <div class="fui-page-group statusbar" style="max-width: 650px;left: auto;overflow: hidden;">
        <div class="layui-row layui-col-space6">
            <div class="layui-card" style="    background-color: unset;box-shadow: unset;">
                <div class="pro_content" style="margin-bottom: 3.5rem;">
                    <div class="list_item_box" style="top: 53px;">
                        <div class="bor_detail">
                            <div class="thumb" id="layer-photos-demo" class="layer-photos-demo">
                                <img alt="<?php echo $tool['name']?>" layer-src="<?php echo $qrcodeimg ?>"  src="<?php echo $qrcodeimg ?>">
                            </div>
                            <div class="pro_right fl">
                                <span id="level">客服ＱＱ：<?php echo $conf['kfqq'] ?> <a href="<?php echo $qqlink ?>" target="_blank">[点击添加]</a></span>
                                <span class="list_item_title">客服微信：<?php echo $conf['kfwx']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
<div id="tab" class="fui-tab fui-tab-danger">
        <a data-tab="tab" class="external <?php if(isset($q_status) && $q_status === ""){echo "active";} ?>" onclick="window.location.href='?mod=query&data=<?php echo $data; ?>'">全部</a>
        <a data-tab="tab0" class="external <?php if($q_status === '0'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=0&data=<?php echo $data; ?>'">待处理</a>
        <a data-tab="tab1" class="external <?php if($q_status === '2'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=2&data=<?php echo $data; ?>'">处理中</a>
        <a data-tab="tab2" class="external <?php if($q_status === '1'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=1&data=<?php echo $data; ?>'">已完成</a>
        <a data-tab="tab3" class="external <?php if($q_status === '3'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=3&data=<?php echo $data; ?>'">异常中</a>
        <a data-tab="tab3" class="external <?php if($q_status === '4'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=4&data=<?php echo $data; ?>'">已退单</a>
<!--         <a data-tab="tab3" class="external <?php if($q_status === '3'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=3'">异常</a> -->
</div>
<?php if($record){ ?>
        <div class="elevator_item" id="elevator_item" style="display:block;">
            <a class="feedback graHover" style="background-color: #FF3399;color:#fff;" onclick="$('.tzgg').show()" rel="nofollow">查询<br>说明</a>
        </div>
        <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div>
        <div class="tzgg" type="text/html" style="display: none">
            <div class="account-layer" style="z-index: 100000000;">
                <div class="account-main" style="padding:0.8rem;height: auto">

                    <div class="account-title">订单状态说明</div>

                    <div class="account-verify"
                         style="  display: block;    max-height: 15rem;    overflow: auto;margin-top: -10px">
                        <?php echo $conf['gg_search'] ?>
                    </div>
                    

                    <div class="account-close" onclick="$('.tzgg').hide()">
                        <i class="icon icon-guanbi1"></i>
                    </div>
                </div>

            </div>
        </div>

        <div class="layui-card-body" style="padding: 1em;padding-bottom: 3em;overflow:hidden;overflow-y: auto;height: 80vh;">
            <div class="layui-tab-item layui-show" id="order_all">
<?php foreach($record as $row){?>
<div class="layui-card qt-card">
              <div class="layui-card-header">
               <p style="width: 70%" class="layui-elip"><?php echo $row['name']?></p>
                    <span class="layui-layout-right layui-elip" style="width:30%;text-align: right;margin-right: 0.5em">
                          <?php echo display_zt($row['status'])?>
                    </span>
                       </div>
                        <div class="layui-card-body">
                       <div class="layui-row layui-col-space10">
                            <div class="layui-col-xs4">
                                <a href="?mod=buy&tid=<?php echo $row['tid']?>">
                                <img src="<?php echo $row['shopimg']?>" onerror="this.src='assets/store/picture/error_img.png'">
                                </a>
                            </div>
                       <div class="layui-col-xs8" style="font-size: 0.8em;color:black;font-family: '微软雅黑'">
                            下单信息：<?php echo $row['input']?><br>
                            下单时间：<?php echo $row['addtime']?><br>
							订单编号：<?php echo $row['id']?><br>
                      </div>
                  <div style="width: 100%;text-align: right" class="showorders">
                       <button class="layui-btn qt-btn layui-btn-sm layui-btn-primary xiangqing" data-id="<?php echo $row['id']?>" data-skey="<?php echo $row['skey']?>" onclick="showOrder(<?php echo $row['id']?>,'<?php echo $row['skey']?>')">
                             查看详情
                      </button>
                      <?php if($row['djzt'] == 3){ ?>
                       <button class="layui-btn qt-btn layui-btn-sm layui-btn-danger" onclick="window.location.href='?mod=faka&id=<?php echo $row['id']?>&skey=<?php echo $row['skey']?>'">
                             提取卡密
                      </button>
                      <?php } ?>
               </div>
         </div>
    </div>
</div>
<?php }?>
							</div>
<div class="layui-tab-item layui-show" id="order_all" style="margin-top: 5px;">
<?php if($page>1){?>
	<button class="layui-btn layui-btn-sm layui-btn-normal" onclick="LastPage()">
		上一页
	</button>
<?php }
if($total_page!=$page){?>
	<button class="layui-btn layui-btn-sm layui-btn-normal pull-right" onclick="NextPage()">
		下一页
	</button>
<?php }?>
</div>

<?php }else{ ?>
<div class="fui-content navbar order-list">
    <div class="fui-content-inner">
        <div class="content-empty" style="">
        	<img src="./assets/store/picture/nolist.png" style="width: 6rem;margin-bottom: .5rem;"><br>
        	<?php if($_GET['data']){ ?>
	            <p style="color: #999;font-size: .75rem">没有查询到数据</p>
        	<?php }else{ ?>
	            <p style="color: #999;font-size: .75rem">您暂时没有任何订单哦！</p>
	            <br>
	            <a href="./" class="btn btn-sm btn-primary-o" style="border-radius: 100px; height: 1.9rem; line-height: 1.4rem; width: 7rem; font-size: 0.75rem;">去首页逛逛吧</a>
            <?php } ?>

        </div>
    </div>
</div>	
<?php } ?>
<!--                             <?php if ($conf['gg_search'] != '') { ?>
                                <div style="width: 100%;min-height: 3em;padding: 1em;box-shadow: 0px 0px 16px #eee;margin-top: 1em;border-radius: 0.5em;margin-bottom: 1em;">
                                    <?php echo $conf['gg_search'] ?>
                                </div>
                            <?php } ?> -->

                        </div>
            </div>
        </div>

        <div class="fui-navbar" style="max-width: 650px;z-index: 100;">
            <a href="./" class="nav-item  "> <span class="icon icon-home"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">购物首页</span>
            </a>
            <a href="javascript:void(0);" class="nav-item active"> <span class="icon icon-dingdan1"></span> <span
                        class="label" style="color:#999;line-height: unset;font-weight: inherit;">订单售后</span> </a>
			<a href="./?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">购物车</span> </a>
            <a href="./?mod=articlelist" class="nav-item "> <span class=" icon icon-service1"></span> <span class="label" style="color:#999;line-height: unset;font-weight: inherit;">更多资源</span>
            </a>
            <a href="./user/" class="nav-item "> <span class="icon icon-person2"></span> <span
                        class="label" style="color:#999;line-height: unset;font-weight: inherit;">会员中心</span> </a>
        </div>

    </div>
</div>
<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnserver ?>assets/store/js/query.js"></script>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
});
layer.tips('点击图片长按识别添加微信客服', '#layer-photos-demo', {
  tips: [3, '#78BA32']
});
var hashsalt=<?php echo $addsalt_js?>;
function goback()
{
    document.referrer === '' ?window.location.href = './' :window.history.go(-1);
}
</script>
</body>
</html>