<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>">
<head>
    <title><?= \Altum\Title::get() ?></title>
    <base href="<?= SITE_URL; ?>">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php if(!empty($this->settings->favicon)): ?>
        <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
    <?php endif ?>

    <?php foreach(['bootstrap.min.css', 'custom.css', 'animate.min.css'] as $file): ?>
        <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>

    <?= \Altum\Event::get_content('head') ?>

    <?php if(!empty($this->settings->custom->head_js)): ?>
        <?= $this->settings->custom->head_js ?>
    <?php endif ?>

    <?php if(!empty($this->settings->custom->head_css)): ?>
        <style><?= $this->settings->custom->head_css ?></style>
    <?php endif ?>
</head>

<body class="bg-white">

<main class="animate__animated animate__fadeIn">

    <?= $this->views['content'] ?>

</main>


<?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

<?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
    <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
<?php endforeach ?>

<?= \Altum\Event::get_content('javascript') ?>
</body>
</html>
