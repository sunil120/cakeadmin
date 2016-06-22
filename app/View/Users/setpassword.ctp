<p class="login-box-msg">Set Password</p>
<div class="clearfix">
    <?php
    if (isset($error) && !empty($error)) {
        echo $this->element('flash/error', array("message" => $error));
    }

    echo $this->CustomForm->create('User');
    ?>
    <?php
    echo $this->CustomForm->create('User', array('type' => 'file',
        'inputDefaults' => array(
        ),
    ));
    ?>
    <?php echo $this->CustomForm->input('UserModel.id'); ?>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input('UserModel.new_password', array('placeholder' => 'New Password', 'type' => 'password', 'required' => 'required'), array());
        ?>
    </div>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input('UserModel.confirm_password', array('placeholder' => 'Confirm Password', 'type' => 'password', 'required' => 'required'), array());
        ?>
    </div>

    <?php
    foreach ($questions as $question) {
        $question_list[$question['id']] = $question['name'];
    }
    ?>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input('UserModel.security_question_id', array(
            'required' => 'required',
            'options' => $question_list,
            'empty' => 'Select Security Question'
        ));
        ?>
    </div>
    <div class="form-group has-feedback">
        <?php
        echo $this->CustomForm->input('UserModel.answer', array(
            'placeholder' => 'Answer',
            'required' => 'required'
        ));
        ?>
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