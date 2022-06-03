<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <base href="/">
    <link rel="shorcut icon" href="img/star.png" type="image/png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?=$this->getmeta();?>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="chosen/docsupport/prism.css"/>
    <link rel="stylesheet" type="text/css" href="chosen/chosen.css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="d-flex flex-column h-100">
    <header>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="img/logo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"> <!-- active">-->
                            <a class="nav-link" href="/">Главная </a> <!--<span class="sr-only">(current)</span>-->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/partner">Контрагенты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/budget">Бюджет</a>
                        </li>
                    </ul>
                    <!--<form class="form-inline mt-2 mt-md-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Поиск" aria-label="Search">
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Поиск</button>
                    </form>-->
                </div>
            </div>
        </nav>
    </header>
    <div class="content">
        <?= /** @var string $content */
        $content;?>
    </div>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <span class="text-muted">Разработано ИТО ОП "Тольятти"</span>
        </div>
    </footer>

    <div class="preloader"><img src="img/ring.svg" alt=""></div>

    <script>
        let path = '<?=PATH;?>';
    </script>
    <script type="text/javascript" src="chosen/docsupport/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/main.js?<?echo time();?>"></script>
    <?php
        foreach ($scripts as $script) {
            echo $script;
        }
    ?>
</body>
</html>
