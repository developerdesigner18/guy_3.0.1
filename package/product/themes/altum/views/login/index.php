<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="card border-0 col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card-body">
                <?php display_notifications() ?>

                <form action="" method="post" class="mt-4" role="form">
                    <div class="form-group ">
                        <input type="text" name="email" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->email_placeholder ?>" value="<?= $data->values['email'] ?>" aria-label="<?= $this->language->login->form->email ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->password_placeholder ?>" aria-label="<?= $this->language->login->form->password ?>" <?= $data->login_account ? 'value="' . $data->values['password'] . '"' : null ?> required="required" />
                    </div>

                    <?php if($data->login_account && $data->login_account->twofa_secret && $data->login_account->active): ?>
                        <div class="form-group">
                            <input type="text" name="twofa_token" class="form-control form-control-lg" placeholder="<?= $this->language->login->form->twofa_token_placeholder ?>" required="required" autocomplete="off" />
                        </div>
                    <?php endif ?>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="rememberme">
                                <small class="form-text text-muted"><?= $this->language->login->form->remember_me ?></small>
                            </label>
                        </div>

                        <small><a href="lost-password" class="text-muted"><?= $this->language->login->display->lost_password ?></a> / <a href="resend-activation" class="text-muted" role="button"><?= $this->language->login->display->resend_activation ?></a></small>
                    </div>


                    <div class="form-group mt-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block my-1"><?= $this->language->login->form->login ?></button>
                    </div>

                    <div class="row">
                        <?php if($this->settings->facebook->is_enabled): ?>
                            <div class="col-sm mt-1">
                                <a href="<?= $data->facebook_login_url ?>" class="btn btn-light btn-block"><?= sprintf($this->language->login->display->facebook, "<i class=\"fab fa-fw fa-facebook\"></i>") ?></a>
                            </div>
                        <?php endif ?>
                    </div>

                </form>
            </div>
        </div>

        <?php if($this->settings->register_is_enabled): ?>
            <div class="mt-4">
                <?= sprintf($this->language->login->display->register, '<a href="' . url('register') . '" class="font-weight-bold">' . $this->language->login->display->register_help . '</a>') ?></a>
            </div>
        <?php endif ?>
    </div>
</div>
