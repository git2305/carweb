<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<!--
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">-->

		<link rel="shortcut icon" href="img/admin/favicon_1.ico">

		<title>
			<?php if(isset($PAGE_TITLE) && !empty($PAGE_TITLE)){
				echo $PAGE_TITLE;
			}else{
				echo "Admin Dashboard";
			} ?>
		</title>
		
		<!-- include css -->
		<?php echo $this->Html->css(['admin/footable/footable.core', 'admin/owl.carousel/owl.carousel.min', 'admin/owl.carousel/owl.theme.default.min', 'admin/bootstrap.min', 'admin/core', 'admin/components', 'admin/icons', 'admin/pages', 'admin/responsive', 'admin/bootstrap-select.min','front/validationEngine.jquery']); ?>
	
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
		<!-- Include jQuery files -->
                <script>
                    var BASE_URL = '<?php echo BASE_URL.'/admin/'; ?>';
                </script>
                
		<?php echo $this->Html->script(['admin/html5shiv', 'admin/respond.min', 'admin/modernizr.min','front/jquery.min', 'front/jquery-ui','front/jquery.validationEngine', 'front/jquery.validationEngine-en','admin/common']); ?>
	
		<script src="https://code.highcharts.com/highcharts.js"></script>

	</head>

	<body class="fixed-left">

		<!-- Begin page -->
		<div id="wrapper">

        <!-- Fetch Header -->
			<?php echo $this->element('admin/header'); ?>
		<!-- Finish Header -->


        <!-- ========== Left Sidebar Start ========== -->
			<?php echo $this->element('admin/leftmenubar'); ?>
        <!-- Left Sidebar End -->


			<!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
			
			<!-- Middle content start -->
				<?php echo $this->fetch('content'); ?>
			<!-- Middle content end -->
				
		</div>
		
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
		<?php echo $this->Html->script(['front/jquery.min', 'front/bootstrap.min', 'admin/detect', 'admin/fastclick', 'admin/jquery.slimscroll', 'admin/jquery.blockUI', 'admin/waves', 'admin/wow.min', 'admin/jquery.nicescroll', 'admin/jquery.scrollTo.min', 'admin/jquery.core', 'admin/jquery.app', 'admin/owl.carousel.min', 'admin/footable.all.min', 'admin/bootstarp-select/bootstrap-select.min', 'admin/jquery.footable']); ?>
        
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                //owl carousel
                $("#owl-slider").owlCarousel({
                    loop:true,
				    nav:false,
				    autoplay:true,
				    autoplayTimeout:4000,
				    autoplayHoverPause:true,
					animateOut: 'fadeOut',
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:1
				        },
				        1000:{
				            items:1
				        }
				    }
                });
                
                $("#owl-slider-2").owlCarousel({
                    loop:false,
				    nav:false,
				    autoplay:true,
				    autoplayTimeout:4000,
				    autoplayHoverPause:true,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:1
				        },
				        1000:{
				            items:1
				        }
				    }
                });
                
                //Owl-Multi
                $('#owl-multi').owlCarousel({
				    loop:true,
				    margin:20,
				    nav:false,
				    autoplay:true,
				    responsive:{
				        0:{
				            items:1
				        },
				        480:{
				            items:2
				        },
				        700:{
				            items:4
				        },
				        1000:{
				            items:3
				        },
				        1100:{
				            items:5
				        }
				    }
				})
            });
            
			function changestatus(id, model) {
			//alert($('#status_' + id + ' span').attr("status"));
                            $.ajax({
                                url: '<?php echo BASE_URL; ?>/Users/changestatus',
                                type: 'post', // performing a POST request
                                data: {
                                    model: model,
                                    id: id,
                                    status: $('#status_' + id).children("span").text()
                                },
                                success: function (result)
                                {
                                    if (result == 0) {
                                        alert('An error occured, please try again');
                                    } else {
                                        $('#status_' + id).html(result);
                                    }

                                }
                            });
                        }
        </script>
	
	</body>
</html>
