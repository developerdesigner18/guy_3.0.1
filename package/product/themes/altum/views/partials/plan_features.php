<?php defined('ALTUMCODE') || die() ?>

<ul class="list-style-none m-0">
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->websites_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->websites_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->websites_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_websites_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->websites_limit, '<strong>' . nr($data->plan_settings->websites_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->sessions_events_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->sessions_events_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->sessions_events_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_sessions_events_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->sessions_events_limit, '<strong>' . nr($data->plan_settings->sessions_events_limit, 1, true) . '</strong>') ?>
                <small class="text-muted"><?= \Altum\Language::get()->global->plan_settings->per_month ?></small>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->events_children_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->events_children_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->events_children_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_events_children_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->events_children_limit, '<strong>' . nr($data->plan_settings->events_children_limit, 1, true) . '</strong>') ?>
                <small class="text-muted"><?= \Altum\Language::get()->global->plan_settings->per_month ?></small>
            <?php endif ?>
        </div>
    </li>

    <?php if($data->plan_settings->events_children_limit != 0): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
            <div>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->events_children_retention, '<strong>' . nr($data->plan_settings->events_children_retention) . '</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->sessions_replays_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->sessions_replays_limit ? null : 'text-muted' ?>">
                <?php if($data->plan_settings->sessions_replays_limit == -1): ?>
                    <?= \Altum\Language::get()->global->plan_settings->unlimited_sessions_replays_limit ?>
                <?php else: ?>
                    <?= sprintf(\Altum\Language::get()->global->plan_settings->sessions_replays_limit, '<strong>' . nr($data->plan_settings->sessions_replays_limit, 1, true) . '</strong>') ?>
                    <small class="text-muted"><?= \Altum\Language::get()->global->plan_settings->per_month ?></small>
                <?php endif ?>
            </div>
        </li>
    <?php endif ?>

    <?php if($data->plan_settings->sessions_replays_limit != 0): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
            <div>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->sessions_replays_retention, '<strong>' . nr($data->plan_settings->sessions_replays_retention) . '</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->websites_heatmaps_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->websites_heatmaps_limit ? null : 'text-muted' ?>">
                <?php if($data->plan_settings->websites_heatmaps_limit == -1): ?>
                    <?= \Altum\Language::get()->global->plan_settings->unlimited_websites_heatmaps_limit ?>
                <?php else: ?>
                    <?= sprintf(\Altum\Language::get()->global->plan_settings->websites_heatmaps_limit, '<strong>' . nr($data->plan_settings->websites_heatmaps_limit, 1, true) . '</strong>') ?>
                <?php endif ?>
            </div>
        </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->websites_goals_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->websites_goals_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->websites_goals_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_websites_goals_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->websites_goals_limit, '<strong>' . nr($data->plan_settings->websites_goals_limit, 1, true) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <?php if($this->settings->analytics->email_reports_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                <?= $data->plan_settings->email_reports_is_enabled ? \Altum\Language::get()->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} : \Altum\Language::get()->global->plan_settings->email_reports_is_enabled ?>
            </div>
        </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->teams_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->teams_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->teams_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->no_ads ?>
        </div>
    </li>
</ul>
