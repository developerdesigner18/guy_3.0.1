<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-users fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->users->header ?></h2>

        <div class="chart-container">
            <canvas id="users"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-server fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->websites->header ?></h2>

        <div class="chart-container">
            <canvas id="websites"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-user-shield fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->teams->header ?></h2>

        <div class="chart-container">
            <canvas id="teams"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-user-friends fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->users_logs->header ?></h2>

        <div class="chart-container">
            <canvas id="users_logs"></canvas>
        </div>
    </div>
</div>

<?php if(in_array($this->settings->license->type, ['SPECIAL', 'Extended License'])): ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-tags fa-xs text-muted"></i> <?= $this->language->admin_statistics->growth->redeemed_codes->header ?></h2>

        <div class="chart-container">
            <canvas id="redeemed_codes"></canvas>
        </div>
    </div>
</div>
<?php endif ?>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let users_chart = document.getElementById('users').getContext('2d');
    color_gradient = users_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(users_chart, {
        type: 'line',
        data: {
            labels: <?= $data->users_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->growth->users->chart) ?>,
                    data: <?= $data->users_chart['users'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });


    let websites_chart = document.getElementById('websites').getContext('2d');
    color_gradient = websites_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(websites_chart, {
        type: 'line',
        data: {
            labels: <?= $data->websites_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->growth->websites->chart) ?>,
                    data: <?= $data->websites_chart['websites'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });


    let teams_chart = document.getElementById('teams').getContext('2d');
    color_gradient = teams_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(document.getElementById('teams').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->teams_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->growth->teams->chart) ?>,
                    data: <?= $data->teams_chart['teams'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });


    let users_logs_chart = document.getElementById('users_logs').getContext('2d');
    color_gradient = users_logs_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(users_logs_chart, {
        type: 'line',
        data: {
            labels: <?= $data->users_logs_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->growth->users_logs->chart) ?>,
                    data: <?= $data->users_logs_chart['users_logs'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });


    <?php if(in_array($this->settings->license->type, ['SPECIAL', 'Extended License'])): ?>
    let redeemed_codes_chart = document.getElementById('redeemed_codes').getContext('2d');
    color_gradient = redeemed_codes_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)')

    new Chart(redeemed_codes_chart, {
        type: 'line',
        data: {
            labels: <?= $data->redeemed_codes_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->growth->redeemed_codes->chart) ?>,
                    data: <?= $data->redeemed_codes_chart['redeemed_codes'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endif ?>

</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
