<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="card border-0 col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card-body">
                <?php display_notifications() ?>

                <h4 class="card-title"><?= $this->language->register->header ?></h4>

                <form action="" method="post" class="mt-4" role="form">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control form-control-lg" value="<?= $data->values['name'] ?>" placeholder="<?= $this->language->register->form->name_placeholder ?>" aria-label="<?= $this->language->register->form->name ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <input type="text" name="email" class="form-control form-control-lg" value="<?= $data->values['email'] ?>" placeholder="<?= $this->language->register->form->email_placeholder ?>" aria-label="<?= $this->language->register->form->email ?>" required="required" <?= $data->unique_registration_identifier ? 'readonly="readonly"' : null ?> />
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg" value="<?= $data->values['password'] ?>" placeholder="<?= $this->language->register->form->password_placeholder ?>" aria-label="<?= $this->language->register->form->password ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <?php $data->captcha->display() ?>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="accept" type="checkbox" required="required">
                            <small class="form-text text-muted">
                                <?= sprintf(
                                    $this->language->register->form->accept,
                                    '<a href="' . $this->settings->terms_and_conditions_url . '" target="_blank">' . $this->language->global->terms_and_conditions . '</a>',
                                    '<a href="' . $this->settings->privacy_policy_url . '" target="_blank">' . $this->language->global->privacy_policy . '</a>'
                                ) ?>
                            </small>
                        </label>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><?= $this->language->register->form->register ?></button>
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

        <div class="mt-4">
            <a href="login" class="text-muted"><?= $this->language->register->login ?></a>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

