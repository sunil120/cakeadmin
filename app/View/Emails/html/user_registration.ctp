Dear <?php echo $data['first_name'].' '.$data['last_name']; ?>,<br><br>
You are invited to join <?php echo Configure::read('site_title'); ?>.<br><br>
Please use the user name listed below to login for the first time.<br><br>
<b>User Name: </b>   <?php echo $data['user_name'];?><br><br>
Please click the link below to set your new password.<br><br>
<a href="<?php echo Router::url('/', true);?>users/setpassword/<?php echo $data['id'];?>"><?php echo Router::url('/', true);?>users/setpassword/<?php echo $data['id'];?></a>
<br><br>Thank you<br>
<?php echo Configure::read('site_title'); ?> Support Team




