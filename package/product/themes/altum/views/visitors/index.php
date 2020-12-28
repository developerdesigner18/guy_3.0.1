<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <div class="d-flex flex-column flex-xl-row justify-content-between mb-3">
            <div>
                <h1 class="h3 text-break"><?= sprintf($this->language->visitors->header->header, $this->website->host . $this->website->path) ?></h1>
            </div>

            <div>
                <button
                        id="daterangepicker"
                        type="button"
                        class="btn btn-sm btn-outline-primary my-1"
                        data-min-date="<?= \Altum\Date::get($this->website->date, 4) ?>"
                        data-max-date="<?= \Altum\Date::get('', 4) ?>"
                >
                    <i class="fa fa-fw fa-calendar mr-1"></i>
                    <span>
                        <?php if($data->date->start_date == $data->date->end_date): ?>
                            <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php else: ?>
                            <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                        <?php endif ?>
                    </span>
                    <i class="fa fa-fw fa-caret-down ml-1"></i>
                </button>

                <button type="button" class="btn btn-sm btn-outline-secondary d-print-none my-1" onclick="$('#filters').toggle();" data-toggle="tooltip" title="<?= $this->language->analytics->filters->toggle ?>">
                    <i class="fa fa-fw fa-filter"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3 mb-md-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->visitors ?></small>
                                <span class="h4 font-weight-bolder"><?= nr($data->total_rows) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-3 mb-md-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->visitors->header->average_time_per_session ?></small>
                                <span class="h4 font-weight-bolder"><?= \Altum\Date::get_seconds_to_his($data->average_time_per_session) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-stopwatch"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= (new \Altum\Views\View('partials/analytics/filters_wrapper', (array) $this))->run(['available_filters' => 'websites_visitors']) ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if(!$data->total_rows): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->visitors->basic->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->visitors->basic->no_data ?></h2>
            <p><?= sprintf($this->language->visitors->basic->no_data_help, '<a href="' . url('websites') . '">', '</a>') ?></a></p>
        </div>

    <?php else: ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th><?= $this->language->visitors->visitor->visitor ?></th>
                    <th><?= $this->language->visitors->visitor->system ?></th>
                    <th></th>
                    <th><?= $this->language->visitors->visitor->last_date ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data->visitors as $row): ?>
                    <?php
                    /* Visitor */
                    $icon = new \Jdenticon\Identicon([
                        'value' => $row->visitor_uuid,
                        'size' => 50
                    ]);
                    $row->icon = $icon->getImageDataUri();
                    ?>

                <tr data-visitor-id="<?= $row->visitor_id ?>">
                    <td>
                        <div class="d-flex align-items-center">
                            <?php if(($row->custom_parameters = json_decode($row->custom_parameters, true)) && count($row->custom_parameters)): ?>
                            <a href="<?= url('visitor/' . $row->visitor_id) ?>" class="mr-3" data-toggle="tooltip" title="<?= sprintf($this->language->visitors->visitor->custom_parameters, count($row->custom_parameters)) ?>">
                                <span>
                                    <i class="fa fa-fw fa-2x fa-fingerprint text-primary"></i>
                                </span>
                            </a>
                            <?php else: ?>
                            <a href="<?= url('visitor/' . $row->visitor_id) ?>" class="mr-3">
                                <img src="<?= $row->icon ?>" class="visitor-avatar rounded-circle" alt="" />
                            </a>
                            <?php endif ?>

                            <div class="d-flex flex-column">
                                <div>
                                    <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/countries/' . ($row->country_code ? strtolower($row->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />

                                    <span class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : $this->language->visitor->visitor->country_unknown ?></span>
                                </div>
                                <small class="text-muted"><?= $this->language->visitors->visitor->since ?> <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->date, 2) ?></span></small>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span class="mr-1" data-toggle="tooltip" title="<?= $this->language->visitor->device_type->{$row->device_type} ?>"><i class="fa fa-fw fa-<?= $row->device_type ?>"></i></span>
                            <span><?= $row->os_name ?></span>
                        </div>

                        <small class="text-muted"><?= $row->browser_name . ' - ' . $row->screen_resolution ?></small>
                    </td>

                    <td>
                        <a href="<?= url('visitor/' . $row->visitor_id) ?>">
                            <i class="fa fa-fw fa-hourglass-half fa-sm text-muted"></i> <?= sprintf($this->language->visitors->visitor->total_sessions, '<strong>' . nr($row->total_sessions) . '</strong>') ?>
                        </a>
                    </td>

                    <td>
                        <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->last_date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->last_date, 2) ?></span>
                    </td>

                    <td>
                        <a
                                href="#"
                                class="text-muted text-decoration-none"
                                data-toggle="modal"
                                data-target="#visitor_delete"
                                data-visitor-id="<?= $row->visitor_id ?>"
                        >
                            <span data-toggle="tooltip" title="<?= $this->language->global->delete ?>"><i class="fa fa-fw fa-times fa-sm"></i></span>
                        </a>
                    </td>
                </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>

    <?php endif ?>

</section>

<input type="hidden" name="start_date" value="<?= \Altum\Date::get($data->date->start_date, 1) ?>" />
<input type="hidden" name="end_date" value="<?= \Altum\Date::get($data->date->end_date, 1) ?>" />
<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->date->start_date) ?>,
        endDate: <?= json_encode($data->date->end_date) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode($this->language->global->date->today) ?>: [moment(), moment()],
            <?= json_encode($this->language->global->date->yesterday) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode($this->language->global->date->last_7_days) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode($this->language->global->date->last_30_days) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode($this->language->global->date->this_month) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode($this->language->global->date->last_month) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        alwaysShowCalendars: true,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`<?= url('visitors') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
