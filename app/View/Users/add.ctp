<script>
    var min = 1;
    var max = 1000;
    function changeUsername() {

        var firstName = $('#first_name').val();
        var lastName = $('#last_name').val();
        var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;

        if (firstName != '' && lastName != '') {
            var testfirstName = firstName.charAt(0).toLowerCase();
            var testlastName = lastName.toLowerCase();
            var user_name = testfirstName + testlastName + randomNumber;
            $('#user_name').val(user_name);
            elemSpecificGradeDiv = document.getElementById('testing');
            elemSpecificGradeDiv.innerHTML = user_name;
        }
    }
</script>
<?php
echo $this->CustomForm->create('User ', array('type' => 'file', 'name' => 'test',
    'inputDefaults' => array(
        'between' => '<div >',
        'after' => '</div>'
    ),
    'novalidate'
));
?>
<div class="clearfix">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for=""><span class="error-text">*</span>First Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('UserModel.first_name', array('tabindex'=>'1','id' => 'first_name', 'onblur' => 'changeUsername();'), array()); ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for=""><span class="error-text">*</span>Email Id</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('UserModel.email', array('tabindex'=>'4')); ?>
            </div>
        </div>
        <div class="form-group clearfix" id="radio-control">
            <label class="col-sm-4 control-label">Status</label>
            <div class="col-sm-8" id="radio">
                <div class="row radio-inline status-radio">
                    <?php
                    $default = '1';
                    echo $this->CustomForm->radio('UserModel.status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(                
                        'id' => 'radio1',
                        'legend' => false,
                        'value' => $default,
                        'tabindex'=>'7'
                    ));
                    ?> 
                </div>
            </div> 
        </div>  
        
    </div>

    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">Middle Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('UserModel.middle_name',array('tabindex'=>'2')); ?>
            </div>
        </div>
        
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for="">User Name</label>
            <div class="col-sm-8 form-user-name">
                <?php
                    echo $this->CustomForm->input('UserModel.user_name', array('tabindex'=>'5','type' => 'text', 'id' => 'user_name'));
                ?>
                <span id="testing"></span> 
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for=""><span class="error-text">*</span>Last Name</label>
            <div class="col-sm-8">
                <?php echo $this->CustomForm->input('UserModel.last_name', array('tabindex'=>'3','id' => 'last_name', 'onblur' => 'changeUsername();'), array()); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-sm-4 control-label" for=""><span class="error-text">*</span>Role</label>
            <div class="col-sm-8">
                <div class="select-style">
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
    </div>
</div>

<div class="col-lg-4 col-md-12 col-sm-12">
<div class="form-group clearfix">
    <label class="col-sm-4 control-label button-section" for=""></label>
    <div class="col-sm-8">
        <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary', 'tabindex'=>'8')); ?>
        <a class="btn btn-flat btn-default" 
           href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>"
           title="Cancel">Cancel</a>
    </div>
</div>
</div>
    
<?php echo $this->CustomForm->end(); ?>