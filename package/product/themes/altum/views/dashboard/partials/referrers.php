<?php defined('ALTUMCODE') || die() ?>

<ul class="nav nav-pills nav-justified flex-column flex-md-row mt-5" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#referrers" role="tab" aria-controls="referrers_result" aria-selected="true">
            <?= $this->language->dashboard->referrers->header ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#social_media_referrers" role="tab" aria-controls="social_media_referrers_result" aria-selected="false">
            <?= $this->language->dashboard->social_media_referrers->header ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#search_engines_referrers" role="tab" aria-controls="search_engines_referrers_result" aria-selected="false">
            <?= $this->language->dashboard->search_engines_referrers->header ?>
        </a>
    </li>
</ul>

<div class="tab-content mt-5">
    <div class="tab-pane fade show active" id="referrers" role="tabpanel" aria-labelledby="referrers_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->referrers->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-random"></i>
                    </span>
                </div>

                <div class="mt-4" id="referrers_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="social_media_referrers" role="tabpanel" aria-labelledby="social_media_referrers_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->social_media_referrers->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-share-alt"></i>
                    </span>
                </div>

                <div class="mt-4" id="social_media_referrers_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="search_engines_referrers" role="tabpanel" aria-labelledby="search_engines_referrers_result">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= $this->language->dashboard->search_engines_referrers->header ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fa fa-fw fa-sm fa-search"></i>
                    </span>
                </div>

                <div class="mt-4" id="search_engines_referrers_result" data-limit="-1" data-bounce-rate="true"></div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
