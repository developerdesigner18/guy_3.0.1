<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div>
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                    <span class="badge badge-primary mr-1">BETA</span>
                    <h1 class="h3 text-break"><?= sprintf($this->language->heatmaps->header, $this->website->host . $this->website->path) ?></h1>
                </div>
            </div>

            <div class="col-auto p-0">
                <?php if(!$this->team): ?>
                    <?php if($this->user->plan_settings->websites_heatmaps_limit != -1 && $data->total_heatmaps >= $this->user->plan_settings->websites_heatmaps_limit): ?>
                        <button type="button" data-confirm="<?= $this->language->heatmaps->error_message->websites_heatmaps_limit ?>"  class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->heatmaps->heatmap->create ?></button>
                    <?php else: ?>
                        <button type="button" data-toggle="modal" data-target="#heatmap_create" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->heatmaps->heatmap->create ?></button>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if(!$data->total_heatmaps): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->heatmaps->basic->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->heatmaps->basic->no_data ?></h2>
            <p><?= sprintf($this->language->heatmaps->basic->no_data_help) ?></a></p>
        </div>

    <?php else: ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= $this->language->heatmaps->heatmap->heatmap ?></th>
                <th></th>
                <th><?= $this->language->heatmaps->heatmap->is_enabled ?></th>
                <th><?= $this->language->heatmaps->heatmap->date ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($data->heatmaps as $row): ?>
                <tr data-heatmap-id="<?= $row->heatmap_id ?>">
                    <td>
                        <div class="d-flex flex-column">
                            <span><a href="<?= url('heatmap/' . $row->heatmap_id) ?>"><?= $row->name ?></a></span>
                            <small class="text-muted"><?= $this->website->host . $this->website->path . $row->path ?></small>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex">
                            <a href="<?= url('heatmap/' . $row->heatmap_id . '/desktop') ?>" class="mr-2 <?= ($row->snapshot_id_desktop ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_desktop ? $this->language->heatmaps->heatmap->snapshot_id_desktop : $this->language->heatmaps->heatmap->snapshot_id_desktop_null) ?>"><i class="fa fa-fw fa-lg fa-desktop"></i></a>
                            <a href="<?= url('heatmap/' . $row->heatmap_id . '/tablet') ?>" class="mr-2 <?= ($row->snapshot_id_tablet ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_tablet ? $this->language->heatmaps->heatmap->snapshot_id_tablet : $this->language->heatmaps->heatmap->snapshot_id_tablet_null) ?>"><i class="fa fa-fw fa-lg fa-tablet"></i></a>
                            <a href="<?= url('heatmap/' . $row->heatmap_id . '/mobile') ?>" class="mr-2 <?= ($row->snapshot_id_mobile ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_mobile ? $this->language->heatmaps->heatmap->snapshot_id_mobile : $this->language->heatmaps->heatmap->snapshot_id_mobile_null) ?>"><i class="fa fa-fw fa-lg fa-mobile"></i></a>
                        </div>
                    </td>

                    <td>
                        <?php if($row->is_enabled): ?>
                        <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= $this->language->heatmaps->heatmap->is_enabled_true ?>
                        <?php else: ?>
                        <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->heatmaps->heatmap->is_enabled_false ?>
                        <?php endif ?>
                    </td>

                    <td>
                        <div><span data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->date, 2) ?></span></div>
                    </td>

                    <td>
                        <?php if(!$this->team): ?>
                        <div class="dropdown">
                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                <i class="fa fa-ellipsis-v"></i>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" data-toggle="modal" data-target="#heatmap_update" data-heatmap-id="<?= $row->heatmap_id ?>" data-name="<?= $row->name ?>" data-is-enabled="<?= (bool) $row->is_enabled ?>" class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= \Altum\Language::get()->global->update ?></a>
                                    <a href="#" data-toggle="modal" data-target="#heatmap_retake_snapshots" data-heatmap-id="<?= $row->heatmap_id ?>" class="dropdown-item"><i class="fa fa-fw fa-camera"></i> <?= \Altum\Language::get()->heatmaps->heatmap->retake_snapshots ?></a>
                                    <a href="#" data-toggle="modal" data-target="#heatmap_delete" data-heatmap-id="<?= $row->heatmap_id ?>" class="dropdown-item"><i class="fa fa-fw fa-times"></i> <?= \Altum\Language::get()->global->delete ?></a>
                                </div>
                            </a>
                        </div>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>

    <?php endif ?>

</section>

<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />
