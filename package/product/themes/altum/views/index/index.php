<?php defined('ALTUMCODE') || die() ?>

<div class="index-container">

    <div class="container">
        <?php display_notifications() ?>

        <div class="row justify-content-center">
            <div class="col-11 col-md-10 col-lg-7">
                <h1 class="index-header text-center mb-4"><?= $this->language->index->header ?></h1>
            </div>

            <div class="col-10 col-sm-8 col-lg-6">
                <p class="index-subheader text-center mb-4"><?= $this->language->index->subheader ?></p>
            </div>
        </div>

        <div class="text-center">
            <a href="<?= url('register') ?>" class="btn btn-primary btn-lg"><?= $this->language->index->sign_up ?></a>
        </div>
    </div>

    <div class="position-relative">
        <svg class="index-background" height="500px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <defs>
                <linearGradient spreadMethod="pad" id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:rgb(17, 85, 212);stop-opacity:1;" />
                    <?php if(\Altum\ThemeStyle::get() == 'light'): ?>
                        <stop offset="100" style="stop-color:rgb(255, 255, 255);stop-opacity:1;" />
                    <?php else: ?>
                        <stop offset="100" style="stop-color:rgb(19,20,22);stop-opacity:1;" />
                    <?php endif ?>
                </linearGradient>
            </defs>
            <path fill="#256DF4" opacity="0.3" d="M0,32L20,69.3C40,107,80,181,120,181.3C160,181,200,107,240,85.3C280,64,320,96,360,133.3C400,171,440,213,480,240C520,267,560,277,600,245.3C640,213,680,139,720,106.7C760,75,800,85,840,90.7C880,96,920,96,960,122.7C1000,149,1040,203,1080,192C1120,181,1160,107,1200,96C1240,85,1280,139,1320,176C1360,213,1400,235,1420,245.3L1440,256L1440,320L1420,320C1400,320,1360,320,1320,320C1280,320,1240,320,1200,320C1160,320,1120,320,1080,320C1040,320,1000,320,960,320C920,320,880,320,840,320C800,320,760,320,720,320C680,320,640,320,600,320C560,320,520,320,480,320C440,320,400,320,360,320C320,320,280,320,240,320C200,320,160,320,120,320C80,320,40,320,20,320L0,320Z"></path>
            <path fill="#256DF4" opacity="0.4" d="M0,224L12,192C24,160,48,96,72,90.7C96,85,120,139,144,186.7C168,235,192,277,216,277.3C240,277,264,235,288,197.3C312,160,336,128,360,138.7C384,149,408,203,432,218.7C456,235,480,213,504,176C528,139,552,85,576,69.3C600,53,624,75,648,101.3C672,128,696,160,720,154.7C744,149,768,107,792,80C816,53,840,43,864,69.3C888,96,912,160,936,186.7C960,213,984,203,1008,186.7C1032,171,1056,149,1080,154.7C1104,160,1128,192,1152,197.3C1176,203,1200,181,1224,165.3C1248,149,1272,139,1296,138.7C1320,139,1344,149,1368,154.7C1392,160,1416,160,1428,160L1440,160L1440,320L1428,320C1416,320,1392,320,1368,320C1344,320,1320,320,1296,320C1272,320,1248,320,1224,320C1200,320,1176,320,1152,320C1128,320,1104,320,1080,320C1056,320,1032,320,1008,320C984,320,960,320,936,320C912,320,888,320,864,320C840,320,816,320,792,320C768,320,744,320,720,320C696,320,672,320,648,320C624,320,600,320,576,320C552,320,528,320,504,320C480,320,456,320,432,320C408,320,384,320,360,320C336,320,312,320,288,320C264,320,240,320,216,320C192,320,168,320,144,320C120,320,96,320,72,320C48,320,24,320,12,320L0,320Z"></path>

            <path fill="url(#gradient)" d="M0,96L20,112C40,128,80,160,120,149.3C160,139,200,85,240,101.3C280,117,320,203,360,197.3C400,192,440,96,480,69.3C520,43,560,85,600,90.7C640,96,680,64,720,64C760,64,800,96,840,112C880,128,920,128,960,117.3C1000,107,1040,85,1080,90.7C1120,96,1160,128,1200,160C1240,192,1280,224,1320,202.7C1360,181,1400,107,1420,69.3L1440,32L1440,320L1420,320C1400,320,1360,320,1320,320C1280,320,1240,320,1200,320C1160,320,1120,320,1080,320C1040,320,1000,320,960,320C920,320,880,320,840,320C800,320,760,320,720,320C680,320,640,320,600,320C560,320,520,320,480,320C440,320,400,320,360,320C320,320,280,320,240,320C200,320,160,320,120,320C80,320,40,320,20,320L0,320Z"></path>
        </svg>
    </div>


    <div class="container">
    <div class="row justify-content-center mt-8">
        <div class="col-11 col-md-10">
            <video poster="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/hero.png' ?>" width="100%" height="100%" autoplay="" muted="" loop="" class="d-none d-md-block img-fluid rounded-sm shadow-lg">
                <source src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/hero.mp4' ?>" type="video/mp4">
            </video>

            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/hero.png' ?>" class="d-md-none img-fluid shadow-lg" />
        </div>
    </div>
    </div>
