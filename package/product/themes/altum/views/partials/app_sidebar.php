<?php defined('ALTUMCODE') || die() ?>

<section class="app-sidebar d-print-none">
    <div class="app-sidebar-title">
        <a href="<?= url() ?>"><?= substr($this->settings->title, 0, 1) ?></a>
    </div>

    <ul class="app-sidebar-links">
        <li class="<?= \Altum\Routing\Router::$controller == 'Dashboard' ? 'active' : null ?>">
            <a href="<?= url('dashboard') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->dashboard->menu ?>"><i class="fa fa-fw fa-th"></i></a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'Realtime' ? 'active' : null ?>">
            <a href="<?= url('realtime') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->realtime->menu ?>"><i class="fa fa-fw fa-record-vinyl"></i></a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'Visitors' ? 'active' : null ?>">
            <a href="<?= url('visitors') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->visitors->menu ?>"><i class="fa fa-fw fa-user-friends"></i></a>
        </li>

        <?php if($this->settings->analytics->websites_heatmaps_is_enabled && $this->user->plan_settings->websites_heatmaps_limit != 0): ?>
        <li class="<?= \Altum\Routing\Router::$controller == 'Heatmaps' ? 'active' : null ?>">
            <a href="<?= url('heatmaps') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->heatmaps->menu ?>"><i class="fa fa-fw fa-fire"></i></a>
        </li>
        <?php endif ?>

        <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
        <li class="<?= \Altum\Routing\Router::$controller == 'Replays' ? 'active' : null ?>">
            <a href="<?= url('replays') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->replays->menu ?>"><i class="fa fa-fw fa-video"></i></a>
        </li>
        <?php endif ?>

        <li class="<?= \Altum\Routing\Router::$controller == 'Websites' ? 'active' : null ?>">
            <a href="<?= url('websites') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->websites->menu ?>"><i class="fa fa-fw fa-server"></i></a>
        </li>

        <li class="<?= \Altum\Routing\Router::$controller == 'Teams' ? 'active' : null ?>">
            <a href="<?= url('teams') ?>" data-toggle="tooltip" data-placement="right" title="<?= $this->language->teams->menu ?>"><i class="fa fa-fw fa-user-shield"></i></a>
        </li>

        <li>
            <a href="<?= url('help') ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="<?= $this->language->help->menu ?>"><i class="fa fa-fw fa-question"></i></a>
        </li>
    </ul>
</section>
