<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php if(isset($PAGE_TITLE) && !empty($PAGE_TITLE)){
	echo __($PAGE_TITLE);
}else{
	echo __("Car Listing");
} ?>
</title>

<style>
/* Flash message css */
.red{ color: red; text-align: center; }
.green{	color: green; text-align: center; }
</style>

<!-- Include All CSS -->
<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i|Roboto+Condensed:400,400i,700,700i" rel="stylesheet">
<?php echo $this->Html->css(['front/bootstrap.min', 'front/style', 'front/responsive','front/validationEngine.jquery','front/select2.min','front/jquery.fancybox']); ?>

<link href="<?php echo BASE_URL; ?>/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo BASE_URL; ?>/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!--- Include All JS --->
<script type="text/javascript">
    var baseUrl = "<?php echo BASE_URL; ?>";
    var siteUrl = "<?php echo SITE_URL; ?>";
</script>
<?php echo $this->Html->script(['front/jquery.min', 'front/jquery-ui','front/jquery.validationEngine', 'front/jquery.validationEngine-en','front/select2.full','front/general','front/common','front/alertifyjs/alertify','front/jquery.countdown','front/jquery.elevatezoom','front/jquery.fancybox','front/jquery.slimscroll','front/jquery.blockUI']); ?>

<script type="text/javascript">
    
    $(window).scroll(function(){
        var sticky = $('body'),
                scroll = $(window).scrollTop();

        if (scroll >= 100) sticky.addClass('sticky_header');
        else sticky.removeClass('sticky_header');
      });  
</script>
</head>

<body>

<!-- Fetch Header -->
<?php echo $this->element('front/header');  ?>
<!-- Finish Header -->


<!-- Fetch Middle Content -->
<?php echo $this->fetch('content'); ?>
<!-- Finished Middle Content -->


<!-- Fetch Footer -->
<?php echo $this->element('front/footer'); ?>
<!-- Finished Footer -->

<!-- Include jQuery files -->
<?php echo $this->Html->script(['front/bootstrap.min']); ?>

</body>
</html>

<script>
	$('#flashMessage').fadeOut(5200);
</script>
