<?php defined('ALTUMCODE') || die() ?>

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between mb-2">
        <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->dashboard->countries->country ?></small>
        <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->analytics->{$data->by} ?></small>
    </div>

    <?php if(!count($data->rows)): ?>
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <div>
                    <span class="text-muted"><?= $this->language->dashboard->basic->no_data ?></span>
                </div>

                <div class="d-flex justify-content-end">
                    <div class="col">-</div>

                    <div class="col p-0 text-right" style="min-width:50px;"><small class="text-muted">-</small></div>
                </div>
            </div>
        </div>
    <?php else: ?>

        <?php foreach($data->rows as $row): ?>
            <?php $percentage = round($row->total / $data->total_sum * 100, 1) ?>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <div>
                        <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/countries/' . ($row->country_code ? strtolower($row->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />

                        <?php if($row->country_code): ?>
                            <a href="#" data-target="#cities_modal" data-toggle="modal" data-country-code="<?= $row->country_code ?>" class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : $this->language->visitor->visitor->country_unknown ?></a>
                        <?php else: ?>
                            <span class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : $this->language->visitor->visitor->country_unknown ?></span>
                        <?php endif ?>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="col"><?= nr($row->total) ?></div>

                        <div class="col p-0 text-right" style="min-width:50px;"><small class="text-muted"><?= $percentage ?>%</small></div>
                    </div>
                </div>

                <div class="progress" style="height: 5px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        <?php endforeach ?>

        <?php if($data->total_rows > count($data->rows)): ?>
            <a href="<?= url('dashboard/countries') ?>"><?= sprintf($this->language->global->view_x_more, nr($data->total_rows - count($data->rows))) ?></a>
        <?php endif ?>

    <?php endif ?>
</div>
