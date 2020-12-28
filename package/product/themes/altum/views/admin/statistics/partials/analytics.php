<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-seedling fa-xs text-muted"></i> <?= $this->language->admin_statistics->analytics->sessions_events->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->analytics->sessions_events->subheader ?></p>

        <div class="chart-container">
            <canvas id="sessions_events"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-bell fa-xs text-muted"></i> <?= $this->language->admin_statistics->analytics->events_children->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->analytics->events_children->subheader ?></p>

        <div class="chart-container">
            <canvas id="events_children"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-video fa-xs text-muted"></i> <?= $this->language->admin_statistics->analytics->sessions_replays->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->analytics->sessions_replays->subheader ?></p>

        <div class="chart-container">
            <canvas id="sessions_replays"></canvas>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-bullseye fa-xs text-muted"></i> <?= $this->language->admin_statistics->analytics->goals_conversions->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->analytics->goals_conversions->subheader ?></p>

        <div class="chart-container">
            <canvas id="goals_conversions"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let sessions_events_chart = document.getElementById('sessions_events').getContext('2d');
    color_gradient = sessions_events_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(sessions_events_chart, {
        type: 'line',
        data: {
            labels: <?= $data->sessions_events_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->admin_statistics->analytics->sessions_events->chart_sessions_events) ?>,
                data: <?= $data->sessions_events_chart['sessions_events'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });


    let click_color = css.getPropertyValue('--teal');
    let form_color = css.getPropertyValue('--indigo');
    let resize_color = css.getPropertyValue('--cyan');
    let scroll_color = css.getPropertyValue('--blue');

    /* Display chart */
    new Chart(document.getElementById('events_children').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->events_children_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode($this->language->admin_statistics->analytics->events_children->chart_click) ?>,
                    data: <?= $data->events_children_chart['click'] ?? '[]' ?>,
                    backgroundColor: click_color,
                    borderColor: click_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->analytics->events_children->chart_form) ?>,
                    data: <?= $data->events_children_chart['form'] ?? '[]' ?>,
                    backgroundColor: form_color,
                    borderColor: form_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->analytics->events_children->chart_resize) ?>,
                    data: <?= $data->events_children_chart['resize'] ?? '[]' ?>,
                    backgroundColor: resize_color,
                    borderColor: resize_color,
                    fill: false
                },
                {
                    label: <?= json_encode($this->language->admin_statistics->analytics->events_children->chart_scroll) ?>,
                    data: <?= $data->events_children_chart['scroll'] ?? '[]' ?>,
                    backgroundColor: scroll_color,
                    borderColor: scroll_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });


    /* Display chart */
    let sessions_replays_chart = document.getElementById('sessions_replays').getContext('2d');
    color_gradient = sessions_replays_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(sessions_replays_chart, {
        type: 'line',
        data: {
            labels: <?= $data->sessions_replays_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->admin_statistics->analytics->sessions_replays->chart_sessions_replays) ?>,
                data: <?= $data->sessions_replays_chart['sessions_replays'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });


    /* Display chart */
    let goals_conversions_chart = document.getElementById('goals_conversions').getContext('2d');
    color_gradient = goals_conversions_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(goals_conversions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->goals_conversions_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->admin_statistics->analytics->goals_conversions->chart_goals_conversions) ?>,
                data: <?= $data->goals_conversions_chart['goals_conversions'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
