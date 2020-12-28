<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li><a href="<?= url('heatmaps') ?>"><?= $this->language->heatmaps->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                    <li class="active" aria-current="page"><?= $this->language->heatmap->breadcrumb ?></li>
                </ol>
            </small>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="mb-3 mb-md-0">
                <div class="d-flex align-items-baseline">
                    <h1 class="h3"><i class="fa fa-fw fa-xs fa-fire text-gray-700"></i> <?= $data->heatmap->name ?></h1>

                    <?php if(!$this->team): ?>
                    <div class="dropdown ml-3">
                        <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                            <i class="fa fa-ellipsis-v"></i>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" data-toggle="modal" data-target="#heatmap_update" data-heatmap-id="<?= $data->heatmap->heatmap_id ?>" data-name="<?= $data->heatmap->name ?>" data-is-enabled="<?= $data->heatmap->is_enabled ?>" class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->update ?></a>
                                <a href="#" data-toggle="modal" data-target="#heatmap_retake_snapshots" data-heatmap-id="<?= $data->heatmap->heatmap_id ?>" class="dropdown-item"><i class="fa fa-fw fa-camera"></i> <?= $this->language->heatmaps->heatmap->retake_snapshots ?></a>
                                <a href="#" data-toggle="modal" data-target="#heatmap_delete" data-heatmap-id="<?= $data->heatmap->heatmap_id ?>" class="dropdown-item"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                            </div>
                        </a>
                    </div>
                    <?php endif ?>
                </div>
                <div>
                    <?php if($data->heatmap->is_enabled): ?>
                        <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= $this->language->heatmaps->heatmap->is_enabled_true ?></span>
                    <?php else: ?>
                        <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->heatmaps->heatmap->is_enabled_false ?></span>
                    <?php endif ?>

                    <small class="ml-3 text-muted"><?= $this->website->host . $this->website->path . $data->heatmap->path ?></small>
                </div>
            </div>

            <div>
                <div class="btn-group" role="group">
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/desktop') ?>" class="btn btn-sm <?= $data->snapshot_type == 'desktop' ? 'btn-primary' : 'btn-secondary' ?>">
                        <i class="fa fa-fw fa-desktop"></i> <?= $data->snapshot_type == 'desktop' ? sprintf($this->language->heatmap->click, '<span id="heatmap_data_count"></span>') : null ?>
                    </a>
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/tablet') ?>" class="btn btn-sm <?= $data->snapshot_type == 'tablet' ? 'btn-primary' : 'btn-secondary' ?>">
                        <i class="fa fa-fw fa-tablet"></i> <?= $data->snapshot_type == 'tablet' ? sprintf($this->language->heatmap->click, '<span id="heatmap_data_count"></span>') : null ?>
                    </a>
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/mobile') ?>" class="btn btn-sm <?= $data->snapshot_type == 'mobile' ? 'btn-primary' : 'btn-secondary' ?>">
                        <i class="fa fa-fw fa-mobile"></i> <?= $data->snapshot_type == 'mobile' ? sprintf($this->language->heatmap->click, '<span id="heatmap_data_count"></span>') : null ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <div class="notification-container mb-3"></div>

    <?php if(!$data->snapshot): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/collecting.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->heatmap->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->heatmap->no_data ?></h2>
            <p><?= $this->language->heatmap->no_data_help ?></a></p>
        </div>

    <?php else: ?>

        <div class="position-relative">
            <div id="heatmap-loading"></div>

            <div id="heatmap-container" class="heatmap-container shadow-md rounded-sm" style="display: none;">
                <div id="heatmap-inner" class="position-relative">
                    <canvas id="heatmap-canvas" class="heatmap-canvas"></canvas>
                </div>
            </div>
        </div>

    <?php endif ?>
</section>


<?php if($data->snapshot): ?>
    <?php ob_start() ?>
    <script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/simpleheat.js' ?>"></script>
    <script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/rrweb.mod.js' ?>"></script>

    <script>
        /* Default loading state */
        let loading_html = $('#loading').html();
        $('#heatmap-loading').html(loading_html);

        let player = null;
        let simpleheatdata = null;

        /* Request the data */
        $.ajax({
            type: 'GET',
            url: `heatmap/read/<?= $data->heatmap->heatmap_id ?>/<?= $data->snapshot->type ?>`,
            success: (result) => {

                /* Generate the heatmap */
                player = new rrweb.Replayer(result.snapshot_data, {
                    root: document.querySelector('#heatmap-inner'),
                });

                player.play();

                /* Save the data for the heatmap */
                simpleheatdata = result.heatmap_data;

                /* Count */
                $('#heatmap_data_count').text(result.heatmap_data_count);

                /* Remove the loading state */
                $('#heatmap-loading').html('');

                /* Display it */
                $('#heatmap-container').fadeIn();

                /* Draw the heatmap after x time */
                setTimeout(() => {
                    heatmap_draw();
                }, 1000);

                /* Timeout loading after 5 seconds */
                setTimeout(() => {
                    window.stop();
                }, 5000);

            },
            dataType: 'json'
        });

        /* Prepare the heatmap */
        let heatmap_resize = () => {

            /* Default iframe height */
            let width = $('#heatmap-inner iframe').data('width');

            /* Full iframe height */
            let height = document.querySelector('#heatmap-inner iframe').contentWindow.document.querySelector('body').scrollHeight;

            $('#heatmap-container').css('width', width);
            $('#heatmap-canvas').attr('width', width).attr('height', height);
            $('#heatmap-container iframe').attr('width', width).attr('height', height);
            $('#heatmap-inner').css('width', width).css('height', height);

            heatmap_proper_scale()
        };

        let heatmap_proper_scale = () => {
            let container_width = $('section[class="container"]').width();
            let heatmap_container_width = $('#heatmap-container').width();

            if(heatmap_container_width > container_width) {
                let transform_scale = Math.round(container_width / heatmap_container_width * 100);
                $('#heatmap-container').css('transform', `scale(0.${transform_scale})`);

                let margin_bottom = (1 - transform_scale / 100) * parseInt($('#heatmap-container').css('height'));
                let margin_bottom_px = `-${margin_bottom}px`;
                $('#heatmap-container').css('margin-bottom', margin_bottom_px);
            }

        };

        $(window).on('resize', heatmap_proper_scale);

        let heatmap_draw = () => {
            heatmap_resize();

            simpleheat('heatmap-canvas').data(simpleheatdata).draw();
        };
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
