<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="card border-0 col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card-body">
                <?php display_notifications() ?>

                <h4 class="card-title d-flex justify-content-between"><?= $this->language->resend_activation->header ?></h4>

                <form action="" method="post" class="mt-4" role="form">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control form-control-lg" value="<?= $data->values['email'] ?>" placeholder="<?= $this->language->resend_activation->form->email_placeholder ?>" aria-label="<?= $this->language->resend_activation->form->email ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <?php $data->captcha->display() ?>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block my-1"><?= $this->language->global->submit ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="login" class="text-muted"><?= $this->language->resend_activation->return ?></a>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

