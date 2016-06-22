<?php echo $this->CustomForm->create('TagMaster',array('novalidate')); ?>
<?php echo $this->CustomForm->hidden('id'); ?>
<div class="clearfix">
    <div class="col-lg-9">
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Tag</label>
            <div class="col-md-9">
                <?php echo $this->CustomForm->input('tag_name'); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">Status</label>
            <div class="col-md-6 radio-inline status-radio" id="radio">
                    <?php
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'id' => 'radio1',
                        'value'=>'1',
                        'legend' => false,
                    ));
                    ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""></label>
            <div class="col-md-9">
                <div class="button-section clearfix">
                    <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary', 'onclick' => 'save();')); ?>

                    <a class="btn btn-flat btn-default"
                       href="<?php echo $this->Html->url(array('controller' => 'tag_masters', 'action' => 'index')); ?>"
                       title="Cancel">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->CustomForm->end(); ?>

