<?php echo $this->CustomForm->create('Faq', array('novalidate')); ?>
<div class="clearfix">
    <div class="col-lg-9">
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Question</label>
            <div class="col-md-9">
                <?php echo $this->CustomForm->input('question');?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Answer</label>
            <div class="col-md-9">
                <?php echo $this->CustomForm->input('answer' , array('type'=>'textarea','class'=>'textarea')); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Status</label>
            <div class="col-sm-8" id="radio">
                <div class="row radio-inline status-radio">
                    <?php
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'legend' => false,
                        'value'=>1,
                        'tabindex'=>'6'
                    ));
                    if ($this->CustomForm->isFieldError('status')){
                        echo $this->CustomForm->error('status');
                    }
                    ?>

                </div>
            </div> 
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""></label>
            <div class="col-md-9">
                <div class="button-section clearfix">
                    <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
                    <a class="btn btn-flat btn-default"
                       href="<?php echo $this->Html->url(array('controller' => 'faq', 'action' => 'index')); ?>"
                       title="Cancel">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->CustomForm->end(); ?>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('FaqAnswer');
</script>

