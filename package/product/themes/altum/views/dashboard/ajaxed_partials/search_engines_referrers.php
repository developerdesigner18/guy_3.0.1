<?php defined('ALTUMCODE') || die() ?>

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between mb-2">
        <div>
            <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->dashboard->referrers->referrer_host ?></small>
        </div>

        <div class="d-flex justify-content-end">
            <?php if($data->options['bounce_rate']): ?>
                <div class="col">
                    <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->analytics->{$data->by} ?></small>
                </div>

                <div class="col p-0 text-right">
                    <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->analytics->bounce_rate ?></small>
                </div>
            <?php else: ?>
                <div class="col p-0">
                    <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->analytics->{$data->by} ?></small>
                </div>
            <?php endif ?>
        </div>
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
            <?php $bounce_rate = !isset($row->bounces) || is_null($row->bounces) ? null : round($row->bounces / $row->total * 100, 1) ?>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <div class="text-truncate">
                        <img src="https://external-content.duckduckgo.com/ip3/<?= $row->referrer ?>.ico" class="img-fluid icon-favicon mr-1" />
                        <span class="align-middle">
                            <?php
                            switch($row->referrer){
                                case 'google.com': echo 'Google'; break;
                                case 'bing.com': echo 'Bing'; break;
                                case 'yahoo.com': echo 'Yahoo'; break;
                                case 'yandex.com': echo 'Yandex'; break;
                                case 'ecosia.org': echo 'Ecosia'; break;
                            }
                            ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="col">
                            <span>
                                <?= nr($row->total) ?>
                            </span>
                        </div>

                        <div class="col p-0 text-right" style="min-width:50px;"><small class="text-muted"><?= $percentage ?>%</small></div>

                        <?php if($data->options['bounce_rate']): ?>
                            <div class="col p-0 text-right" style="min-width:70px;"><small class="text-muted"><?= !is_null($bounce_rate) ? $bounce_rate . '%' : '-' ?></small></div>
                        <?php endif ?>
                    </div>
                </div>

                <div class="progress" style="height: 5px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        <?php endforeach ?>

        <?php if($data->total_rows > count($data->rows)): ?>
            <a href="<?= url('dashboard/referrers') ?>"><?= sprintf($this->language->global->view_x_more, nr($data->total_rows - count($data->rows))) ?></a>
        <?php endif ?>

    <?php endif ?>
</div>
