<p class="login-box-msg">Sign in to start your session</p>
<div class="clearfix">
    <?php
    $msg = $this->Session->flash('auth');
    if($msg):
        echo $this->element('flash/error', array("message" => $msg));
    elseif($this->Session->check('Message.flash')):
         echo $this->Session->flash();          
    endif;
    echo $this->CustomForm->create('UserModel',array('novalidate'));?>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input(
                'user_name', array('placeholder' => 'User Name')
        );
        ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input(
                'password', array('placeholder' => 'Password')
        );
        ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?php echo $this->CustomForm->button('Sign In', array('class' => 'btn-info')); ?>
        </div>
        <!-- /.col -->
    </div>
    <?php
    echo $this->CustomForm->end();
    echo $this->html->link('Forgot Password ?', array('controller' => 'users', 'action' => 'forgotpassword'));
    ?>
</div>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>