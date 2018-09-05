<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php if(isset($PAGE_TITLE) && !empty($PAGE_TITLE)){
	echo __($PAGE_TITLE);
}else{
	echo __("CarListing");
} ?>
</title>

<style>
/* Flash message css */
.red{
	color: red;
	text-align: center;
}
.green{
	color: green;
	text-align: center;
}
</style>

<!-- Include All CSS -->

<?php echo $this->Html->css(['front/bootstrap.min', 'front/style','front/validationEngine.jquery']); ?>
<link href="<?php echo BASE_URL; ?>/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!--- Include All JS --->

<?php echo $this->Html->script(['front/jquery.min', 'front/jquery-ui','front/jquery.validationEngine', 'front/jquery.validationEngine-en']); ?>

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
<?php echo $this->Html->script(['front/jquery.min', 'front/bootstrap.min']); ?>

</body>
</html>

<script>
	$('#flashMessage').fadeOut(5200);
</script>