<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php echo $this->Html->docType('html5'); ?>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo Configure::read("site_title") ?>:
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        
        /*Meta files*/
        echo $this->Html->meta('icon');
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no,text/html,charset=UTF-8'));
        echo $this->fetch('meta');
        
        /*CSS files*/
        echo $this->Html->css(array(
               'bootstrap.min',
               'font-awesome.min',
               'ionicons.min.css',
                '//fonts.googleapis.com/css?family=Droid+Serif:400,700,700italic,400italic',
                'skins/_all-skins',
                'icomoon-fonts',
                'bootstrap-multiselect',
                'select2.min',
                'ng-tags-input.bootstrap.min',
                'ng-tags-input.min',
                'CakeAdminLTE',
                'custom',
                'mkjax',
            )
        );
        echo $this->fetch('css');
        
        /*JS files*/
        echo $this->Html->script(
          array(
            'jquery-2.1.4',
            'jquery-ui.js',
            'bootstrap-3.3.4.min',
            'angular-1.3.16.min',
            'neoget-datagrid',
            'moment.js',
            'angular-animate',
            'ui-bootstrap-tpls-0.13.4',
            'scripts',
            'bootstrap-multiselect',
            'bootstrap-datepicker.min',
            'modernizr',
            'owl.carousel',
            'jquery.validate',
            'additional-methods',
            'formValidation.min',
            'bootstrap.framework',
            'xlsx.core.min',
            'alasql.min',
            'ng-tags-input.min',
            'jquery.form',
            'pnotify.custom',
            'loadingoverlay.min',
            'mkjax',
            'permissions',
            'plugins/slimScroll/jquery.slimscroll.min',
            'plugins/fastclick/fastclick',            
            'plugins/select2/select2.full.min',
            'grid',
            'app',  
            'general',
            
           
        ));
        echo $this->fetch('script');
        ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="hold-transition <?php echo $theme_skin;?> sidebar-mini">
        <div class="wrapper">
            <?php
            echo $this->element('menu/top_menu');
            echo $this->element('menu/left_sidebar');
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="outer-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header" style="min-height: 40px;">
                    <?php
                    $actionName = $this->request->params['action'];
                    if (!isset($controllerName)) {
                        $controllerName = $this->request->params['controller'];
                    }
                    $actionUrl = 'index';
                    if ($this->request->params['controller'] == 'dashboard' & $this->request->params['action'] == 'index') {
                        ?>
                        <ol class="breadcrumb">
                            <li class="active">Dashboard</li>
                        </ol>
                        <?php
                    } else {
                        if (isset($controllerName) && !empty($controllerName)) {
                            if (!isset($breadcrumb) || empty($breadcrumb)) {
                                ?>
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="<?php echo $this->webroot."dashboard"?>" >Home</a>
                                    </li>
                                    <?php
                                    if (isset($controllerName) && !empty($controllerName)) {
                                        if (isset($actionName) && !empty($actionName) && $actionName != 'index') {
                                            ?>
                                            <li>
                                                <?php echo $this->Html->link(ucfirst($controllerName), array('controller' => $this->request->params['controller'], 'action' => $actionUrl)); ?>
                                            </li>
                                        <?php } else { ?>
                                            <li class="active">
                                                <?php echo $this->Html->link(ucfirst($controllerName), array('controller' => $this->request->params['controller'], 'action' => $actionUrl)); ?>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    if (isset($actionName) && !empty($actionName) && $actionName != 'index') {
                                        ?>
                                        <li class="active"><?php echo ucfirst($actionName); ?></li>
                                    <?php } ?>
                                </ol>
                                <?php
                            } else {
                                echo $this->Breadcrumb->create($breadcrumb);
                            }
                        }
                    }
                    ?>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="col-lg-12">
                        <?php echo $this->Session->flash(); ?>
                        <div class="mkjax-alert-wrapper"></div>
                        <div id="messages">
                            <?php
                            if ($this->Session->check('Message.flash'))
                                $this->Session->flash();
                            if ($messages = $this->Session->read('Message.multiFlash')) {
                                foreach ($messages as $k => $v)
                                    $this->Session->flash('multiFlash.' . $k);
                            }
                            ?>
                        </div>
                    </div>
                    <?php echo $this->fetch('content'); ?>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0
                </div>
                <strong>Copyright &copy; 2015-2016 <a href="<?php echo $this->webroot; ?>"><?php echo Configure::read('site_title'); ?></a>.</strong> All rights
                reserved.
            </footer>

            <!-- Control Sidebar -->
            <?php echo $this->element('menu/control_sidebar'); ?>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <div id="conversejs"></div>
        <!-- ./wrapper -->
    </body>
</html>

