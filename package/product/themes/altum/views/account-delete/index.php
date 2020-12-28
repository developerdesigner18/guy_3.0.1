<?php defined('ALTUMCODE') || die() ?>

<div class="row">
    <?= $this->views['app_account_sidebar'] ?>

    <div class="col">
        <?php display_notifications() ?>

        <header class="">
            <div class="container">

                <h1 class="h3"><?= $this->language->account_delete->header ?></h1>
                <p class="text-muted"><?= $this->language->account_delete->subheader ?></p>

            </div>
        </header>

        <section class="container">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="current_password"><?= $this->language->account_delete->current_password ?></label>
                    <input type="password" id="current_password" name="current_password" class="form-control" />
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-secondary"><?= $this->language->global->delete ?></button>
            </form>

        </section>

    </div>
</div>

