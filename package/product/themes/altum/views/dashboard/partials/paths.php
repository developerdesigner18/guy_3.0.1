<?php defined('ALTUMCODE') || die() ?>

<ul class="nav nav-pills nav-justified flex-column flex-md-row mt-5" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#paths" role="tab" aria-controls="paths_result" aria-selected="true">
            <?= $this->language->dashboard->paths->header ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#landing_paths" role="tab" aria-controls="landing_paths_result" aria-selected="false">
            <?= $this->language->dashboard->landing_paths->header ?>
        </a>
    </li>

    <?php if($this->website->tracking_type == 'normal'): ?>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#exit_paths" role="tab" aria-controls="exit_paths_result" aria-selected="false">
            <?= $this->language->dashboard->exit_paths->header ?>
        </a>
    </li>
    <?php endif ?>
</ul>

<div class="tab-content mt-5">
    <div class="tab-pane fade show active" id="paths" role="tabpanel" aria-labelledby="paths_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->paths->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-copy"></i>
                    </span>
                </div>

                <div class="mt-4" id="paths_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="landing_paths" role="tabpanel" aria-labelledby="landing_paths_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->landing_paths->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-plane-arrival"></i>
                    </span>
                </div>

                <div class="mt-4" id="landing_paths_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>

    <?php if($this->website->tracking_type == 'normal'): ?>
    <div class="tab-pane fade" id="exit_paths" role="tabpanel" aria-labelledby="exit_paths_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->exit_paths->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-door-open"></i>
                    </span>
                </div>

                <div class="mt-4" id="exit_paths_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>

<?php ob_start() ?>
<script>

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