</div>


<div class="container mt-10">

    <div class="row justify-content-between">
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center order-1 order-md-0">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->analytics->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->analytics->header ?></h2>

                <p class="text-muted"><?= $this->language->index->analytics->subheader ?></p>
            </div>
        </div>

        <div class="col-8 col-md-5 text-center mb-5 mb-md-0 order-0 order-md-1">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/analytics.svg' ?>" class="img-fluid" />
        </div>
    </div>

    <div class="row justify-content-between mt-9">
        <div class="col-8 col-md-5 text-center mb-5 mb-md-0">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/visitor_behaviour.svg' ?>" class="img-fluid" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->visitor_behaviour->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->visitor_behaviour->header ?></h2>

                <p class="text-muted"><?= $this->language->index->visitor_behaviour->subheader ?></p>
            </div>
        </div>
    </div>

    <div class="row justify-content-between mt-9">
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center order-1 order-md-0">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->realtime_data->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->realtime_data->header ?></h2>

                <p class="text-muted"><?= $this->language->index->realtime_data->subheader ?></p>
            </div>
        </div>

        <div class="col-8 col-md-5 text-center mb-5 mb-md-0 order-0 order-md-1">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/realtime_data.svg' ?>" class="img-fluid" />
        </div>
    </div>

    <div class="row justify-content-between mt-9">
        <div class="col-8 col-md-5 text-center mb-5 mb-md-0">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/privacy.svg' ?>" class="img-fluid" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->privacy->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->privacy->header ?></h2>

                <p class="text-muted"><?= $this->language->index->privacy->subheader ?></p>
            </div>
        </div>
    </div>

    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
    <div class="row justify-content-between mt-9">
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center order-1 order-md-0">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->session_recording->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->session_recording->header ?></h2>

                <p class="text-muted"><?= $this->language->index->session_recording->subheader ?></p>
            </div>
        </div>

        <div class="col-8 col-md-5 text-center mb-5 mb-md-0 order-0 order-md-1">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/session_recording.svg' ?>" class="img-fluid" />
        </div>
    </div>
    <?php endif ?>

    <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
    <div class="row justify-content-between mt-9">
        <div class="col-8 col-md-5 text-center mb-5 mb-md-0">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/index/heatmaps.svg' ?>" class="img-fluid" />
        </div>

        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
            <div class="text-uppercase font-weight-bold text-primary mb-3"><?= $this->language->index->heatmaps->name ?></div>

            <div>
                <h2 class="mb-4"><?= $this->language->index->heatmaps->header ?></h2>

                <p class="text-muted"><?= $this->language->index->heatmaps->subheader ?></p>
            </div>
        </div>
    </div>
    <?php endif ?>

</div>


<div class="index-register-container bg-gray-200 mt-9">
    <div class="container">
        <div class="text-center">
            <h2><?= $this->language->index->testimonials->header ?></h2>
            <p class="text-muted mt-3"><?= $this->language->index->testimonials->subheader ?></p>
        </div>

        <div class="row mt-8">
            <div class="col-12 col-md-4 m mb-md-0">
                <?= (new \Altum\Views\View('index/partials/testimonial'))->run([
                        'image' => $this->language->index->testimonials->t1->image,
                        'text' => $this->language->index->testimonials->t1->text,
                        'name' => $this->language->index->testimonials->t1->name,
                        'attribute' => $this->language->index->testimonials->t1->attribute
                ]) ?>
            </div>

            <div class="col-12 col-md-4 m mb-md-0">
                <?= (new \Altum\Views\View('index/partials/testimonial'))->run([
                    'image' => $this->language->index->testimonials->t2->image,
                    'text' => $this->language->index->testimonials->t2->text,
                    'name' => $this->language->index->testimonials->t2->name,
                    'attribute' => $this->language->index->testimonials->t2->attribute
                ]) ?>
            </div>

            <div class="col-12 col-md-4 m mb-md-0">
                <?= (new \Altum\Views\View('index/partials/testimonial'))->run([
                    'image' => $this->language->index->testimonials->t3->image,
                    'text' => $this->language->index->testimonials->t3->text,
                    'name' => $this->language->index->testimonials->t3->name,
                    'attribute' => $this->language->index->testimonials->t3->attribute
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div id="pricing" class="container mt-10">
    <div class="mb-5 text-center">
        <h2><?= $this->language->index->pricing->header ?></h2>

        <p class="text-muted mt-3"><?= $this->language->index->pricing->subheader ?></p>
    </div>

    <?= $this->views['plans'] ?>
</div>


<?php ob_start() ?>
<script>
    if($(window).width() <= 768){
        $('video').remove();
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

