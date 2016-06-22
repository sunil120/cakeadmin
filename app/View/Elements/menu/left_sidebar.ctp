<?php $modules   = $this->Module->getPermittedModules();?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?= $this->Html->image('no_profile.gif', array('class' => 'img-circle')); ?>
            </div>
            <div class="pull-left info">
                <p><?php echo ucwords($activeUser['first_name'] . " " . $activeUser['last_name']) ?></p>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php 
            if(!empty($modules)) :                    
                foreach ($modules as $module):                        
                    $moduleName = $module['module_name'];
                    $linkHref = $module['anchor_href'];
                    $icon = $module['module_icon'];
                    $alias = $module['module_alias'];
                    $displaySubModules = $module['display_submodules'];
                    $subModules = $module['sub'];
                    $controller = $module['controller'];
                    $action = $module['action'];                        
            ?>
            <li class="treeview">
                <a href="<?php echo $linkHref;?>"><i class="fa <?php echo $icon;?>"></i><span><?php echo $moduleName;?></span>
                    <?php if(!empty($subModules) && $displaySubModules == 1) : ?><i class="fa fa-angle-left pull-right"></i><?php endif; ?>
                </a>
                <?php if(!empty($subModules) && $displaySubModules == 1) : ?>
                <ul class="treeview-menu menu-open">
                    <?php
                    foreach ($subModules as $subModule) :
                        $moduleName     = $subModule['module_name'];
                        $linkHref       = $subModule['anchor_href'];
                        $controller     = $subModule['controller'];
                        $action         = $subModule['action'];
                        $icon	  	= $subModule['module_icon'];
                    ?>
                    <li class="sub-module <?php echo strtolower($controller) == strtolower($this->request['controller']) ? 'active':''?>">
                        <a href="<?php echo $this->webroot.$controller.'/'.$action;?>"><i class="fa <?php echo $icon;?>"></i> <?php echo $moduleName;?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php
            endforeach;
            endif;
            ?>
        </ul>
    </section>
</aside>
