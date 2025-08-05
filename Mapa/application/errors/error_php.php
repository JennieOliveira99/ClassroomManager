<!DOCTYPE html>
<html>
<head>
    <title>PHP Error</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
        .error { background: #fff; padding: 15px; border: 1px solid #ccc; }
        .title { font-size: 18px; color: #c00; }
    </style>
</head>
<body>
    <div class="error">
        <p class="title">A PHP Error was encountered</p>
        <p>Severity: <?php echo $severity; ?></p>
        <p>Message: <?php echo $message; ?></p>
        <p>Filename: <?php echo $filepath; ?></p>
        <p>Line Number: <?php echo $line; ?></p>
    </div>
</body>
</html>
