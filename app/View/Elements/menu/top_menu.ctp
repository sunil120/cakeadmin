<header class="main-header" >
    <!-- Logo -->
    <a href="<?php echo $this->webroot?>" class="logo" style="background: url(<?php echo $this->webroot.'img/logo/'.@$site_logo; ?>) no-repeat center center / 90% auto">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini" ></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo $this->Html->image('no_profile.gif', array('class' => 'user-image')); ?>
                        <span class="hidden-xs"><?php echo ucwords($activeUser['first_name'] . " " . $activeUser['last_name']) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php echo $this->Html->image('no_profile.gif', array('class' => 'img-circle')); ?>
                            <p>
                                <?php echo ucwords($activeUser['first_name']
                                        . " " . $activeUser['last_name']
                                        . " - " . $activeUser['RoleMaster']['role_name']) ?>
                                <small>
                                    Member since
                                    <?php  echo $this->Time->format("M. Y", $activeUser['date_created'])?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="<?php echo $this->webroot . "settings"?>">Settings</a>
                                </div>
                                <div class="col-xs-8 text-center">
                                    <a href="<?php echo $this->webroot . "users/changepassword/" . $activeUser['id']?>">Change Password</a>
                                </div>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo $this->webroot . "users/userprofile/" . $activeUser['id']; ?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo $this->webroot . "users/logout" ?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
