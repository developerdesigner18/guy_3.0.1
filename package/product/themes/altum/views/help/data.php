<?php defined('ALTUMCODE') || die() ?>

<h1><?= $this->language->help->data->header ?></h1>
<p><?= $this->language->help->data->p1 ?></p>

<h2 class="h3 mt-5"><?= $this->language->help->data->visitors->header ?></h2>
<p><?= $this->language->help->data->visitors->p1 ?></p>

<ul class="list-style-none ml-4 mt-5">
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->visitors->li1 ?>
    </li>

    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->visitors->li2 ?>
    </li>

    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->visitors->li3 ?>
    </li>

    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->visitors->li4 ?>
    </li>
</ul>

<h2 class="h3 mt-5"><?= $this->language->help->data->tracking->header ?></h2>
<p><?= $this->language->help->data->tracking->p1 ?></p>

<ul class="list-style-none ml-4 mt-5">
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->tracking->li1 ?>
    </li>

    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->tracking->li2 ?>
    </li>

    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
        <?= $this->language->help->data->tracking->li3 ?>
    </li>
    <?php endif ?>

    <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
        <li class="mb-3">
            <span class="badge badge-pill badge-primary mr-2"><i class="fa fa-fw fa-check"></i></span>
            <?= $this->language->help->data->tracking->li4 ?>
        </li>
    <?php endif ?>
</ul>

