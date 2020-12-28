<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div>
                <h1 class="h3"><i class="fa fa-fw fa-xs fa-server text-gray-700"></i> <?= $this->language->websites->websites->header ?></h1>
                <p class="text-muted"><?= $this->language->websites->websites->subheader ?></p>
            </div>

            <div class="col-auto p-0 d-flex">
                <div>
                    <?php if(!$this->team): ?>
                        <?php if($this->user->plan_settings->websites_limit != -1 && count($this->websites) >= $this->user->plan_settings->websites_limit): ?>
                            <button type="button" data-confirm="<?= $this->language->websites->error_message->websites_limit ?>"  class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->websites->websites->create ?></button>
                        <?php else: ?>
                            <button type="button" data-toggle="modal" data-target="#website_create" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->websites->websites->create ?></button>
                        <?php endif ?>
                    <?php endif ?>
                </div>

                <div class="ml-3">
                    <div class="dropdown">
                        <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> rounded-pill filters-button dropdown-toggle-simple" data-toggle="dropdown"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                        <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                            <div class="dropdown-header d-flex justify-content-between">
                                <span class="h6 m-0"><?= $this->language->global->filters->header ?></span>

                                <?php if(count($data->filters->get)): ?>
                                    <a href="<?= url('websites') ?>" class="text-muted"><?= $this->language->global->filters->reset ?></a>
                                <?php endif ?>
                            </div>

                            <div class="dropdown-divider"></div>

                            <form action="" method="get" role="form">
                                <div class="form-group px-4">
                                    <label for="search" class="small"><?= $this->language->global->filters->search ?></label>
                                    <input type="text" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                                </div>

                                <div class="form-group px-4">
                                    <label for="search_by" class="small"><?= $this->language->global->filters->search_by ?></label>
                                    <select name="search_by" id="search_by" class="form-control form-control-sm">
                                        <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= $this->language->websites->filters->search_by_name ?></option>
                                        <option value="host" <?= $data->filters->search_by == 'host' ? 'selected="selected"' : null ?>><?= $this->language->websites->filters->search_by_host ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="is_enabled" class="small"><?= $this->language->global->filters->status ?></label>
                                    <select name="is_enabled" id="is_enabled" class="form-control form-control-sm">
                                        <option value=""><?= $this->language->global->filters->all ?></option>
                                        <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= $this->language->global->active ?></option>
                                        <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= $this->language->global->disabled ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="is_enabled" class="small"><?= $this->language->websites->filters->tracking_type ?></label>
                                    <select name="tracking_type" id="tracking_type" class="form-control form-control-sm">
                                        <option value=""><?= $this->language->global->filters->all ?></option>
                                        <option value="normal" <?= isset($data->filters->filters['tracking_type']) && $data->filters->filters['tracking_type'] == 'normal' ? 'selected="selected"' : null ?>><?= $this->language->websites->filters->tracking_type_normal ?></option>
                                        <option value="lightweight" <?= isset($data->filters->filters['tracking_type']) && $data->filters->filters['tracking_type'] == 'lightweight' ? 'selected="selected"' : null ?>><?= $this->language->websites->filters->tracking_type_lightweight ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="order_by" class="small"><?= $this->language->global->filters->order_by ?></label>
                                    <select name="order_by" id="order_by" class="form-control form-control-sm">
                                        <option value="date" <?= $data->filters->order_by == 'date' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_by_datetime ?></option>
                                        <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= $this->language->websites->filters->order_by_name ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="order_type" class="small"><?= $this->language->global->filters->order_type ?></label>
                                    <select name="order_type" id="order_type" class="form-control form-control-sm">
                                        <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_type_asc ?></option>
                                        <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= $this->language->global->filters->order_type_desc ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4 mt-4">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block"><?= $this->language->global->submit ?></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if(count($this->websites)): ?>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= $this->language->websites->websites->website ?></th>
                    <th><?= $this->language->websites->websites->usage ?></th>
                    <th><?= $this->language->websites->websites->is_enabled ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->websites as $row): ?>
                    <tr data-website-id="<?= $row->website_id ?>">
                        <td>
                            <div class="d-flex flex-column">
                                <span><?= $row->name ?></span>
                                <div class="d-flex align-items-center text-muted">
                                    <img src="https://external-content.duckduckgo.com/ip3/<?= $row->host ?>.ico" class="img-fluid icon-favicon mr-1" />

                                    <?= $row->host . $row->path ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">
                            <?php ob_start() ?>
                            <div class='d-flex flex-column p-3'>
                                <div class='d-flex justify-content-between my-1'>
                                    <div class='mr-3'><?= $this->language->websites->websites->sessions_events ?></div>
                                    <strong><?= nr($row->current_month_sessions_events) . '/' . ($this->user->plan_settings->sessions_events_limit === -1 ? '∞' : nr($this->user->plan_settings->sessions_events_limit, 1, true)) ?></strong>
                                </div>

                                <?php if($row->tracking_type == 'normal'): ?>
                                    <?php if($this->user->plan_settings->events_children_limit != 0): ?>
                                        <div class='d-flex justify-content-between my-1'>
                                            <div class='mr-3'><?= $this->language->websites->websites->events_children ?></div>
                                            <strong><?= nr($row->current_month_events_children) . '/' . ($this->user->plan_settings->events_children_limit === -1 ? '∞' : nr($this->user->plan_settings->events_children_limit, 1, true)) ?></strong>
                                        </div>
                                    <?php endif ?>

                                    <?php if($this->settings->analytics->sessions_replays_is_enabled && $this->user->plan_settings->sessions_replays_limit != 0): ?>
                                        <div class='d-flex justify-content-between my-1'>
                                            <div class='mr-3'><?= $this->language->websites->websites->sessions_replays ?></div>
                                            <strong><?= nr($row->current_month_sessions_replays) . '/' . ($this->user->plan_settings->sessions_replays_limit === -1 ? '∞' : nr($this->user->plan_settings->sessions_replays_limit, 1, true)) ?></strong>
                                        </div>
                                    <?php endif ?>

                                    <?php if($this->settings->analytics->websites_heatmaps_is_enabled && $this->user->plan_settings->websites_heatmaps_limit != 0): ?>
                                        <div class='d-flex justify-content-between my-1'>
                                            <div class='mr-3'><?= $this->language->websites->websites->websites_heatmaps ?></div>
                                            <strong><?= nr($row->heatmaps) . '/' . ($this->user->plan_settings->websites_heatmaps_limit === -1 ? '∞' : nr($this->user->plan_settings->websites_heatmaps_limit, 1, true)) ?></strong>
                                        </div>
                                    <?php endif ?>
                                <?php endif ?>

                                <?php if($this->user->plan_settings->websites_goals_limit != 0): ?>
                                    <div class='d-flex justify-content-between my-1'>
                                        <div class='mr-3'><?= $this->language->websites->websites->websites_goals ?></div>
                                        <strong><?= nr($row->goals) . '/' . ($this->user->plan_settings->websites_goals_limit === -1 ? '∞' : nr($this->user->plan_settings->websites_goals_limit, 1, true)) ?></strong>
                                    </div>
                                <?php endif ?>
                            </div>
                            <?php $html = ob_get_clean() ?>

                            <a
                                href="<?= url('plan') ?>"
                                data-toggle="tooltip"
                                data-html="true"
                                title="<?= $html ?>"
                                class="text-muted"
                            >
                                <i class="fa fa-fw fa-lg fa-info-circle"></i>
                            </a>
                        </td>
                        <td>
                            <?php if($row->is_enabled): ?>
                                <span class="badge badge-success">
                                    <i class="fa fa-fw fa-check"></i> <?= $this->language->global->active ?>
                                    <?php if($row->tracking_type == 'normal'): ?>
                                        - <?= $this->language->websites->websites->tracking_type_normal ?>
                                    <?php endif ?>

                                    <?php if($row->tracking_type == 'lightweight'): ?>
                                        - <?= $this->language->websites->websites->tracking_type_lightweight ?>
                                    <?php endif ?>
                                </span>

                                <?php if($row->tracking_type == 'normal'): ?>
                                <div class="mt-1">
                                    <?php if($this->user->plan_settings->events_children_limit != 0 && $row->events_children_is_enabled): ?>
                                        <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= $this->language->websites->websites->events_children ?>"><i class="fa fa-fw fa-mouse"></i></span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="<?= $this->language->websites->websites->events_children ?>"><i class="fa fa-fw fa-mouse"></i></span>
                                    <?php endif ?>

                                    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                                        <?php if($this->user->plan_settings->sessions_replays_limit != 0 && $row->sessions_replays_is_enabled): ?>
                                            <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= $this->language->websites->websites->sessions_replays ?>"><i class="fa fa-fw fa-video"></i></span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="<?= $this->language->websites->websites->sessions_replays ?>"><i class="fa fa-fw fa-video"></i></span>
                                        <?php endif ?>
                                    <?php endif ?>

                                    <?php if($this->settings->analytics->email_reports_is_enabled): ?>
                                        <?php if($this->user->plan_settings->email_reports_is_enabled && $row->email_reports_is_enabled): ?>
                                            <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= $this->language->websites->websites->email_reports ?>"><i class="fa fa-fw fa-envelope"></i></span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="<?= $this->language->websites->websites->email_reports ?>"><i class="fa fa-fw fa-envelope"></i></span>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>
                                <?php endif ?>

                            <?php else: ?>
                                <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->global->disabled ?></span>
                            <?php endif ?>
                        </td>

                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm mr-3"
                                    data-toggle="modal"
                                    data-target="#website_pixel_key"
                                    data-tracking-type="<?= $row->tracking_type ?>"
                                    data-pixel-key="<?= $row->pixel_key ?>"
                                    data-url="<?= $row->scheme . $row->host . $row->path ?>"
                                ><?= $this->language->websites->websites->pixel_key ?></button>

                                <?php if(!$this->team): ?>
                                <a
                                        href="#"
                                        class="mr-3 text-decoration-none"
                                        data-toggle="modal"
                                        data-target="#website_update"
                                        data-website-id="<?= $row->website_id ?>"
                                        data-name="<?= $row->name ?>"
                                        data-scheme="<?= $row->scheme ?>"
                                        data-host="<?= $row->host . $row->path ?>"
                                        data-tracking-type="<?= $row->tracking_type ?>"
                                        data-events-children-is-enabled="<?= (bool) $row->events_children_is_enabled ?>"
                                        data-sessions-replays-is-enabled="<?= (bool) $row->sessions_replays_is_enabled ?>"
                                        data-excluded-ips="<?= $row->excluded_ips ?>"
                                        data-email-reports-is-enabled="<?= $row->email_reports_is_enabled ?>"
                                        data-is-enabled="<?= (bool) $row->is_enabled ?>"
                                >
                                    <i class="fa fa-fw fa-sm fa-pencil-alt"></i> <?= $this->language->global->edit ?>
                                </a>
                                <a
                                        href="#"
                                        class="text-muted text-decoration-none"
                                        data-toggle="modal"
                                        data-target="#website_delete"
                                        data-website-id="<?= $row->website_id ?>"
                                >
                                    <i class="fa fa-fw fa-sm fa-times"></i> <?= $this->language->global->delete ?>
                                </a>
                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php else: ?>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->websites->websites->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->websites->websites->no_data ?></h2>
            <p><?= $this->language->websites->websites->no_data_help ?></a></p>
        </div>
    <?php endif ?>

</section>
