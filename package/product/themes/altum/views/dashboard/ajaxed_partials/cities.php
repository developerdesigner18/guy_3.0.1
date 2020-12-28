<?php defined('ALTUMCODE') || die() ?>

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between mb-2">
        <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->dashboard->cities->city ?></small>
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
                    <div class="text-truncate">
                        <span><?= $row->city_name ? $row->city_name : $this->language->visitor->visitor->city_unknown ?></span>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="col">
                            <span>
                                <?= nr($row->total) ?>
                            </span>
                        </div>

                        <div class="col p-0 text-right" style="min-width:50px;"><small class="text-muted"><?= $percentage ?>%</small></div>
                    </div>
                </div>

                <div class="progress" style="height: 5px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        <?php endforeach ?>

    <?php endif ?>
</div>
