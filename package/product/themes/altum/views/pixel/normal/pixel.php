<?php defined('ALTUMCODE') || die() ?>

(() => {
    let pixel_url_base = <?= json_encode(url()) ?>;
    let pixel_key = <?= json_encode($data->pixel_key) ?>;
    let pixel_exposed_identifier = <?= json_encode($this->settings->analytics->pixel_exposed_identifier) ?>;
    let pixel_track_events_children = <?= json_encode($data->pixel_track_events_children) ?>;
    let pixel_track_sessions_replays = <?= json_encode($data->pixel_track_sessions_replays) ?>;
    let pixel_heatmaps = <?= json_encode($data->pixel_heatmaps) ?>;
    let pixel_goals = <?= json_encode($data->pixel_goals) ?>;

    /* Helper messages */
    let pixel_key_verify_message = <?= json_encode($this->language->pixel->success_message->verify) ?>;
    let pixel_key_dnt_message = <?= json_encode($this->language->pixel->info_message->dnt) ?>;
    let pixel_key_optout_message = <?= json_encode($this->language->pixel->info_message->optout) ?>;

    <?php require_once ASSETS_PATH . 'js/pixel/normal/pixel-helpers.js' ?>

    <?php if(!empty($data->pixel_heatmaps) || $data->pixel_track_sessions_replays): ?>
        <?php require_once ASSETS_PATH . 'js/pixel/normal/pixel-helper-rr.js' ?>
    <?php endif ?>

    <?php require_once ASSETS_PATH . 'js/pixel/normal/pixel-header.js' ?>

    <?php require_once ASSETS_PATH . 'js/pixel/normal/pixel-footer.js' ?>
})();
