<?php defined('ALTUMCODE') || die() ?>

(() => {
    let pixel_url_base = <?= json_encode(url()) ?>;
    let pixel_key = <?= json_encode($data->pixel_key) ?>;
    let pixel_exposed_identifier = <?= json_encode($this->settings->analytics->pixel_exposed_identifier) ?>;
    let pixel_goals = <?= json_encode($data->pixel_goals) ?>;

    /* Helper messages */
    let pixel_key_verify_message = <?= json_encode($this->language->pixel->success_message->verify) ?>;
    let pixel_key_dnt_message = <?= json_encode($this->language->pixel->info_message->dnt) ?>;

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-helpers.js' ?>

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-header.js' ?>

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-footer.js' ?>
})();
