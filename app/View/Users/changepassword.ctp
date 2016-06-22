
<div class="clearfix">
    <div class="col-lg-9">
        <?php
        echo $this->CustomForm->create('User', array('type' => 'file',
            'inputDefaults' => array(
                'between' => '<div >',
                'after' => '</div>'
            ),
        ));
        ?>
        <?php echo $this->CustomForm->input('UserModel.id'); ?>
        
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">Old Password</label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->input('UserModel.old_password', array('type' => 'password', 'required' => 'required'), array()); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">New Password</label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->input('UserModel.new_password', array('type' => 'password', 'required' => 'required'), array()); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">Confirm Password</label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->input('UserModel.confirm_password', array('type' => 'password', 'required' => 'required'), array()); ?>
            </div>
        </div>
       <div class="form-group clearfix">
    <div class="button-section clearfix">
        <label class="col-sm-3 control-label" for=""></label>
        <div class="col-sm-6">
         <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
        <a class="btn btn-flat btn-default"
           href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
        </div>
       
    </div>
</div>
       
    </div>

</div>


<?php echo $this->CustomForm->end(); ?>