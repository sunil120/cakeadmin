<p class="login-box-msg">Forgot Password</p>
<div class="clearfix">
    <?php
    if (isset($error) && !empty($error)) {
        echo $this->element('flash/error', array("message" => $error));
    }

    echo $this->CustomForm->create('User',array('novalidate'));
    ?>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input(
                'UserModel.email', array('placeholder' => 'Email')
        );
        ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?php
            echo $this->CustomForm->button('Submit', array('class' => 'btn-info'));
            ?>
        </div>
        <div class="col-xs-4">
            <a class="btn btn-flat btn-default"
               href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>"
               title="Cancel">Cancel</a>
        </div>
        <!-- /.col -->
    </div>

    <?php
    echo $this->CustomForm->end();
    ?>
</div>