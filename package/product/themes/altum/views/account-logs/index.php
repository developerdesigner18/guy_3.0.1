<?php defined('ALTUMCODE') || die() ?>

<div class="row">
    <?= $this->views['app_account_sidebar'] ?>

    <div class="col">

        <header class="header">
            <div class="container">

                <h1 class="h3"><?= $this->language->account_logs->header ?></h1>
                <p class="text-muted"><?= $this->language->account_logs->subheader ?></p>

            </div>
        </header>

        <section class="container">

            <?php display_notifications() ?>

            <?php if(count($data->logs)): ?>

                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= $this->language->account_logs->logs->type ?></th>
                            <th><?= $this->language->account_logs->logs->ip ?></th>
                            <th><?= $this->language->account_logs->logs->date ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->logs as $row): ?>
                            <tr>
                                <td><?= $row->type ?></td>
                                <td><?= $row->ip ?></td>
                                <td class="text-muted"><?= \Altum\Date::get($row->date, 1) ?></td>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>

                <div class="mt-3"><?= $data->pagination ?></div>
            <?php else: ?>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->account_payments->payments->no_data ?>" />
                    <h2 class="h4 text-muted"><?= $this->language->account_logs->logs->no_data ?></h2>
                </div>
            <?php endif ?>
        </section>

    </div>
</div>
