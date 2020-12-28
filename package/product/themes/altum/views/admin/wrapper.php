<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html class="admin" lang="<?= \Altum\Language::$language_code ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= \Altum\Language::$language_code  ?>" />

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

        <?php foreach(['admin-' . \Altum\ThemeStyle::get_file(), 'admin-custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= SITE_URL . ASSETS_URL_PATH ?>css/<?= $file ?>?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>
    </head>

    <body class="admin" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">

        <div class="admin-container">

            <?= $this->views['admin_sidebar'] ?>

            <section class="admin-content animate__animated animate__fadeIn">
                <div id="admin_overlay" class="admin-overlay" style="display: none"></div>

                <?= $this->views['admin_menu'] ?>

                <div class="p-5">
                    <?= $this->views['content'] ?>

                    <?= $this->views['footer'] ?>
                </div>
            </section>
        </div>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            <script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>

        <script>
            let toggle_admin_sidebar = () => {

                /* Open sidebar menu */
                $('body').toggleClass('admin-sidebar-opened');

                /* Toggle overlay */
                $('#admin_overlay').fadeToggle(150);

                /* Change toggle button content */
                let button = $('#admin_menu_toggler');

                $(button).children().animate({opacity: 0}, 75, event => {
                    if($('body').hasClass('admin-sidebar-opened')) {
                        $(button).html('<i class="fa fa-fw fa-times"></i>');
                    } else {
                        $(button).html('<i class="fa fa-fw fa-bars"></i>');
                    }

                    $(button).css('opacity', 0).animate({opacity: 1}, 75)
                });
            };

            /* Toggler for the sidebar */
            $('#admin_menu_toggler').on('click', event => {
                event.preventDefault();

                toggle_admin_sidebar();

                if($('body').hasClass('admin-sidebar-opened')) {
                    $('#admin_overlay').off().on('click', toggle_admin_sidebar);
                } else {
                    $('#admin_overlay').off();
                }

            });
        </script>
    </body>
</html>
