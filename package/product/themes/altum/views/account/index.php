<?php defined('ALTUMCODE') || die() ?>

<div class="row">
    <?= $this->views['app_account_sidebar'] ?>

    <div class="col">

        <header class="header">
            <div class="container">

                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <img src="<?= get_gravatar($this->user->email) ?>" class="d-none d-md-block mr-3 user-avatar" />

                        <div class="d-flex flex-column">
                            <h1 class="h3"><?= $this->user->name ?></h1>

                            <div>
                                <span class="badge badge-success"><?= sprintf($this->language->account->plan->header, $this->user->plan->name) ?></span>

                                <?php if($this->user->plan_id != 'free'): ?>
                                    <small><?= sprintf($this->language->account->plan->subheader, '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>') ?></small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <?php require THEME_PATH . 'views/partials/ads_header.php' ?>

        <section class="container">

            <?php display_notifications() ?>

            <form action="" method="post" role="form" class="mt-5">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="row">
                    <div class="col-12 col-xl-4">
                        <h2 class="h4"><?= $this->language->account->settings->header ?></h2>
                        <p class="text-muted"><?= $this->language->account->settings->subheader ?></p>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="name"><?= $this->language->account->settings->name ?></label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= $this->user->name ?>" />
                        </div>

                        <div class="form-group">
                            <label for="email"><?= $this->language->account->settings->email ?></label>
                            <input type="text" id="email" name="email" class="form-control" value="<?= $this->user->email ?>" />
                        </div>

                        <div class="form-group">
                            <label for="timezone"><?= $this->language->account->settings->timezone ?></label>
                            <select id="timezone" name="timezone" class="form-control">
                                <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($this->user->timezone == $timezone ? 'selected' : null) . '>' . $timezone . '</option>' ?>
                            </select>
                            <small class="text-muted"><?= $this->language->account->settings->timezone_help ?></small>
                        </div>
                    </div>
                </div>

                <div class="mt-5"></div>

                <div class="row">
                    <div class="col-12 col-xl-4">
                        <h2 class="h4"><?= $this->language->account->twofa->header ?></h2>
                        <p class="text-muted"><?= $this->language->account->twofa->subheader ?></p>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label><?= $this->language->account->twofa->is_enabled ?></label>

                            <select name="twofa_is_enabled" class="form-control">
                                <option value="1" <?= $this->user->twofa_secret ? 'selected' : null ?>><?= $this->language->global->yes ?></option>
                                <option value="0" <?= !$this->user->twofa_secret ? 'selected' : null ?>><?= $this->language->global->no ?></option>
                            </select>
                        </div>

                        <div id="twofa_container">
                            <?php if(!$this->user->twofa_secret): ?>
                                <div class="form-group">
                                    <label><?= $this->language->account->twofa->qr ?></label>
                                    <p class="text-muted"><?= $this->language->account->twofa->qr_help ?></p>

                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="mb-3 mb-md-0 mr-md-5">
                                            <img src="<?= $data->twofa_image ?>" alt="<?= $this->language->account->twofa->qr ?>" />
                                        </div>

                                        <div>
                                            <label><?= $this->language->account->twofa->secret ?></label>
                                            <p class="text-muted"><?= $this->language->account->twofa->secret_help ?></p>

                                            <p class="h5"><?= $data->twofa_secret ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="twofa_token"><?= $this->language->account->twofa->verify ?></label>
                                    <p class="text-muted"><?= $this->language->account->twofa->verify_help ?></p>
                                    <input type="text" id="twofa_token" name="twofa_token" class="form-control" value="" autocomplete="off" />
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="mt-5"></div>

                <div class="row">
                    <div class="col-12 col-xl-4">
                        <h2 class="h4"><?= $this->language->account->change_password->header ?></h2>
                        <p class="text-muted"><?= $this->language->account->change_password->subheader ?></p>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="old_password"><?= $this->language->account->change_password->current_password ?></label>
                            <input type="password" id="old_password" name="old_password" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="new_password"><?= $this->language->account->change_password->new_password ?></label>
                            <input type="password" id="new_password" name="new_password" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="repeat_password"><?= $this->language->account->change_password->repeat_password ?></label>
                            <input type="password" id="repeat_password" name="repeat_password" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-xl-4"></div>

                    <div class="col">
                        <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
                    </div>
                </div>
            </form>

            <div class="mt-8 row justify-content-between">
                <div class="col-12 col-md-auto">
                    <h2 class="h4"><?= $this->language->account->delete->header ?></h2>
                    <p class="text-muted"><?= $this->language->account->delete->subheader ?></p>
                </div>

                <div class="col-12 col-md-auto">
                    <a href="<?= url('account/delete' . \Altum\Middlewares\Csrf::get_url_query()) ?>" class="btn btn-secondary" data-confirm="<?= $this->language->account->delete->confirm_message ?>"><?= $this->language->global->delete ?></a>
                </div>
            </div>
        </section>

    </div>
</div>

<?php if(!$this->user->twofa_secret): ?>
    <?php ob_start() ?>
    <script>
        let twofa = () => {
            let is_enabled = parseInt($('select[name="twofa_is_enabled"]').find(':selected').val());

            if(is_enabled) {
                $('#twofa_container').show();
            } else {
                $('#twofa_container').hide();

            }
        };

        twofa();

        $('select[name="twofa_is_enabled"]').on('change', twofa);
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
