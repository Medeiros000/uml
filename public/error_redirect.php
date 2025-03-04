<?php
require_once __DIR__ . '/../vendor/autoload.php';

$error_code = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : '';

$error_message =
  match ($error_code) {
    403     => '403',
    404     => '404',
    500     => '500',
    default => $error_code
  };

?>

<form action="/uml/" method="post">
    <input type="hidden" name="page">
</form>
<script>
  document.querySelector('input').value = '<?php echo $error_message ?>';
  document.querySelector('form').submit();
</script>