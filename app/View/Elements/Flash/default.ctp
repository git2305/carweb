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