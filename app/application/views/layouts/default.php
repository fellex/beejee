<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo "BeeJee Tasks - " . $title; ?></title>

	<link rel="stylesheet" href="<?= $this->app_root_path ?>public/css/bootstrap.css">
	<link rel="stylesheet" href="<?= $this->app_root_path ?>public/css/style.css">
	<script>
        let app_root_path = '<?= $this->app_root_path ?>';
    </script>
</head>
<body>

<?php // рисуем header ?>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-default">
        <h3 class="my-0 mr-md-auto font-weight-normal header-title"><a href="<?= $this->app_root_path ?>">Мои задачи</a></h3>
        <?php if($this->user->isAuthorized()) { ?>
            <?= ucfirst($this->user->name) ?>&nbsp;&nbsp;<a class="btn btn-outline-primary mr-5" href="<?= $this->app_root_path ?>account/logout">Выйти</a>
        <?php } else { ?>
            <a class="btn btn-outline-primary mr-5" href="<?= $this->app_root_path ?>account/login">Войти</a>
        <?php } ?>
    </div>


<?php // рисуем основную часть страницы ?>
    <div class="container mb-4" style="max-width: 90%;">
        <div class="row justify-content-md-center">
            <div class="col-11"><?php echo $content; ?></div>
        </div>
    </div>


<?php // рисуем footer ?>
<div class="flex-md-row align-items-center p-3 px-md-4 bg-default">
    <h6 class="my-0 mr-md-auto font-weight-normal text-center">© fellex [2022.03.06]</h6>
</div>


    <script src="<?= $this->app_root_path ?>public/js/jquery-slim.min.js"></script>
    <script src="<?= $this->app_root_path ?>public/js/popper.min.js"></script>
    <script src="<?= $this->app_root_path ?>public/js/bootstrap.js"></script>
    <script src="<?= $this->app_root_path ?>public/js/common.js"></script>
</body>
</html>
