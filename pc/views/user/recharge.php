<div class="wrap1 clearfix houtai_content">
    <?= $this->render('../layouts/menu', compact('current_position')) ?>

  <div id="page1" class="hover">
    <h3 class="mx clearfix">※ 账户充值
      <a href="javascript:;" class="fr jilu" id="page2Btn">充值记录></a>
    </h3>
    <div class="cz_mx_content">
      <div class="yue_money">
        <span class="tg">账户余额：</span>
        <span>
							￥
							<strong class="money"><?= u()->account - u()->blocked_account ?></strong>
							元
						</span>
      </div>
      <form action="<?= url('user/pay') ?>" method="post" id="form">
      <div>
        <span class="tg">请输入充值金额：</span>
        <input class="inpt_money" name="money" id="inpMoney" value="99.99" type="text" placeholder="请输入10元以上充值金额">
          <input type="hidden" id="type" name="type">
        <span>元</span>
      </div>


      <div class="select_cz_fs clearfix">
        <span class="fl tg">选择快捷充值金额：</span>
        <div class="fl clearfix jine_select" id="priceBtn">
          <span class="am-u-sm-3 on">99.99</span>
          <span class="am-u-sm-3">999.99</span>
          <span class="am-u-sm-3">2999.99</span>
          <span class="am-u-sm-3">4999.99</span>
          <span class="am-u-sm-3">9999.99</span>
          <span class="am-u-sm-3">19999.99</span>
          <span class="am-u-sm-3">29999.99</span>
          <span class="am-u-sm-3">59999.99</span>
        </div>
      </div>
	  
	  
	  
	  <div>
        <span class="tg ssw">在线充值：</span>
        <div class="lefts">
          <a style="display: none" class="zhifubao" href="javascript:;" onclick="formSubmit(1);">快捷支付</a>
            <a class="zhifubao" href="javascript:;" onclick="formSubmit(5);">支付宝</a>
          <a class="zhifubao" href="javascript:;" onclick="formSubmit(2);">支付宝支付(通道二)</a>

            <a style="display: none"  class="zhifubao" href="javascript:;" onclick="formSubmit(3);">线下转账</a>
            <a style="display: none" class="zhifubao" href="javascript:;" onclick="formSubmit(4);">微信支付</a>
            <a style="" class="zhifubao" href="javascript:;" onclick="formSubmit(6);">微信支付（通道二）</a>
        </div>
   
      </div>

      <!--<input type="submit" value="提交" class="tj_cz">-->
      </form>
      <p class="clearfix bz">
        <a href="<?= url(['user/new-gui']) ?>" class="red">【新手入金帮助】</a>
        <a href="javascript:;" class="fr">如遇到充值问题，请联系客服！</a>
      </p>
    </div>
  </div>
  <div id="page2" class="hover" style="display: none">
    <h3 class="mx">※ 资金明细 <a href="javascript:;" class="fr jilu" id="backBtn">充值></a></h3>
    <div class="zj_mx_content">
      <table class="table_mx">
        <thead>
        <th>支付方式</th>
        <th>金额（元）</th>
        <th>账号信息</th>
        <th>状态</th>
        </thead>
          <?php foreach ($list as $k => $v) : ?>
        <tr>
          <td><?= $v->getTypeValue() ?></td>
          <td><?= $v->money ?></td>
          <td><?= $v->info ?></td>
          <td><?= $v->getStatusValue() ?></td>
        </tr>
          <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript" src="/pc/js/layer.js"></script>


<script type="text/javascript">
  !function () {
    var priceBtn = $('#priceBtn .am-u-sm-3');
    var inpMoney = $('#inpMoney');
    priceBtn.on('click', function () {
      var num = $(this).text();
      inpMoney.val(num);
    });

    var typeBtn = $('.lefts .zhifubao');
    var dataInput = $('#type');
    typeBtn.on('click', function () {
      $(this).siblings('a').removeClass('on');
      $(this).addClass('on');
      dataInput.val($(this).attr('data-type'));
    });


    var payWayOne = $('.payWayOne');
    payWayOne.find('.noCheck').show();
    payWayOne.find('.Check').hide();
    payWayOne.on('click', function () {
      var _this = $(this);
      payWayOne.find('.noCheck').show();
      payWayOne.find('.Check').hide();
      _this.find('.Check').show();
      _this.find('.noCheck').hide();
    });

    var page2Btn = $('#page2Btn');
    var page1 = $('#page1');
    var page2 = $('#page2');
    var backBtn = $('#backBtn');
    page2Btn.on('click', function () {
      page1.hide();
      page2.show();
    });

    backBtn.on('click', function () {
      page2.hide();
      page1.show();
    });

  }()
  
  
  function formSubmit(type) {
		  
		  
		  amount = $("input[name='money']").val();
		  
		  if(type==901 || type==903 || type==904)
		  {
			  
			//alert(type);
			
			window.location.href="/zhpay/pay.php?uid=<?=u()->id?>&user=<?=u()->username?>&type="+type+"&amount="+amount;
			  
			  
		  }else{
		  
		  
        $('#type').val(type);
        $('#form').submit();
		
		  }
		
      }
  
</script>
