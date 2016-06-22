<?php
$modules   = $this->Module->getPermittedModules();
?>
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?php echo $this->webroot ?>" class="logo" style="background: url(<?php echo $this->webroot.'logos/'.$site_logo; ?>) no-repeat center center / 90% auto">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"></span>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                <?php
                if(!empty($modules)) {
                    foreach ($modules as $module) {
                        $moduleName = $module['module_name'];
                        $linkHref = $module['anchor_href'];
                        $icon = $module['module_icon'];
                        $alias = $module['module_alias'];
                        $displaySubModules = $module['display_submodules'];
                        $subModules = $module['sub'];
                        $controller = $module['controller'];
                        $action = $module['action'];
                        $shtml = $ahtml = $class = "";                        

                        if(!empty($subModules) && $displaySubModules == 1) {
                            $class = "dropdown";
                            $ahtml = 'class="dropdown-toggle" data-toggle="dropdown"';
                            $shtml = '<span class="caret"></span>';
                        }
                        
                ?>
                    <li class="<?php echo $class;?>">
                        <a href="<?php echo $linkHref;?>" <?php echo $ahtml;?> ><?php echo $moduleName ." ". $shtml;?></a>
                        <?php
                            if(!empty($subModules) && $displaySubModules == 1) {
                        ?>
                        <ul class="dropdown-menu" role="menu">
                            <?php
                                foreach ($subModules as $subModule) {
                                    $moduleName     = $subModule['module_name'];
                                    $linkHref       = $subModule['anchor_href'];
                                    $controller     = $subModule['controller'];
                                    $action         = $subModule['action'];
                                    $icon	  	= $subModule['module_icon'];
                            ?>
                            <li><a href="<?php echo $this->webroot.$controller.'/'.$action;?>"><?php echo $moduleName;?></a></li>
                            <?php
                                }
                            ?>
                        </ul>
                        <?php
                            }
                        ?>
                    </li>
                <?php
                    }
                }
                ?>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <?php echo $this->Html->image('no_profile.gif', array('class' => 'user-image')); ?>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo ucwords($activeUser['first_name'] . " " . $activeUser['last_name']) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <?php echo $this->Html->image('no_profile.gif', array('class' => 'img-circle')); ?>
                                <p>
                                    <?php echo ucwords($activeUser['first_name']
                                            . " " . $activeUser['last_name']
                                            . " - " . $activeUser['RoleMaster']['role_name']) ?>
                                    <small>
                                        Member since
                                        <?php echo $this->Time->format("M. Y", $activeUser['created'])?></small>
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
<!--                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>-->
                                </div>
                                <!-- /.row -->
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
            <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->