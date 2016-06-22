
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
            <label class="col-sm-4 control-label" ><span class="error-text">*</span>First Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('first_name',array('tabindex'=>'1')); ?>
            </div> 
        </div>
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" tabindex='4'>User Name</label>
            <div class="col-sm-8 form-user-name">
                <?php echo $this->CustomForm->input('user_name', array( 'tabindex'=>'4','type' => 'hidden')); ?>
                <?php echo $this->data['UserModel']['user_name']; ?>
            </div> 
        </div>
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" ><span class="error-text">*</span>Role</label>
            <div class="col-sm-8"> <div class="select-style">
                   <?php echo $this->CustomForm->input('UserModel.role_id', array(
                        'id' => 'role',
                        'options' => @$roles,
                        'empty' => 'Select Role',
                        array('class' => 'select2'),
                    ));
                    ?>
                </div> 
            </div> 
        </div>
        <?php if (!empty($editPermissionAccess)) { ?>
            <div class="form-group clearfix">
                <div class="col-sm-8 col-sm-offset-4">
                    <div class="text-link">
                        <?php echo $this->Html->link('Edit Permissions', '/permission/user/' . $this->request->data['UserModel']['id'],array('tabindex'=>9)); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4  control-label" >Middle Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('middle_name'); ?>
            </div> 
        </div>
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" ><span class="error-text">*</span>Email Id</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('email',array('tabindex'=>'5')); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4  control-label" ><span class="error-text">*</span>Last Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('last_name',array('tabindex'=>'3')); ?>
            </div> 
        </div>
        <div class="form-group clearfix" id="radio-control">
            <label class="col-sm-4 control-label">Status</label>
            <div class="col-sm-8" id="radio">
                <div class="row radio-inline status-radio">
                    <?php
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'id' => 'radio1',
                        'legend' => false,
                        'tabindex'=>'6'
                    ));
                    ?> </div>
            </div> 
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-12 col-sm-12">
<div class="form-group clearfix">
    <label class="col-sm-4 control-label button-section" ></label>
    <div class="col-sm-8">
        <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary', 'tabindex'=>'8')); ?>
        <a class="btn btn-flat btn-default"
           href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
    </div>
</div>
</div>

<?php echo $this->CustomForm->end(); ?>