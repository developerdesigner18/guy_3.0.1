<?php defined('ALTUMCODE') || die() ?>

<h1><?= $this->language->help->advanced->header ?></h1>
<p><?= $this->language->help->advanced->p1 ?></p>

<h2 class="h3 mt-5"><?= $this->language->help->advanced->custom_parameters->header ?></h2>
<p><?= $this->language->help->advanced->custom_parameters->p1 ?></p>
<p><?= $this->language->help->advanced->custom_parameters->p2 ?></p>
<p><?= $this->language->help->advanced->custom_parameters->p3 ?></p>
<p><?= $this->language->help->advanced->custom_parameters->p4 ?></p>

<pre id="pixel_key_html" class="pre-custom rounded">&lt;script async src="<?= url('pixel/12345678910111213') ?>" data-custom-parameters='{"name": "John Doe", "email": "john@example.com"}'&gt&lt;/script&gt</pre>

<p><?= $this->language->help->advanced->custom_parameters->p5 ?></p>

<h2 class="h3 mt-5"><?= $this->language->help->advanced->goals->header ?></h2>
<p><?= $this->language->help->advanced->goals->p1 ?></p>

<h3 class="h4 mt-5"><?= $this->language->help->advanced->goals->pageview->header ?></h3>
<p><?= $this->language->help->advanced->goals->pageview->p1 ?></p>
<p><?= $this->language->help->advanced->goals->pageview->p2 ?></p>

<h3 class="h4 mt-5"><?= $this->language->help->advanced->goals->custom->header ?></h3>
<p><?= $this->language->help->advanced->goals->custom->p1 ?></p>
<p><?= $this->language->help->advanced->goals->custom->p2 ?></p>
<p><?= $this->language->help->advanced->goals->custom->p3 ?></p>
<ul>
    <li><?= $this->language->help->advanced->goals->custom->li1 ?></li>
    <li><?= $this->language->help->advanced->goals->custom->li2 ?></li>
    <li><?= $this->language->help->advanced->goals->custom->li3 ?></li>
</ul>
<p><?= $this->language->help->advanced->goals->custom->p4 ?></p>

<pre id="pixel_key_html" class="pre-custom rounded"><?= $this->settings->analytics->pixel_exposed_identifier ?>.goal('my-goal');</pre>

<p><?= $this->language->help->advanced->goals->custom->p5 ?></p>
