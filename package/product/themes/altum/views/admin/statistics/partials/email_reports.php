<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-envelope fa-xs text-muted"></i> <?= $this->language->admin_statistics->email_reports->header ?></h2>
        <p class="text-muted"><?= $this->language->admin_statistics->email_reports->subheader ?></p>

        <div class="chart-container">
            <canvas id="email_reports"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let email_reports_chart = document.getElementById('email_reports').getContext('2d');
    color_gradient = email_reports_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(email_reports_chart, {
        type: 'line',
        data: {
            labels: <?= $data->email_reports_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode($this->language->admin_statistics->email_reports->chart_email_reports) ?>,
                data: <?= $data->email_reports_chart['email_reports'] ?? '[]' ?>,
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
