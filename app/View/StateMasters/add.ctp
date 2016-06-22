<?php echo $this->CustomForm->create('StateMaster', array('novalidate')); ?>
<div class="col-lg-9">

    <div class="form-group clearfix">
        <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Country</label>
        <div class="col-md-6">
            <?php
            echo $this->CustomForm->input('country_id', 
                array(
                    'options' => $countries,
                    'empty' => 'Select Country',
                    array('class' => 'select2')
                )
            );
            ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-3 control-label" for=""><span class="error-text">*</span>State</label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->input('state_name'); ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-3 control-label" for=""></label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
            <a class="btn btn-flat btn-default"
               href="<?php echo $this->Html->url(array('controller' => 'StateMasters', 'action' => 'index')); ?>"
               title="Cancel">Cancel</a>
        </div>
    </div>
</div>


<?php echo $this->CustomForm->end(); ?>



