<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <div>
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                    <span class="badge badge-primary mr-1">BETA</span>
                    <h1 class="h3 text-break"><?= sprintf($this->language->replays->header, $this->website->host . $this->website->path) ?></h1>
                </div>
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
            <div class="col-12 col-md-3 mb-3 mb-md-0">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <small class="text-muted text-uppercase font-weight-bold"><?= $this->language->analytics->replays ?></small>
                                <span class="h4"><?= nr($data->total_replays) ?></span>
                            </div>

                            <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fa fa-fw fa-lg fa-video"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3 mb-3 mb-md-0">
            </div>

            <div class="col-12 col-md-3 mb-3 mb-md-0">
            </div>

            <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex flex-column justify-content-center">
                <button type="button" class="btn btn-block btn-sm btn-outline-secondary" onclick="$('#filters').toggle();"><i class="fa fa-fw fa-filter"></i> <?= $this->language->analytics->filters->toggle ?></button>
            </div>
        </div>

        <?= (new \Altum\Views\View('partials/analytics/filters_wrapper', (array) $this))->run(['available_filters' => 'websites_visitors']) ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if(!$data->total_replays): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->replays->basic->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->replays->basic->no_data ?></h2>
            <p><?= sprintf($this->language->replays->basic->no_data_help) ?></a></p>
        </div>

    <?php else: ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= $this->language->replays->replay->session ?></th>
                <th><?= $this->language->replays->replay->date ?></th>
                <th><?= $this->language->replays->replay->visitor ?></th>
                <th>
                    <span data-toggle="tooltip" title="<?= $this->language->replays->delete ?>">
                        <a
                            href="#"
                            class="text-muted text-decoration-none"
                            data-toggle="modal"
                            data-target="#replays_delete"
                        >
                            <i class="fa fa-fw fa-times fa-sm"></i>
                        </a>
                    </span>
                </th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($data->replays as $row): ?>
                    <?php
                    /* Visitor */
                    $icon = new \Jdenticon\Identicon([
                        'value' => $row->visitor_uuid,
                        'size' => 50
                    ]);
                    $row->icon = $icon->getImageDataUri();
                    ?>

                <tr data-session-id="<?= $row->session_id ?>">
                    <td>
                        <div class="d-flex">
                            <div class="mr-3 align-self-center" data-toggle="tooltip" title="<?= $this->language->replays->replay->sessions_replays ?>">
                                <a href="<?= url('replay/' . $row->session_id) ?>"><i class="fa fa-fw fa-play-circle fa-2x text-primary"></i></a>
                            </div>
                            <div class="d-flex flex-column">
                                <?= sprintf($this->language->replays->replay->time_spent, (new \DateTime($row->last_date))->diff((new \DateTime($row->date)))->format('%H:%I:%S')) ?>
                                <span class="text-muted"><?= sprintf($this->language->replays->replay->events, nr($row->events)) ?></span>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column text-muted">
                            <span><strong><?= \Altum\Date::get($row->date, 2) ?></strong></span>
                            <span><?= \Altum\Date::get($row->date, 3) ?> <i class="fa fa-fw fa-sm fa-arrow-right"></i> <?= \Altum\Date::get($row->last_date, 3) ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex">
                            <a href="<?= url('visitor/' . $row->visitor_id) ?>"><img src="<?= $row->icon ?>" class="visitor-avatar rounded-circle mr-3" alt="" /></a>

                            <div class="d-flex flex-column">
                                <div>
                                    <?php if(($row->custom_parameters = json_decode($row->custom_parameters, true)) && count($row->custom_parameters)): ?>
                                        <span data-toggle="tooltip" title="<?= sprintf($this->language->visitors->visitor->custom_parameters, count($row->custom_parameters)) ?>"><i class="fa fa-fw fa-fingerprint text-primary"></i></span>
                                    <?php endif ?>

                                    <span class="text-muted"><?= $this->language->visitors->visitor->from ?></span>

                                    <span>
                                    <?php if($row->country_name): ?>
                                        <img src="https://www.countryflags.io/<?= $row->country_code ?>/flat/16.png" />
                                    <?php endif ?>
                                        <?= $row->country_name ? $row->country_name : '-' ?>
                                </span>
                                </div>
                                <small class="text-muted"><?= $this->language->visitors->visitor->since ?> <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->date, 2) ?></span></small>
                            </div>
                        </div>
                    </td>

                    <td>
                        <a
                            href="#"
                            class="text-muted text-decoration-none"
                            data-toggle="modal"
                            data-target="#replay_delete"
                            data-session-id="<?= $row->session_id ?>"
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
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen">
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
                redirect(`replays?start_date=${start_date}&end_date=${end_date}`);
            }
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
