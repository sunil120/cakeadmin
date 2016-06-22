<?php echo $this->CustomForm->create('CityMaster', array('novalidate')); ?>
<div class="col-lg-9">

    <div class="form-group clearfix">
        <label class="col-md-3 control-label" ><span class="error-text">*</span>Country</label>
        <div class="col-md-6">
            <?php
            echo $this->CustomForm->input('country_id', array(
                'options' => $countries,
                'empty' => 'Select Country',
               ),
               array('class' => 'select2')
            );
            ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-3 control-label" ><span class="error-text">*</span>State</label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->input('state_id', array(
                'options' => array(),
                'empty' => 'Select State',
               ),
               array('class' => 'select2')     
            );
            ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-3 control-label" ><span class="error-text">*</span>City</label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->input('city_name'); ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-3 control-label" ></label>
        <div class="col-md-6">
            <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
            <a class="btn btn-flat btn-default"
               href="<?php echo $this->Html->url(array('controller' => 'CityMasters', 'action' => 'index')); ?>"
               title="Cancel">Cancel</a>
        </div>
    </div>
</div>
<?php echo $this->CustomForm->end(); ?>
<script type="text/javascript" charset="utf-8">
$("#CityMasterCountryId").on("change",function(){
   $.post('<?php echo Router::url('/', true); ?>CityMasters/getStateByCountry', {country:$(this).val()})
       .success(function(data) {
            data = $.parseJSON(data);
            $('#CityMasterStateId').empty();
            $('#CityMasterStateId').append('<option value="">Select State</option>');
            $.each(data, function(i, value) {
                $('#CityMasterStateId').append($('<option>').text(value).attr('value', i));
            });
        });
});
</script>



