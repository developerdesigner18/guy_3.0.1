<?php defined('ALTUMCODE') || die() ?>

<div class="card border-0">
    <div class="card-body">
        <img src="<?= $data->image ?>" class="img-fluid index-testimonial-avatar" />

        <p class="mt-5">
            <span class="text-primary-800 font-weight-bold h4">“</span>
            <span class="font-italic text-muted"><?= $data->text ?></span>
            <span class="text-primary-800 font-weight-bold h4">”</span>
        </p>
        <div class="blockquote-footer mt-4">
            <span class="font-weight-bold"><?= $data->name ?></span>, <span class="text-muted"><?= $data->attribute ?></span>
        </div>
    </div>
</div>
