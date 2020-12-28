<?php defined('ALTUMCODE') || die() ?>

<h1><?= $this->language->help->introduction->header ?></h1>

<p class=""><?= $this->language->help->introduction->p1 ?></p>

<p class=""><?= $this->language->help->introduction->p2 ?></p>

<ul class="list-style-none ml-4 mt-5">
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">1</span>
        <a href="<?= url('help/setup') ?>"><?= $this->language->help->setup->link ?></a>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">2</span>
        <a href="<?= url('help/install') ?>"><?= sprintf($this->language->help->install->link, $this->settings->title) ?></a>
    </li>
    <li class="mb-3">
        <span class="badge badge-pill badge-primary mr-2">3</span>
        <a href="<?= url('help/verify') ?>"><?= $this->language->help->verify->link ?></a>
    </li>
</ul>
