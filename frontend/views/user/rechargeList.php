<div class="header">
  <p>充值记录</p>
  <img src="/wap/img/icons_03.png" onclick="javascript:history.back()">
</div>
<?php
$type = [1=>'支付宝',2=>'微信',3=>'银联',4=>'支付宝'];
?>
<div class="tables">
  <div class="tab_top">
    <ul>
      <li>充值类型</li>
      <li>充值金额（元）</li>
      <li>充值时间</li>

    </ul>
  </div>
  <div class="tab_bot">
    <ul>
        <?php foreach ($list as $k => $v) : ?>
          <li>
            <p><?= $type[$v->charge_type] ?></p>
            <span><?= $v->amount ?></span>
            <em><?= $v->updated_at ?></em>

          </li>
        <?php endforeach; ?>
    </ul>
  </div>
</div>
<div class="null"></div>
<?= $this->render('../layouts/_footer') ?>

