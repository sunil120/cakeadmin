<?php
echo $this->CustomForm->create('UserModel', array('type' => 'file',
    'inputDefaults' => array(
        'between' => '<div >',
        'after' => '</div>'
    ),
    'novalidate'
));
?>
<?php echo $this->CustomForm->input('id'); ?>

<div class="clearfix">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for=""><span class="error-text">*</span>First Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('first_name'); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">User Name</label>
            <div class="col-sm-8 form-user-name">
                <?php echo $this->data['UserModel']['user_name']; ?> 
            </div>
        </div>
        
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">Role</label>
            <div class="col-sm-8"> <div class="select-style">
                     <?php echo $this->data['RoleMaster']['role_name']; ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">Middle Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('middle_name'); ?>
            </div>
        </div>
       <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">Email Id</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('email', array('disabled' => 'disabled'), array()); ?>
            </div>
        </div>

    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">Last Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('last_name'); ?>
            </div>
        </div>
        <?php
        echo $this->CustomForm->input('user_name', array('type' => 'hidden', 'value' => $this->data['UserModel']['user_name']));
        ?>
        <div class="form-group clearfix">

            <label class="col-sm-4 control-label" for="">Status</label>

            <div class="col-sm-8" id="radio">
                <div class="row radio-inline status-radio">
                    <?php
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'label' =>array('class' => 'q'),
                        'disabled' => 'disabled',
                        'id' => 'radio1',
                        'legend' => false,
                    ));
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-12 col-sm-12">

    <div class="form-group clearfix button-section">
        <label class="col-sm-4 control-label" for=""></label>
        <div class="col-sm-8">
            <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
        <a class="btn btn-flat btn-default"
           href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
        </div>
    </div>

</div>
<?php echo $this->CustomForm->end(); ?>