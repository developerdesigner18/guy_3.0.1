<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="website_pixel_key" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header flex-column flex-md-row">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pixel-key-install-tab" data-toggle="pill" href="#pixel-key-install" role="tab" aria-controls="pixel-key-install" aria-selected="true"><i class="fa fa-fw fa-sm fa-code"></i> <?= $this->language->website_pixel_key_modal->install->header ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pixel-key-verify-tab" data-toggle="pill" href="#pixel-key-verify" role="tab" aria-controls="pixel-key-verify" aria-selected="false"><i class="fa fa-fw fa-sm fa-check"></i> <?= $this->language->website_pixel_key_modal->verify->header ?></a>
                    </li>
                </ul>

                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pixel-key-install" role="tabpanel" aria-labelledby="pixel-key-install-tab">
                        <p class="text-muted"><?= $this->language->website_pixel_key_modal->install->subheader ?></p>

                        <pre id="pixel_key_html" class="pre-custom rounded"></pre>

                        <div class="mt-4">
                            <button type="button" class="btn btn-lg btn-block btn-primary" data-clipboard-target="#pixel_key_html" data-copied="<?= $this->language->website_pixel_key_modal->install->copied ?>"><?= $this->language->website_pixel_key_modal->install->copy ?></button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pixel-key-verify" role="tabpanel" aria-labelledby="pixel-key-verify-tab">
                        <p class="text-muted"><?= $this->language->website_pixel_key_modal->verify->subheader ?></p>

                        <div class="mt-4">
                            <button type="button" data-verify="" class="btn btn-lg btn-block btn-outline-secondary"><?= $this->language->website_pixel_key_modal->verify->verify ?></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH ?>js/libraries/clipboard.min.js?v=<?= PRODUCT_CODE ?>"></script>

<script>
    /* On modal show */
    $('#website_pixel_key').on('show.bs.modal', event => {
        let pixel_key = $(event.relatedTarget).data('pixel-key');
        let url = $(event.relatedTarget).data('url');
        let site_url = <?= json_encode(SITE_URL) ?>;

        let pixel_key_html = `&lt;!-- Pixel Code for ${site_url} --&gt;
&lt;script async src="${site_url}pixel/${pixel_key}"&gt;&lt;/script&gt;
&lt;!-- END Pixel Code --&gt;`;

        $(event.currentTarget).find('pre').html(pixel_key_html);

        new ClipboardJS('[data-clipboard-target]');

        /* Handle on click button */
        let copy_button = $(event.currentTarget).find('[data-clipboard-target]');
        let initial_text = copy_button.text();

        copy_button.on('click', () => {

            copy_button.text(copy_button.data('copied'));

            setTimeout(() => {
                copy_button.text(initial_text);
            }, 2500);
        });

        /* Verify pixel */
        $(event.currentTarget).find('[data-verify]').off().on('click', () => {
            window.open(`${url}?pixel_verify=${pixel_key}`, '_blank', 'toolbar=0,location=0,menubar=0,width=600,height=450');
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
