<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


    <!-- Mirrored from www.ait.sk/uniadmin/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Tue, 20 Jul 2010 00:38:40 GMT -->
    <!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8"><!-- /Added by HTTrack -->
    <head>
        <meta name="description"  content=""/>
        <meta name="keywords" content=""/>
        <meta name="robots" content="ALL,FOLLOW"/>
        <meta name="Author" content="AIT"/>
        <meta http-equiv="imagetoolbar" content="no"/>
        <title>5Stars CRM System</title>
        <?php echo $this->Html->css('reset') ?>
        <?php echo $this->Html->css('screen') ?>
        <!--[if IE 7]>	
        <?php echo $this->Html->css('ie7') ?>
        <![endif]-->    
        <?php echo $this->element('css_and_js'); ?>
    </head>

    <body class="no-side">

        <?php 
            
            echo $this->Session->flash('error');            
        ?>

        <?php echo $this->fetch('content') ?>

    </body>
</html>
