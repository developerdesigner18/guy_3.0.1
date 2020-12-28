<?php defined('ALTUMCODE') || die() ?>

<h1><?= sprintf($this->language->help->install->header, $this->settings->title) ?></h1>

<p><?= $this->language->help->install->p1 ?></p>

<p><?= $this->language->help->install->p2 ?></p>

<ul class="list-style-none ml-4 mt-5">
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">1</span>
        <?= $this->language->help->install->step1 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">2</span>
        <?= sprintf($this->language->help->install->step2, url('websites')) ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">3</span>
        <?= $this->language->help->install->step3 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">4</span>
        <?= $this->language->help->install->step4 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">5</span>
        <?= $this->language->help->install->step5 ?>
    </li>
</ul>
