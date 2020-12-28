<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="card border-0 col-xs-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card-body">
                <?php display_notifications() ?>

                <h4 class="card-title"><?= $this->language->reset_password->header ?></h4>

                <form action="" method="post" class="mt-4" role="form">
                    <input type="hidden" name="email" value="<?= $data->values['email'] ?>" class="form-control" />

                    <div class="form-group">
                        <input type="password" name="new_password" class="form-control form-control-lg" aria-label="<?= $this->language->reset_password->form->new_password ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <input type="password" name="repeat_password" class="form-control form-control-lg" aria-label="<?= $this->language->reset_password->form->repeat_password ?>" required="required" />
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block my-1"><?= $this->language->global->submit ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="login" class="text-muted"><?= $this->language->reset_password->return ?></a>
        </div>
    </div>
</div>
