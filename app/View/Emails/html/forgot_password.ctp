Dear <?php echo $data['first_name'].' '.$data['last_name']; ?>,<br><br>
You have requested to reset your password.<br><br>
To reset the password for your account: <b><?php echo $data['user_name'];?></b><br><br>
Please click the link below to set your new password.<br><br>
<a href="<?php echo Router::url('/', true);?>users/setpassword/<?php echo $data['id'];?>"><?php echo Router::url('/', true);?>users/setpassword/<?php echo $data['id'];?></a>
<br><br>Please ignore this email if you did not request help with your password. Your current password will remain unchanged.
<br><br>Thank you<br>
<?php echo Configure::read('site_title'); ?> Support Team




