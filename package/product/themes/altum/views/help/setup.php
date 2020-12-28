<?php defined('ALTUMCODE') || die() ?>

<h1><?= sprintf($this->language->help->setup->header, $this->settings->title) ?></h1>

<p><?= $this->language->help->setup->p1 ?></p>

<ul class="list-style-none ml-4 mt-5">
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">1</span>
        <?= $this->language->help->setup->step1 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">2</span>
        <?= sprintf($this->language->help->setup->step2, url('websites')) ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">3</span>
        <?= $this->language->help->setup->step3 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">4</span>
        <?= $this->language->help->setup->step4 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">5</span>
        <?= $this->language->help->setup->step5 ?>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">6</span>
        <?= $this->language->help->setup->step6 ?>
    </li>
</ul>
