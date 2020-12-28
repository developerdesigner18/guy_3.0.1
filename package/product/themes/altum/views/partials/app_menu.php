<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar app-navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">

        <?php if(count($this->websites)): ?>
        <div class="dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-check-circle fa-sm"></i> <span class="text-gray-700"><?= $this->website->host ?></span>
            </a>

            <div class="dropdown-menu">
                <?php foreach($this->websites as $row): ?>
                <a href="<?= url('dashboard?website_id=' . $row->website_id . '&redirect=' . \Altum\Routing\Router::$controller_key) ?>" class="dropdown-item" href="<?= url('account') ?>">
                    <?php if($row->website_id == $this->website->website_id): ?>
                        <i class="fa fa-fw fa-sm fa-check-circle mr-1 text-primary"></i>
                    <?php else: ?>
                        <i class="fa fa-fw fa-sm fa-circle mr-1"></i>
                    <?php endif ?>

                    <?= $row->host ?>
                </a>
                <?php endforeach ?>
            </div>
        </div>

            <?php if($this->team): ?>
                <div class="d-flex align-items-baseline">
                    <span class="text-muted"><?= sprintf($this->language->global->team->is_enabled, '<strong>' . $this->team->name . '</strong>') ?></span>
                    <small class="text-muted">&nbsp;(<a href="#" id="team_logout"><?= $this->language->global->team->logout ?></a>)</small>
                </div>

                <?php ob_start() ?>
                <script>
                    $('#team_logout').on('click', event => {
                        delete_cookie('selected_team_id');
                        redirect('dashboard');

                        event.preventDefault();
                    });
                </script>
                <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
            <?php endif ?>
        <?php endif ?>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_navbar" aria-controls="main_navbar" aria-expanded="false" aria-label="<?= $this->language->global->accessibility->toggle_navigation ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="main_navbar">
            <ul class="navbar-nav align-items-lg-center">

                <?php foreach($data->pages as $data): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $data->url ?>" target="<?= $data->target ?>"><?= $data->title ?></a></li>
                <?php endforeach ?>

                <li class="ml-lg-3 dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <img src="<?= get_gravatar($this->user->email) ?>" class="app-navbar-avatar mr-3" />

                            <div class="d-flex flex-column mr-3">
                                <span class="text-gray-700"><?= $this->user->name ?></span>
                                <small class="text-muted"><?= $this->user->email ?></small>
                            </div>

                            <i class="fa fa-fw fa-caret-down"></i>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if(\Altum\Middlewares\Authentication::is_admin()): ?>
                            <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa fa-fw fa-sm fa-user-shield mr-1"></i> <?= $this->language->global->menu->admin ?></a>
                        <?php endif ?>
                        <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= $this->language->account->menu ?></a>
                        <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= $this->language->account_plan->menu ?></a>
                        <a class="dropdown-item" href="<?= url('teams') ?>"><i class="fa fa-fw fa-sm fa-user-shield mr-1"></i> <?= $this->language->teams->menu ?></a>
                        <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt mr-1"></i> <?= $this->language->global->menu->logout ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
