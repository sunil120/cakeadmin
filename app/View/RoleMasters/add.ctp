<div class="clearfix">
    <?php echo $this->CustomForm->create('RoleMaster',array('novalidate')); ?>
    <div class="col-lg-9">
        <div class="form-group">
            <label class="col-md-3 control-label" ><span class="error-text">*</span>Role Name</label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->input('role_name'); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" >Description</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('description', 
                    array(
                    'type' => 'textarea',
                    'class' => 'text-justify',
                    'rows' => '4'
                    )
                );
                ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" >Status</label>
            <div class="col-md-6 radio-inline status-radio" id="radio">                
                    <?php            
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'id' => 'radio1',
                        'legend' => false,          
                        'value'=>1
                    ));
                    ?>
                
            </div>
        </div>
        <div class="form-group button-section clearfix">
            <label class="col-md-3 control-label" ></label>
            <div class="col-md-6">
               <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
        <a class="btn btn-flat btn-default"
           href="<?php echo $this->Html->url(array('controller' => 'roleMasters', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>

