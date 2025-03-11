<?php
// Simulate an array of valid URLs for your project (it can be more complex, depending on your application)
$valid_urls = [
  '/',
  './',
];

// Get the requested URL (without the domain)
$current_url = $_SERVER['REQUEST_URI'];

// Check if the requested URL exists in our valid URLs
if (!in_array($current_url, $valid_urls)) {
  // If the URL is not valid, redirect to the 404 page
?>
<form action="/" method="post">
    <input type="hidden" name="page">
</form>
<script>
  document.querySelector('input').value = '<?php echo 404 ?>';
  document.querySelector('form').submit();
</script>
<?php
  exit;
}
?>