<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="<?= \simplerest\libs\Url::assets('css/toastr.css') ?>" rel="stylesheet"/>
    <link href="<?= \simplerest\libs\Url::assets('css/core.css"') ?>" rel="stylesheet"/>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
    <script src="<?= \simplerest\libs\Url::assets('js/toastr.min.js') ?>"></script> <!-- flash notifications -->	
    <script src="<?= \simplerest\libs\Url::assets('js/bootbox.min.js') ?>"></script><!-- confirmation boxes -->
    <script src="<?= \simplerest\libs\Url::assets('js/helpers.js') ?>"></script>
    <script src="<?= \simplerest\libs\Url::assets('js/login.js') ?>"></script>
    <script src="<?= \simplerest\libs\Url::assets('js/jqtable.js') ?>"></script>

</head>
<body>
    <?php 
        if (!isset($hidenav) || !$hidenav)
            \simplerest\libs\Url::section('navbar.php') 
    ?>
    
    <div class="container">
        <main>
           <?= $content ?>
        </main>
    </div>
</body>
</html>
