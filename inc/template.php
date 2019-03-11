<?php
$content=ob_get_contents();
ob_end_clean();
?>
<html>
<head><title>UmVirt LFS Assistant</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php echo $content;?>
</body>

</html>
