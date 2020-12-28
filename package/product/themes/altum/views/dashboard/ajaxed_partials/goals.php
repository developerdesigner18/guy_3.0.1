<?php defined('ALTUMCODE') || die() ?>

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between mb-2">
        <div class="d-flex align-items-baseline">
            <small class="text-muted font-weight-bold text-uppercase mr-3"><?= $this->language->dashboard->goals->goal ?></small>

            <?php if(!$this->team): ?>
            <a href="#" data-toggle="modal" data-target="#goal_create_modal"><small><?= $this->language->global->create ?></small></a>
            <?php endif ?>
        </div>

        <div>
            <small class="text-muted font-weight-bold text-uppercase"><?= $this->language->analytics->{$data->by} ?></small>
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
            <?php $percentage = $data->total_sum > 0 ? round($row->total / $data->total_sum * 100, 1) : 0 ?>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <div>
                        <a href="#" data-toggle="modal" data-target="#goal_update_modal" data-goal-id="<?= $row->goal_id ?>" data-key="<?= $row->key ?>" data-type="<?= $row->type ?>" data-path="<?= ltrim($row->path, '/') ?>" data-name="<?= $row->name ?>"><?= $row->name ?></a>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="col"><?= nr($row->total) ?></div>

                        <div class="col p-0 text-right" style="min-width:50px;"><small class="text-muted"><?= $percentage ?>%</small></div>
                    </div>
                </div>

                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        <?php endforeach ?>

        <?php if($data->total_rows > count($data->rows)): ?>
            <a href="<?= url('dashboard/goals') ?>"><?= sprintf($this->language->global->view_x_more, nr($data->total_rows - count($data->rows))) ?></a>
        <?php endif ?>

    <?php endif ?>
</div>
