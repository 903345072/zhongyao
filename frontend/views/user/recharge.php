
		<div class="main_heads">
      <img src="/wap/img/icons_03.png" onclick="javascript:history.back()">
			<h1><span>充值</span></h1>
<!--			<a href="#">充值记录</a>-->
		</div>
    <form action="<?= url('user/pay') ?>" method="post" id="form">
		<div class="list_pay">
			<input type="text" name="money" id="money" value="999.99" placeholder="请输入10元以上的充值金额"/>
			<ul id="priceBtn">
				<li class="on" data-mode="999.99">999.99元</li>
				<li data-mode="1999.99">1999.99元</li>
				<li data-mode="2999.99">2999.99元</li>
				<li data-mode="4999.99">4999.99元</li>
				<li data-mode="9999.99">9999.99元</li>
				<li data-mode="19999.99">20000元</li>
			</ul>
			<div class="bot_btn">
				<div class="fl" onclick="formSubmit(2)">
					<img src="/wap/img/alipay.jpeg"/>
					<p>支付宝支付</p>
				</div>

                <div style="margin-left: 15px;display: none" class="fl" onclick="formSubmit(1)">
                    <img src="/wap/img/banl_03.jpg"/>
                    <p>快捷支付</p>
                </div>


                <div style="margin-left: 10px;display: none" class="fl" onclick="formSubmit(3)">
                    <img src="/wap/img/saoma.jpg"/>
                    <p>线下转账</p>
                </div>
                <div style="margin-left: 10px;" class="fl" onclick="formSubmit(4)">
                    <img src="/wap/img/weixinsaoma.jpg"/>
                    <p>微信支付</p>
                </div>

        <div style="clear: both;"></div>
<!--        <div class="zfb" onclick="formSubmit(3)">-->
<!--          <img src="/wap/img/banl_03.jpg"/>-->
<!--          <p>银行卡支付</p>-->
<!--        </div>-->
			</div>
		</div>
      <input type="hidden" id="type" name="type" value="1">
    </form>
<!--		<a href="#"><div class="btns">确认支付</div></a>-->
		<div class="null"></div>
    <?= $this->render('../layouts/_footer') ?>
    <script type="text/javascript">
      $(function(){
        var priceBtn = $('#priceBtn li');
        var inpMoney = $('#money');
        priceBtn.on('click', function () {
          var num = $(this).attr('data-mode');
          inpMoney.val(num);
        });

      })
      function formSubmit(type) {
        $('#type').val(type);
        $('#form').submit();
      }
    </script>
