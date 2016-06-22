<?php echo $this->CustomForm->create('CountryMaster',array('novalidate')); ?>
<div class="col-lg-9">
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" ><span class="error-text">*</span>Country</label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->input('country_name'); ?>
            </div>
        </div>

    <div class="form-group">
        <label class="col-md-3 control-label" ></label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
            <a class="btn btn-flat btn-default"
           href="<?php echo $this->Html->url(array('controller' => 'CountryMasters', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
        </div>
    </div>


</div>


<?php echo $this->CustomForm->end(); ?>


