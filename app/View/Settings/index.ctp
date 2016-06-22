<?php
//echo $this->webroot; exit;
?>
<script>
    function save() {
        $('#SettingsLogoImg').removeAttr('required');
    }

    $(function () {
        $(".email_setting").on('change', function () {
            if ($(this).val() == '2') {
                $("#smtpDiv").removeClass('hide');
                $("#smtpDiv :input").removeAttr("disabled");
            } else {
                $("#smtpDiv").addClass('hide');
                $("#smtpDiv :input").attr("disabled", true);
            }
        });
    });


</script>

<?php echo $this->CustomForm->create('Settings', array('enctype' => 'multipart/form-data')); ?>
<?php echo $this->CustomForm->input('id', array('value' => $settingsData['Settings']['id'])); ?>
<div class="clearfix">
    <div class="col-lg-9">
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Site Title</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.site_title', array(
                    'value' => $settingsData['Settings']['site_title']
                        )
                );
                ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Logo</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.logo_img', array(
                    'type' => 'file',
                    'class' => '',
                        )
                );
                ?>
                <?php if($settingsData['Settings']['logo_img'] !='' && file_exists(IMAGES_URL.'logo/'.$settingsData['Settings']['logo_img'])):?>
                <div class="settings-img">
                    <?php
                        echo $this->Html->image('logo/' . $settingsData['Settings']['logo_img'], 
                            array(
                                'alt' => $settingsData['Settings']['logo_img'],
                                'hight' => 100,
                                'width' => 100,
                                'class' => 'logo_settings_img',
                            )
                        );
                    ?>
                </div>
                <?php endif;  ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Logo Link</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.logo_link', array(
                    'value' => $settingsData['Settings']['logo_link'],
                    'type' => 'url'
                        )
                );
                ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Admin Email</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.admin_email', array(
                    'value' => $settingsData['Settings']['admin_email'],
                    'type' => 'email'
                        )
                );
                ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Email Setting</label>
            <div class="col-md-6" id="radio">
                <?php
                echo $this->CustomForm->radio('Settings.email_setting', array(
                        '1' => 'PHP Mail',
                        '2' => 'SMTP',
                    ), array(
                        'value' => $settingsData['Settings']['email_setting'],
                        'legend' => false,
                        'class' => 'email_setting'
                ));
                ?>
            </div>
        </div>
        <div id="smtpDiv" class="<?php echo ($settingsData['Settings']['email_setting'] != '2')?'hide':'' ?>">
            <div class="form-group clearfix">
                <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Host</label>
                <div class="col-md-6">
                    <?php
                    echo $this->CustomForm->input('Settings.smtp_host', array(
                        'value' => $settingsData['Settings']['smtp_host'],
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Port</label>
                <div class="col-md-6">
                    <?php
                    echo $this->CustomForm->input('Settings.smtp_port', array(
                        'value' => $settingsData['Settings']['smtp_port'],
                        'type' => 'number'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Username</label>
                <div class="col-md-6">
                    <?php
                    echo $this->CustomForm->input('Settings.smtp_username', array(
                        'value' => $settingsData['Settings']['smtp_username'],
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Password</label>
                <div class="col-md-6">
                    <?php
                    echo $this->CustomForm->input('Settings.smtp_password', array(
                        'value' => $settingsData['Settings']['smtp_password'],
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Discount Percentage</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.discount_percentage', array(
                    'value' => $settingsData['Settings']['discount_percentage']
                )
                );
                ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""><span class="error-text">*</span>Fixed Discount</label>
            <div class="col-md-6">
                <?php
                echo $this->CustomForm->input('Settings.fixed_discount', array(
                    'value' => $settingsData['Settings']['fixed_discount']
                )
                );
                ?>
            </div>
        </div>
        <div class="form-group button-section clearfix">
            <label class="col-md-3 control-label" for=""></label>
            <div class="col-md-6">
                <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary', 'onclick' => 'save();')); ?>

                <a class="btn btn-flat btn-default"
                   href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>"
                   title="Cancel">Cancel</a>

                <!-- <button type="button" onclick="showpreview();" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Preview
        </button> -->
            </div>
        </div>
    </div>

</div>

<?php echo $this->CustomForm->end(); ?>