<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="heatmap_create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->heatmap_create_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="heatmap_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-signature text-gray-700 mr-1"></i> <?= $this->language->heatmap_create_modal->input->name ?></label>
                        <input type="text" class="form-control form-control-lg" name="name" required="required" />
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-link text-gray-700 mr-1"></i> <?= $this->language->heatmap_create_modal->input->path ?></label>
                        <div class="input-group">
                            <div id="path_prepend" class="input-group-prepend">
                                <span class="input-group-text"><?= $this->website->host . $this->website->path . '/' ?></span>
                            </div>

                            <input type="text" name="path" class="form-control form-control-lg" placeholder="<?= $this->language->heatmap_create_modal->input->path_placeholder ?>" />
                        </div>
                        <small class="form-text text-muted"><?= $this->language->heatmap_create_modal->input->path_help ?></small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->create ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>

    $('form[name="heatmap_create"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'heatmaps-ajax/create',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                let notification_container = $(event.currentTarget).find('.notification-container');

                if (data.status == 'error') {
                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {

                        /* Hide modal */
                        $('#heatmap_create').modal('hide');

                        /* Clear input values */
                        $('form[name="heatmap_create"] input').val('');

                        /* Fade out refresh */
                        redirect('heatmaps');

                        /* Remove the notification */
                        notification_container.html('');

                    }, 1000);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
