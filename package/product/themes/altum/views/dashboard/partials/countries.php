<?php defined('ALTUMCODE') || die() ?>

<div class="row mt-5">

    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
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

                <div class="mt-4" id="countries_result" data-limit="-1"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0">
            <div class="card-body pt-4">
                <div id="countries_map"></div>
            </div>
        </div>
    </div>

</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/svgMap.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/svgMap.min.js' ?>"></script>
<script>
    $(`#countries_map`).html($('#loading').html());

    /* Receive data */
    $('#countries_map').on('load', (event, data) => {

        data = JSON.parse(data);

        /* Prepare the data for the map */
        let values = {};

        for(let row of data.rows) {
            values[row.country_code] = {
                country: row.country_code,
                visitors: row.total
            }
        }

        /* Clear html of loading */
        $(`#countries_map`).html('');

        /* Create the map */
        new svgMap({
            targetElementID: 'countries_map',
            data: {
                data: {
                    country: {
                        name: '',
                        format: '{0}'
                    },
                    visitors: {
                        name: '',
                        format: '{0} <?= $this->language->analytics->visitors ?>',
                        thousandSeparator: $('[name="number_thousands_separator"]').val(),
                    },
                },
                applyData: 'visitors',
                values: values,
            },
            colorMin: '#90B5F9',
            colorMax: '#07193C',
            colorNoData: '#e1e1e1',
            hideFlag: true,
            noDataText: <?= json_encode($this->language->global->no_data) ?>
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
