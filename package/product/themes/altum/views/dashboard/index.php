<?php defined('ALTUMCODE') || die() ?>
 
<header class="header">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <div>
                <h1 class="h3 text-break"><?= sprintf($this->language->dashboard->header->header, $this->website->host . $this->website->path) ?></h1>
            </div>

            <div>
                <form class="form-inline" id="datepicker_form">
                    <label class="position-relative">
                        <div id="datepicker_selector" class="text-muted clickable">
                            <span class="mr-1">
                                <?php if($data->date->start_date == $data->date->end_date): ?>
                                    <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                                <?php else: ?>
                                    <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                                <?php endif ?>
                            </span>
                            <i class="fa fa-fw fa-caret-down"></i>
                        </div>

                        <input
                                type="text"
                                id="datepicker_input"
                                data-range="true"
                                data-min="<?= (new \DateTime($this->website->date))->format('Y-m-d') ?>"
                                name="date_range"
                                value="<?= $data->date->input_date_range ? $data->date->input_date_range : '' ?>"
                                placeholder=""
                                autocomplete="off"
                                readonly="readonly"
                                class="custom-control-input"
                        >

                    </label>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->pageviews ?></small>
                                <span class="h4"><?= nr($data->basic_totals['pageviews']) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->sessions ?></small>
                                <span class="h4"><?= nr($data->basic_totals['sessions']) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-hourglass"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->visitors ?></small>
                                <span class="h4"><?= nr($data->basic_totals['visitors']) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3 mb-3 mb-lg-0 d-flex flex-column justify-content-center">
                <button type="button" class="btn btn-block btn-sm btn-outline-secondary d-print-none" onclick="$('#filters').toggle();"><i class="fa fa-fw fa-filter"></i> <?= $this->language->analytics->filters->toggle ?></button>
                <button type="button" class="btn btn-block btn-sm btn-outline-secondary d-print-none" onclick="window.open('<?= url('dashboard/csv') ?>')"><i class="fa fa-fw fa-file-csv"></i> <?= $this->language->global->export_csv ?></button>
                <button type="button" class="btn btn-block btn-sm btn-outline-secondary d-print-none" onclick="window.print()"><i class="fa fa-fw fa-file-pdf"></i> <?= $this->language->global->export_pdf ?></button>
            </div>
        </div>

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

<input type="hidden" name="start_date" value="<?= \Altum\Date::get($data->date->start_date, 1) ?>" />
<input type="hidden" name="end_date" value="<?= \Altum\Date::get($data->date->end_date, 1) ?>" />
<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
    <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datepicker.min.js' ?>"></script>

<script>
    /* Datepicker */
    $.fn.datepicker.language['altum'] = <?= json_encode(require APP_PATH . 'includes/datepicker_translations.php') ?>;
    let datepicker = $('#datepicker_input').datepicker({
        language: 'altum',
        dateFormat: 'yyyy-mm-dd',
        autoClose: true,
        timepicker: false,
        toggleSelected: false,
        minDate: new Date($('#datepicker_input').data('min')),
        maxDate: new Date(),

        onSelect: (formatted_date, date) => {

            if(date.length > 1) {
                let [ start_date, end_date ] = formatted_date.split(',');

                if(typeof end_date == 'undefined') {
                    end_date = start_date
                }

                /* Redirect */
                redirect(`dashboard?start_date=${start_date}&end_date=${end_date}`);
            }
        }
    });


    <?php if(count($data->logs)): ?>

    /* Basic data to use for fetching extra data */
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

                await fetch(`${url}dashboard-ajax?${url_query}`)
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
