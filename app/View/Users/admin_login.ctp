<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        
        <title>Carlisting: Admin Panel</title>
        <?php echo $this->Html->css(array('admin/login/boot', 'admin/login/font-awesome', 'admin/login/base')); ?>
    </head>
    <body class="page">

        <div id="container" class="cls-container">
            <div class="cls-header cls-header-lg">
                <div class="cls-brand">
                    <a class="box-inline" href="#">
                        <span class="brand-title">Login <span class="text-thin">Area</span></span>
                    </a>
                </div>
            </div>
            <div class="cls-content">
                <div class="cls-content-sm panel">
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <div id="flashMessage" class="message">
								<?php //echo $this->Session->flash(); ?>
							</div>
                        </div>
                        <?php echo $this->Form->create('User', array("method"=>"POST", 'class'=>'form-horizontal')); ?>

                        <p class="pad-btm">Sign In to your account</p>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                <?php echo $this->Form->input('User.username', array('div' => FALSE, 'label' => FALSE, 'placeholder' => 'Username', 'class' => 'form-control', 'required' => 'required')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                <?php echo $this->Form->input('User.password', array('div' => FALSE, 'label' => FALSE, 'placeholder' => 'Password', 'class' => 'form-control', 'required' => 'required')); ?>
                            </div>
                        </div>
                        <!--<div class="row">
                                <div class="col-xs-8 text-left checkbox">
                                        <label class="form-checkbox form-icon form-text">
                                        <input type="checkbox"> Remember me
                                        </label>
                                </div>
                                
                        </div>-->
                        <?php echo $this->Form->submit('Login',array('div'=>FALSE,'label'=>FALSE,'class'=>'btn btn-primary btn-lg btn-block'));?>
                        <?php echo $this->Form->end(); ?>
                        <div class="pad-ver">

                        </div>


                    </div>		
                </div>
            </div>
        </div>
    </body>
</html>
