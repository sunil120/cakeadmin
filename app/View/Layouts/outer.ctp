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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
            echo $this->Html->css('CakeAdminLTE');
            echo $this->Html->css('iCheck/square/blue.css');
            echo $this->Html->css('select2.min');
            
            echo $this->Html->script(
                array(
                    'jquery-2.1.4.js',
                    'bootstrap-3.3.4.min.js',
                    'angular-1.3.16.min.js',
                    'plugins/iCheck/icheck.min.js',
                    'plugins/select2/select2.full.min.js'
            ));
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?php
                    echo $this->Html->image(
                            'logo/'.$site_logo,
                            array('alt' => $site_logo, 'width' => '250px')
                        );
                  ?>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php echo $this->fetch('content'); ?>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
    </body>
</html>