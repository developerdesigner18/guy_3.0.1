<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div>
                <h1 class="h3 text-break"><?= sprintf($this->language->realtime->header, $this->website->host . $this->website->path) ?></h1>
                <p class="text-muted"><?= $this->language->realtime->subheader ?></p>
            </div>
        </div>

    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <div class="row">
        <div class="col-12 col-md-4 text-center">
            <div id="realtime_visitors_result" class="h1"></div>

            <?php if($this->website->tracking_type == 'normal'): ?>
            <span><?= $this->language->realtime->visitors ?></span>
            <?php endif ?>

            <?php if($this->website->tracking_type == 'lightweight'): ?>
                <span><?= $this->language->realtime->pageviews ?></span>
            <?php endif ?>
        </div>

        <div class="col-12 col-md-8">
            <div class="chart-container">
                <canvas id="logs_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= $this->language->dashboard->countries->header ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fa fa-fw fa-sm fa-globe"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_countries_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= $this->language->dashboard->device_types->header ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fa fa-fw fa-sm fa-laptop"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_device_types_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= $this->language->dashboard->paths->header ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fa fa-fw fa-sm fa-copy"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_paths_result"></div>
                </div>
            </div>
        </div>
    </div>

</section>

<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>

<script>
    /* Chart */
    Chart.defaults.global.elements.line.borderWidth = 4;
    Chart.defaults.global.elements.point.radius = 3;
    Chart.defaults.global.elements.point.borderWidth = 4;

    let chart = document.getElementById('logs_chart').getContext('2d');

    let gradient = chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(17, 85, 212, .7)');
    gradient.addColorStop(1, 'rgba(17, 85, 212, 0.05)');

    let pageviews_chart = new Chart(chart, {
        type: 'line',
        data: {
            labels: null,
            datasets: [{
                data: null,
                backgroundColor: gradient,
                borderColor: '#1155D4',
                fill: true,
                label: <?= json_encode($this->language->dashboard->basic->chart->pageviews) ?>
            }]
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

    /* Basic data to use for fetching extra data */
    let website_id = $('input[name="website_id"]').val();
    let tracking_type = <?= json_encode($this->website->tracking_type) ?>;
    let start_date = 'now';
    let end_date = 'now';
    let request_subtype = 'realtime';
    let dashboard_ajax_url = `${url}dashboard-ajax-${tracking_type}?website_id=${website_id}&request_subtype=${request_subtype}&start_date=${start_date}&end_date=${end_date}&global_token=${global_token}`;

    let load = () => {
        for (let request_type of ['realtime_visitors', 'realtime_paths', 'realtime_countries', 'realtime_device_types']) {

            if ($(`#${request_type}_result`).length) {

                let limit = $(`#${request_type}_result`).data('limit') || 10;

                /* Put the loading placeholders */
                $(`#${request_type}_result`).html($('#loading').html());

                $.ajax({
                    type: 'GET',
                    url: `${dashboard_ajax_url}&request_type=${request_type}&limit=${limit}`,
                    success: (data) => {

                        $(`#${request_type}_result`).html(data.details.html);

                    },
                    dataType: 'json'
                });
            }
        }

        $.ajax({
            type: 'GET',
            url: `${dashboard_ajax_url}&request_type=realtime_chart_data&limit=10`,
            success: (data) => {

                let labels = JSON.parse(data.details.logs_chart_labels);
                let pageviews_dataset_data = JSON.parse(data.details.logs_chart_pageviews);

                pageviews_chart.data.labels = labels;
                pageviews_chart.data.datasets[0].data = pageviews_dataset_data;

                pageviews_chart.update();

            },
            dataType: 'json'
        });
    };

    load();

    setInterval(load, 10000);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
