
<style>
    body{
        background: #2c3e50;
    }
</style>

<div class="denglu_box" style="position: relative">
    <img style="width: 80px;height: 80px;position: absolute;left: 80px;top:-32px" src="/images/user_icon_06.png" alt="">
    <h3 style="color: white;margin-left: 60px;">中钥财富登录</h3>

    <?php $form = self::beginForm(['showLabel' => false, 'id' => 'loginform']) ?>
    <ul style="padding-top: 20px;">
        <li>
            <input type="text" placeholder="请输入用户名" name="User[username]" autocomplete="off">
        </li>
        <li>
            <input type="password" placeholder="请输入登录密码" name="User[password]" autocomplete="off">
        </li>
        <!--<li>
          <input type="text" placeholder="验证码">
          <img src="images/code.png" alt="" class="code">
        </li>-->
        <input style="padding: 0px;" type="button" value="登 录" class="login_btn">
    </ul>
    <?php self::endForm() ?>
    <!--  <div class="clearfix tishi">-->
    <!--    <a href="--><?//= url('site/register') ?><!--" class="fl">没有账号，点此注册</a>-->
    <!--    <a href="--><?//= url('site/forget') ?><!--" class="fr">忘记密码？</a>-->
    <!--  </div>-->
</div>


<script type="text/javascript">
  //function loginset(obj, datajson) {
  //    layer.alert("登录成功", function() {
  //        document.location = "<?//= url('web/loginsucc') ?>//";
  //    })
  //}

  $(function () {
    $(".login_btn").click(function () {
//                                $("#loginForm").ajaxSubmit($.config('ajaxSubmit', {
//                                    success: function (msg) {
//                                        if (!msg.state) {
//                                            return $.alert(msg.info);
//                                        } else {
//                                            window.location.href = msg.info;
//                                        }
//                                    }
//                                }));
      $.ajax({
        url: 'login',
        type: 'post',
        dataType: "json",
        data: $('#loginform').serialize(),
        success: function (msg) {
//                                        console.log(msg);return;
          if (msg['state']) {
            window.location.href = msg['info'];
          } else {
            if (msg['info'] instanceof Object) {
              alert(msg.info[Object.keys(msg.info)[0]])
            } else {
              alert(msg['info'])
            }
          }
        }
      });
      return false;
    });
  });

</script>
