<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?= assets('css/toastr.css') ?>" rel="stylesheet"/>
    <link href="<?= assets('css/core.css"') ?>" rel="stylesheet"/>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
    <script src="<?= assets('js/toastr.min.js') ?>"></script> <!-- flash notifications -->	
    <script src="<?= assets('js/bootbox.min.js') ?>"></script><!-- confirmation boxes -->
    <script src="<?= assets('js/helpers.js') ?>"></script>
    <script src="<?= assets('js/login.js') ?>"></script>
    <script src="<?= assets('js/jqtable.js') ?>"></script>

</head>
<body>
    <?php 
        if (!isset($hidenav) || !$hidenav)
            section('navbar.php'); 
    ?>
    
    <div class="container">
        <main>
           <?= $content ?>
        </main>
    </div>
</body>
</html>
