<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
?>

<div data-role="page" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>快讯</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<ul class="companylist" data-role="listview" data-inset="true" data-icon="false">
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont">知名房企祥生集团启动大规模招聘计划，详见招聘页</div>
					<div class="time"><span>2016/11/22</span></div>
				</li>
				<li>
					<h3><i></i>武汉</h3>
					<div class="txt-cont">武汉楼市限购再升级：本地人限购第三套，外地人限购第二套，新政将于11月15日起开始施行。</div>
					<div class="time"><span>2016/11/15</span></div>
				</li>
				<li>
					<h3><i></i>政策</h3>
					<div class="txt-cont">11月10日起，杭州实施进一步住房限购、上调住房公积金贷款和商业性住房贷款首付比例、暂停发放第三套及以上住房贷款、加强对首付资金来源审核、加强对土地竞买资金来源审查。</div>
					<div class="time"><span>2016/11/10</span></div>
				</li>
				<li>
					<h3><i></i>土拍</h3>
					<div class="txt-cont">杭州余杭区良渚一宗宅地出让，北大资源以26.26亿元竞得了余政储出（2016）40号地块，折合楼面价16578元/平，溢价率3.61%。</div>
					<div class="time"><span>2016/11/03</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont">杭州宝龙城市广场11月6日举行大型招聘会</div>
					<div class="time"><span>2016/11/02</span></div>
				</li>
				<li>
					<h3><i></i>土拍</h3>
					<div class="txt-cont">宋都溢价90.67%夺大江东新湾商住地</div>
					<div class="time"><span>2016/11/01</span></div>
				</li>
				<li>
					<h3><i></i>意见</h3>
					<div class="txt-cont">10月31日，国家发展改革委发布了《关于加快美丽特色小(城)镇建设的指导意见》(下称《意见》)，全文共四千余字，《意见》强调，建设特色小镇应建立在以产业为依托的基础上，从实际出发，防止照搬照抄。浙江杭州的云栖小镇成为了特色小镇的典型案例。</div>
					<div class="time"><span>2016/11/01</span></div>
				</li>
				<li>
					<h3><i></i>退房</h3>
					<div class="txt-cont">长沙商品房新版合同征意见：买房人悔约，卖方15日内退全款</div>
					<div class="time"><span>2016/10/30</span></div>
				</li>
				<li>
					<h3><i></i>万科</h3>
					<div class="txt-cont">2016杭州万科城市乐跑赛10月28日在杭州奥体中心沿江公园开跑，吸引近4000人报名。</div>
					<div class="time"><span>2016/10/28</span></div>
				</li>
				<li>
					<h3><i></i>万达</h3>
					<div class="txt-cont">杭州第二座万达广场，余杭万达广场北至工业路，南至文一西路，东至规划道路，西至城东路，或2018年开业</div>
					<div class="time"><span>2016/10/27</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont">立元集团招聘高级投资经理，主营投资、房产。工作地点杭州 简历投递至 30018523#qq.com 注明来源圈内觅</div>
					<div class="time"><span>2016/10/26</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont"><a href="http://events.the1stmedia.com/H5-all/sun2/index.jsp" target="_blank">阳光城集团杭州公司2017届营销精英招聘启动  点击查看详情</a></div>
					<div class="time"><span>2016/10/25</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont"><a href="http://mp.weixin.qq.com/s?__biz=MjM5MzY3MzYzOQ==&mid=2650550858&idx=1&sn=319612a5d1402c3ff963b85e8fc6f06b&chksm=be9bea8289ec63947945ec1e8c314547ef1c3695f867b56f8f1c733cc8e64c933cb1cb13c8c8&mpshare=1&scene=1&srcid=1019xei55biBm3cGFwgLJcc5#rd" target="_blank">德信集团2017校园招聘正式启动 点击查看详情</a></div>
					<div class="time"><span>2016/10/24</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont"><a href="http://bgy.zhiye.com/dckf?p=3^48" target="_blank">碧桂园印尼区域招聘（营销） 市场营销 / 事务 / 人力 / 策略 / 创作 / 前策产品 | 点击查看</a></div>
					<div class="time"><span>2016/10/24</span></div>
				</li>
				<li>
					<h3><i></i>活动</h3>
					<div class="txt-cont"><a href="http://hi.house.sina.com.cn/house/zjntbyd/baoming.php?dpc=1" target="_blank">有跑出来的美丽，没有等出来的辉煌，和洪秋华一起参加10月29日中节能西溪首座毅行活动.  点击报名</a></div>
					<div class="time"><span>2016/10/21</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont">浙江日报《招商引资》工作室诚聘：内容采编两名。驻点：萧山区、钱江世纪城、空港新城、湘湖新城，服务全国。提供食宿、双休，实习期满（三个月），薪资待遇面议，表现好的可聘为浙报正式员工！联系电话:18058176159马立峰</div>
					<div class="time"><span>2016/10/21</span></div>
				</li>
				<li>
					<h3><i></i>交流</h3>
					<div class="txt-cont">人的共享-共享经济的魂————中国（浙江）人力资源服务博览会在杭州洲际酒店杭州厅召开。</div>
					<div class="img-cont"><img src="/images/news/news_20161020.jpg" alt=""></div>
					<div class="time"><span>2016/10/20</span></div>
				</li>
				<li>
					<h3><i></i>招聘</h3>
					<div class="txt-cont">筑家易杭州招聘销售总监，策划等以及其它各种岗位。简历发送 620098#qq.com。（注明来自圈内觅）</div>
					<div class="time"><span>2016/10/19</span></div>
				</li>
			</ul>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
})
</script>
<?php $this->endBlock();  ?>