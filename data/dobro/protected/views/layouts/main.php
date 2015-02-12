<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->pageTitle ?></title>
    <link href="/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
    body {
    padding-top: 50px;
    }
    .dropdown-menu {
        font-size:9px !important;
    }
    .dropdown-menu2 {
        font-size:9px !important;
    }
    .scrollable-menu {
        height: auto;
        max-height: 200px;
        overflow-x: hidden;
    }
    </style>
</head>
<body>
	<?php echo $content; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="/css/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>