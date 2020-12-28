<?php defined('ALTUMCODE') || die() ?>

<ul class="nav nav-pills nav-justified flex-column flex-md-row mt-5" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="utm-source-tab" data-toggle="pill" href="#utms_source_result" role="tab" aria-controls="utms_source_result" aria-selected="true">
            <?= $this->language->dashboard->utms->utm_source ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="utm-medium-tab" data-toggle="pill" href="#utms_medium_result" role="tab" aria-controls="utms_medium_result" aria-selected="false">
            <?= $this->language->dashboard->utms->utm_medium ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="utm-campaign-tab" data-toggle="pill" href="#utms_campaign_result" role="tab" aria-controls="utms_campaign_result" aria-selected="false">
            <?= $this->language->dashboard->utms->utm_campaign ?>
        </a>
    </li>
</ul>

<div class="mt-5">
    <div class="card border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h5 m-0"><?= $this->language->dashboard->utms->header ?></h2>
                </div>
                <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                    <i class="fa fa-fw fa-sm fa-link"></i>
                </span>
            </div>

            <div class="tab-content mt-4">
                <div class="tab-pane fade show active" id="utms_source_result" data-limit="-1" role="tabpanel" aria-labelledby="utm-source-tab"></div>
                <div class="tab-pane fade" id="utms_medium_result" data-limit="-1" role="tabpanel" aria-labelledby="utm-medium-tab"></div>
                <div class="tab-pane fade" id="utms_campaign_result" data-limit="-1" role="tabpanel" aria-labelledby="utm-campaign-tab"></div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
