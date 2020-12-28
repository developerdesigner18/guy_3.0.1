<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= \Altum\Language::$language_code  ?>" />

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <link rel="alternate" href="<?= SITE_URL . \Altum\Routing\Router::$original_request ?>" hreflang="x-default" />
        <?php if(count(\Altum\Language::$languages) > 1): ?>
            <?php foreach(\Altum\Language::$languages as $language_code => $language_name): ?>
                <?php if($this->settings->default_language != $language_name): ?>
                    <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(!empty($this->settings->favicon)): ?>
            <link href="<?= SITE_URL . UPLOADS_URL_PATH . 'favicon/' . $this->settings->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty($this->settings->custom->head_js)): ?>
            <?= $this->settings->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty($this->settings->custom->head_css)): ?>
            <style><?= $this->settings->custom->head_css ?></style>
        <?php endif ?>
    </head>

    <body class="bg-white" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">

        <main class="animate__animated animate__fadeIn">

            <div class="container mt-8 mb-3">
                <div class="d-flex justify-content-center">
                    <a href="<?= url() ?>" class="text-decoration-none text-dark">
                        <?php if($this->settings->logo != ''): ?>
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'logo/' . $this->settings->logo ?>" class="img-fluid navbar-logo" alt="<?= $this->language->global->accessibility->logo_alt ?>" />
                        <?php else: ?>
                            <h1><?= $this->settings->title ?></h1>
                        <?php endif ?>
                    </a>
                </div>
            </div>

            <?= $this->views['content'] ?>

        </main>

        <?php if(\Altum\Routing\Router::$controller_key != 'index'): ?>
            <?php require THEME_PATH . 'views/partials/ads_footer.php' ?>
        <?php endif ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
