<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div>
                <h1 class="h3 text-break"><?= sprintf($this->language->realtime->header, $this->website->host . $this->website->path) ?></h1>
                <p class="m-0"><?= $this->language->realtime->subheader ?></p>
                <p><small class="text-muted"><i class="fa fa-fw fa-question-circle"></i> <?= $this->language->realtime->info ?></small></p>
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
            <span><?= $this->language->realtime->visitors->subheader ?></span>
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
                <div class="card-body pt-4">
                    <div class="d-flex flex-column align-items-center">
                        <span class="analytics-card-icon round-circle-lg bg-gray-200 text-primary p-3">
                            <i class="fa fa-fw fa-2x fa-globe"></i>
                        </span>
                        <h5 class="card-title pt-4"><?= $this->language->dashboard->countries->header ?></h5>
                    </div>

                    <div class="mt-4" id="realtime_countries_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body pt-4">
                    <div class="d-flex flex-column align-items-center">
                        <span class="analytics-card-icon round-circle-lg bg-gray-200 text-primary p-3">
                            <i class="fa fa-fw fa-2x fa-laptop"></i>
                        </span>
                        <h5 class="card-title pt-4"><?= $this->language->dashboard->device_types->header ?></h5>
                    </div>

                    <div class="mt-4" id="realtime_device_types_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body pt-4">
                    <div class="d-flex flex-column align-items-center">
                        <span class="analytics-card-icon round-circle-lg bg-gray-200 text-primary p-3">
                            <i class="fa fa-fw fa-2x fa-copy"></i>
                        </span>
                        <h5 class="card-title pt-4"><?= $this->language->dashboard->paths->header ?></h5>
                    </div>

                    <div class="mt-4" id="realtime_paths_result"></div>
                </div>
            </div>
        </div>
    </div>

</section>

<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
    <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

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
                        }
                    },
                    min: 0
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
    let start_date = 'now';
    let end_date = 'now';
    let request_subtype = 'realtime';
    let dashboard_ajax_url = `${url}dashboard-ajax?website_id=${website_id}&request_subtype=${request_subtype}&start_date=${start_date}&end_date=${end_date}&global_token=${global_token}`;

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
