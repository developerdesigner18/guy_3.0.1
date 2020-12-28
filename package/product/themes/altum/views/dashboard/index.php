<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <?php if($data->type != 'default'): ?>
        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li><a href="<?= url('dashboard') ?>"><?= $this->language->dashboard->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                    <li class="active" aria-current="page"><?= $this->language->dashboard->{$data->type}->header ?></li>
                </ol>
            </small>
        </nav>
        <?php endif ?>

        <div class="d-flex flex-column flex-xl-row justify-content-between mb-3">
            <div>
                <h1 class="h3 text-break"><?= $this->website->host . $this->website->path ?></h1>
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

                <?php if($this->website->tracking_type == 'normal'): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary d-print-none my-1" onclick="$('#filters').toggle();" data-toggle="tooltip" title="<?= $this->language->analytics->filters->toggle ?>">
                    <i class="fa fa-fw fa-filter"></i>
                </button>
                <?php endif ?>

                <?php if($this->website->tracking_type == 'normal'): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary d-print-none my-1" onclick="window.open('<?= url('dashboard/csv_normal') ?>')" data-toggle="tooltip" title="<?= $this->language->global->export_csv ?>">
                    <i class="fa fa-fw fa-file-csv"></i>
                </button>
                <?php endif ?>

                <?php if($this->website->tracking_type == 'lightweight'): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-print-none my-1" onclick="window.open('<?= url('dashboard/csv_lightweight') ?>')" data-toggle="tooltip" title="<?= $this->language->global->export_csv ?>">
                        <i class="fa fa-fw fa-file-csv"></i>
                    </button>
                <?php endif ?>

                <button type="button" class="btn btn-sm btn-outline-secondary d-print-none my-1" onclick="window.print()" data-toggle="tooltip" title="<?= $this->language->global->export_pdf ?>">
                    <i class="fa fa-fw fa-file-pdf"></i>
                </button>
            </div>
        </div>

        <?php if($data->type == 'default'): ?>

            <?php if($this->website->tracking_type == 'normal'): ?>
                <div class="row">
                    <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->pageviews ?></small>
                                        <span class="h4 font-weight-bolder"><?= nr($data->basic_totals['pageviews']) ?></span>
                                    </div>

                                    <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                        <i class="fa fa-fw fa-lg fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->sessions ?></small>
                                        <span class="h4 font-weight-bolder"><?= nr($data->basic_totals['sessions']) ?></span>
                                    </div>

                                    <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                        <i class="fa fa-fw fa-lg fa-hourglass-half"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->visitors ?></small>
                                        <span class="h4 font-weight-bolder"><?= nr($data->basic_totals['visitors']) ?></span>
                                    </div>

                                    <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                        <i class="fa fa-fw fa-lg fa-users"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if($this->website->tracking_type == 'lightweight'): ?>
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->pageviews ?></small>
                                        <span class="h4 font-weight-bolder"><?= nr($data->basic_totals['pageviews']) ?></span>
                                    </div>

                                    <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                        <i class="fa fa-fw fa-lg fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->visitors ?></small>
                                        <span class="h4 font-weight-bolder"><?= nr($data->basic_totals['visitors']) ?></span>
                                    </div>

                                    <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                        <i class="fa fa-fw fa-lg fa-users"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

        <?php endif ?>

        <?= (new \Altum\Views\View('partials/analytics/filters_wrapper', (array) $this))->run(['available_filters' => null]) ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if(!count($data->logs)): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->dashboard->basic->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->dashboard->basic->no_data ?></h2>
            <p><?= sprintf($this->language->dashboard->basic->no_data_help, '<a href="' . url('websites') . '">', '</a>') ?></a></p>
        </div>

    <?php else: ?>

        <?= $this->views['dashboard_content'] ?>

    <?php endif ?>

</section>

<input type="hidden" name="start_date" value="<?= \Altum\Date::get($data->date->start_date, 4) ?>" />
<input type="hidden" name="end_date" value="<?= \Altum\Date::get($data->date->end_date, 4) ?>" />
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
        redirect(`<?= url('dashboard') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

    <?php if(count($data->logs)): ?>

    /* Basic data to use for fetching extra data */
    let tracking_type = <?= json_encode($this->website->tracking_type) ?>;
    let website_id = $('input[name="website_id"]').val();
    let start_date = $('input[name="start_date"]').val();
    let end_date = $('input[name="end_date"]').val();

    for(let request_type of ['paths', 'landing_paths', 'exit_paths', 'referrers', 'social_media_referrers', 'search_engines_referrers', 'countries', 'utms_source', 'utms_medium', 'utms_campaign', 'screen_resolutions', 'browser_languages', 'operating_systems', 'device_types', 'browser_names', 'goals']) {

        if($(`#${request_type}_result`).length) {

            let limit = $(`#${request_type}_result`).data('limit') || 10;
            let bounce_rate = $(`#${request_type}_result`).data('bounce-rate') || false;

            /* Put the loading placeholders */
            $(`#${request_type}_result`).html($('#loading').html());

            /* Send the requests one by one to not spam the server */
            (async () => {
                let url_query = build_url_query({
                    website_id,
                    start_date,
                    end_date,
                    global_token,
                    request_type,
                    limit,
                    bounce_rate
                });

                await fetch(`${url}dashboard-ajax-${tracking_type}?${url_query}`)
                    .then(response => {
                        if(response.ok) {
                            return response.json();
                        } else {
                            return Promise.reject(response);
                        }
                    })
                    .then(data => {

                        $(`#${request_type}_result`).html(data.details.html);

                        /* Send data to the countries map if needed */
                        if(request_type == 'countries' && $('#countries_map').length) {
                            $('#countries_map').trigger('load', data.details.data);
                        }

                    })
                    .catch(error => {

                        $(`#${request_type}_result`).html(<?= json_encode($this->language->global->error_message->basic) ?>)

                    });
            })();
        }
    }

    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
