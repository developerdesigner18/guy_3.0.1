<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li><a href="<?= url('replays') ?>"><?= $this->language->replays->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                    <li class="active" aria-current="page"><?= $this->language->replay->breadcrumb ?></li>
                </ol>
            </small>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div>
                <div class="d-flex align-items-baseline">
                    <h1 class="h3"><i class="fa fa-fw fa-xs fa-video text-gray-700"></i> <?= $this->language->replay->header ?></h1>

                    <?php if(!$this->team): ?>
                        <div class="dropdown ml-3">
                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                <i class="fa fa-ellipsis-v"></i>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a
                                            href="#"
                                            class="dropdown-item"
                                            data-toggle="modal"
                                            data-target="#replay_delete"
                                            data-session-id="<?= $data->visitor->session_id ?>"
                                    >
                                        <i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?>
                                    </a>
                                </div>
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </div>


            <?php
            /* Visitor */
            $icon = new \Jdenticon\Identicon([
                'value' => $data->visitor->visitor_uuid,
                'size' => 50
            ]);
            $data->visitor->icon = $icon->getImageDataUri();
            ?>

            <div class="col-12 col-md-4">
                <div class="card d-flex flex-row border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <?php if(($data->visitor->custom_parameters = json_decode($data->visitor->custom_parameters, true)) && count($data->visitor->custom_parameters)): ?>
                                <a href="<?= url('visitor/' . $data->visitor->visitor_id) ?>" class="mr-3" data-toggle="tooltip" title="<?= sprintf($this->language->visitors->visitor->custom_parameters, count($data->visitor->custom_parameters)) ?>">
                                <span>
                                    <i class="fa fa-fw fa-2x fa-fingerprint text-primary"></i>
                                </span>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('visitor/' . $data->visitor->visitor_id) ?>" class="mr-3">
                                    <img src="<?= $data->visitor->icon ?>" class="visitor-avatar rounded-circle" alt="" />
                                </a>
                            <?php endif ?>

                            <div>
                                <div>
                                    <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/countries/' . ($data->visitor->country_code ? strtolower($data->visitor->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                    <span class="align-middle"><?= $data->visitor->country_code ? get_country_from_country_code($data->visitor->country_code) :  $this->language->visitor->visitor->country_unknown ?></span>
                                </div>

                                <small class="text-muted"><?= $this->language->visitors->visitor->since ?> <span data-toggle="tooltip" title="<?= \Altum\Date::get($data->visitor->date, 1) ?>" class="text-muted"><?= \Altum\Date::get($data->visitor->date, 2) ?></span></small>
                            </div>
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

    <div class="notification-container mb-3"></div>

    <div class="clearfix d-flex justify-content-center" id="replay"></div>

    <div class="mt-5 row justify-content-between">
        <div class="col-12 mb-3 mb-md-0 col-md-4">
            <div class="card d-flex flex-row border-0 h-100">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->replay->events ?></small>

                    <div class="mt-3"><i class="fa fa-fw fa-eye"></i> <a href="#" data-toggle="modal" data-target="#replay_events_modal"><span class="h4"><?= nr($data->replay->events) ?></span></a></div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3 mb-md-0 col-md-4">
            <div class="card d-flex flex-row border-0 h-100">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->replay->duration ?></small>

                    <div class="mt-3"><i class="fa fa-fw fa-stopwatch"></i> <span class="h4"><?= (new \DateTime($data->replay->last_date))->diff((new \DateTime($data->replay->date)))->format('%H:%I:%S') ?></span></div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3 mb-md-0 col-md-4">
            <div class="card d-flex flex-row border-0 h-100">
                <div class="card-body">
                    <small class="text-muted"><?= $this->language->replay->time_range ?></small>

                    <div class="mt-3"><span class="h4"><?= \Altum\Date::get($data->replay->date, 3) ?> <i class="fa fa-fw fa-sm fa-arrow-right"></i> <?= \Altum\Date::get($data->replay->last_date, 3) ?></span></div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php ob_start() ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rrweb-player@latest/dist/style.css" />
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="https://cdn.jsdelivr.net/npm/rrweb-player@latest/dist/index.js"></script>

<script>
    /* Default loading state */
    let loading_html = $('#loading').html();
    $('#replay').html(loading_html);

    let player = null;

    $.ajax({
        type: 'GET',
        url: `replay/read/<?= $data->visitor->session_id ?>`,
        success: (result) => {

            $('#replay').html('');

            /* Start the replayer */
            player = new rrwebPlayer({
                target: document.querySelector('#replay'),
                data: {
                    events: result.rows,
                    autoPlay: false,
                },
            });

            /* Set the content for the replay events modal */
            $('#replay_events_result').html(result.replay_events_html);

        },
        dataType: 'json'
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
