Dear <?php echo $data['first_name'].' '.$data['last_name']; ?>,<br><br>
Your password has been reset for the account: <b><?php echo $data['user_name'];?></b><br><br>
We hope you continue to enjoy using the system.<br><br>
Thank you<br>
<?php echo Configure::read('site_title'); ?> Support Team