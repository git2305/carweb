<?php
    $class = '';
    $text = '';
    switch($params['class']) {
        case 'success':
            $class = 'alert-sucess';
            $text = 'Success!';
            $messageClass = 'success';
            break;
        case 'fail':
            $class = 'alert-fail';
            $text = 'Error!';
            $messageClass = 'fail';
            break;
        case 'info':
            $class = 'alert-info';
            $text = 'Note!';
            $messageClass = 'info';
            break;
        case 'warning':
            $class = 'alert-warning';
            $text = 'Warning!';
            $messageClass = 'warning';
            break;
        default:
            break;
    }
?>


<script>
    alertify.notify('<?php echo $message; ?>', '<?php echo $messageClass; ?>');
</script>

<!--<div id="flashMessage" class="alert <?php //echo $class; ?> fade in">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <strong>Error!</strong> <?php //echo $message; ?>
</div>-->
