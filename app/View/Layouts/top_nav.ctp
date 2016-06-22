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
        echo $this->Html->meta('icon');
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'));
        echo $this->fetch('meta');

        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('font-awesome.min.css');
        echo $this->Html->css('ionicons.min.css');
        echo $this->Html->css('//fonts.googleapis.com/css?family=Droid+Serif:400,700,700italic,400italic');
        echo $this->Html->css('skins/_all-skins.min');
        echo $this->Html->css('icomoon-fonts');
        echo $this->Html->css('bootstrap-multiselect');
        echo $this->Html->css('select2.min');
        echo $this->Html->css('ng-tags-input.bootstrap.min');
        echo $this->Html->css('ng-tags-input.min');
        echo $this->Html->css('CakeAdminLTE');
        echo $this->Html->css('custom');
        echo $this->Html->css('mkjax');

        echo $this->fetch('css');
        //echo $this->Html->script('libs/jquery-1.10.2.min');
        //echo $this->Html->script('libs/bootstrap.min');
        echo $this->Html->script(array(
            'jquery-2.1.4.js',
            'jquery-ui.js',
            'bootstrap-3.3.4.min.js',
            'angular-1.3.16.min.js',
            'neoget-datagrid.js',
            'moment.js',
            'angular-animate.js',
            'ui-bootstrap-tpls-0.13.4.js',
            'scripts.js',
            'bootstrap-multiselect.js',
            'bootstrap-datepicker.min.js',
            'modernizr.js',
            'owl.carousel.js',
            'jquery.validate.js',
            'additional-methods.js',
            'formValidation.min.js',
            'bootstrap.framework.js',
            'xlsx.core.min.js',
            'alasql.min.js',
            'ng-tags-input.min.js',
            'jquery.form.js',
            'pnotify.custom.js',
            'loadingoverlay.min.js',
            'general.js',
            'mkjax.js',
            'permissions.js',
            'plugins/fastclick/fastclick',
            'plugins/slimScroll/jquery.slimscroll.min',            
            'grid',
            'plugins/select2/select2.full.min.js',
            'app',
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

    <body class="hold-transition <?php echo $theme_skin;?> layout-top-nav">
        <div class="wrapper">
            <?php
            echo $this->element('top-nav-menu/top_menu');
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="outer-wrapper">
                <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header" style="min-height: 40px;">
                    <!--                    <h1>
                    <?php echo ucwords($this->request['controller']); ?>
                                            <small><?php echo $this->request['action']; ?></small>
                                        </h1>-->
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
                    <!--                    <ol class="breadcrumb">
                                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                                            <li class="active">Dashboard</li>
                                        </ol>-->
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
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.2
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
        <!-- ./wrapper -->
    </body>
</html>