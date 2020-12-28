<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?php display_notifications() ?>

    <div class="row">
        <div class="col-12 col-lg-2 mb-5 mb-lg-0">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="<?= url('help') ?>" class="nav-link <?= $data->page == 'introduction' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-file mr-1"></i> <?= $this->language->help->introduction->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/setup') ?>" class="nav-link <?= $data->page == 'setup' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-plus-circle mr-1"></i> <?= $this->language->help->setup->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/install') ?>" class="nav-link <?= $data->page == 'install' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= $this->language->help->install->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/verify') ?>" class="nav-link <?= $data->page == 'verify' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-check mr-1"></i> <?= $this->language->help->verify->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/advanced') ?>" class="nav-link <?= $data->page == 'advanced' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-user-shield mr-1"></i> <?= $this->language->help->advanced->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/privacy') ?>" class="nav-link <?= $data->page == 'privacy' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-eye mr-1"></i> <?= $this->language->help->privacy->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/data') ?>" class="nav-link <?= $data->page == 'data' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-server mr-1"></i> <?= $this->language->help->data->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= url('help/faq') ?>" class="nav-link <?= $data->page == 'faq' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-question-circle mr-1"></i> <?= $this->language->help->faq->menu ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="col col-lg-8">
            <?= $this->views['page'] ?>
        </div>
    </div>
</div>
