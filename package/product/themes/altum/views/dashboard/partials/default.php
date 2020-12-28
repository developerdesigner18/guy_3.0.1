<?php defined('ALTUMCODE') || die() ?>

<div class="chart-container">
    <canvas id="logs_chart"></canvas>
</div>

<div class="row mt-5">

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->paths->header ?></h2>

                        <a href="<?= url('dashboard/paths') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-copy"></i>
                    </span>
                </div>

                <div class="mt-4" id="paths_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->referrers->header ?></h2>

                        <a href="<?= url('dashboard/referrers') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-random"></i>
                    </span>
                </div>

                <div class="mt-4" id="referrers_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->countries->header ?></h2>

                        <a href="<?= url('dashboard/countries') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-globe"></i>
                    </span>
                </div>

                <div class="mt-4" id="countries_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->utms->header ?></h2>

                        <a href="<?= url('dashboard/utms') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-link"></i>
                    </span>
                </div>

                <div class="mt-4" id="utms_source_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->screen_resolutions->header ?></h2>

                        <a href="<?= url('dashboard/screen_resolutions') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-desktop"></i>
                    </span>
                </div>

                <div class="mt-4" id="screen_resolutions_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->browser_languages->header ?></h2>

                        <a href="<?= url('dashboard/browser_languages') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-language"></i>
                    </span>
                </div>

                <div class="mt-4" id="browser_languages_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->operating_systems->header ?></h2>

                        <a href="<?= url('dashboard/operating_systems') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-server"></i>
                    </span>
                </div>

                <div class="mt-4" id="operating_systems_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->device_types->header ?></h2>

                        <a href="<?= url('dashboard/device_types') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-laptop"></i>
                    </span>
                </div>

                <div class="mt-4" id="device_types_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= $this->language->dashboard->browser_names->header ?></h2>

                        <a href="<?= url('dashboard/browser_names') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= $this->language->global->view_more ?>"><i class="align-self-end fa fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-window-restore"></i>
                    </span>
                </div>

                <div class="mt-4" id="browser_names_result"></div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>

<script>
    <?php if(count($data->logs)): ?>
    /* Charts */
    Chart.defaults.global.elements.line.borderWidth = 4;
    Chart.defaults.global.elements.point.radius = 3;
    Chart.defaults.global.elements.point.borderWidth = 4;

    let impressions_chart = document.getElementById('logs_chart').getContext('2d');

    let gradient = impressions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(17, 85, 212, .65)');
    gradient.addColorStop(1, 'rgba(17, 85, 212, 0.025)');

    let labels_alt = <?= $data->logs_chart['labels_alt'] ?>;

    new Chart(impressions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [
                {
                    data: <?= $data->logs_chart['pageviews'] ?? '[]' ?>,
                    backgroundColor: gradient,
                    borderColor: '#1155D4',
                    fill: true,
                    label: <?= json_encode($this->language->dashboard->basic->chart->pageviews) ?>
                },
                {
                    data: <?= $data->logs_chart['sessions'] ?? '[]' ?>,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'rgba(0,0,0,0)',
                    fill: false,
                    showLine: false,
                    borderWidth: 0,
                    pointBorderWidth: 0,
                    pointBorderRadius: 0,
                    label: <?= json_encode($this->language->dashboard->basic->chart->sessions) ?>
                },
                {
                    data: <?= $data->logs_chart['visitors'] ?? '[]' ?>,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'rgba(0,0,0,0)',
                    fill: false,
                    showLine: false,
                    borderWidth: 0,
                    pointBorderWidth: 0,
                    pointBorderRadius: 0,
                    label: <?= json_encode($this->language->dashboard->basic->chart->visitors) ?>
                }
            ]
        },
        options: {
            elements: {
                line: {
                    tension: 0
                }
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    title: (tooltipItem, data) => {
                        return labels_alt[tooltipItem[0].index];
                    },
                    label: (tooltipItem, data) => {
                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                        return `${nr(value)} - ${data.datasets[tooltipItem.datasetIndex].label}`;
                    }
                }
            },
            title: {
                display: false
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        userCallback: (value, index, values) => {
                            if (Math.floor(value) === value) {
                                return nr(value);
                            }
                        },
                        beginAtZero: true
                    },
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
