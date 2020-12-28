<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="heatmap_retake_snapshots" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->heatmap_retake_snapshots_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted"><?= $this->language->heatmap_retake_snapshots_modal->subheader ?></p>

                <form name="heatmap_retake_snapshots" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="heatmap_id" value="" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="snapshot_id_desktop"
                                    id="heatmap_retake_snapshots_snapshot_id_desktop"
                            >
                            <label class="custom-control-label clickable" for="heatmap_retake_snapshots_snapshot_id_desktop"><i class="fa fa-fw fa-desktop"></i> <?= $this->language->heatmap_retake_snapshots_modal->input->snapshot_id_desktop ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="snapshot_id_tablet"
                                    id="heatmap_retake_snapshots_snapshot_id_tablet"
                            >
                            <label class="custom-control-label clickable" for="heatmap_retake_snapshots_snapshot_id_tablet"><i class="fa fa-fw fa-tablet"></i> <?= $this->language->heatmap_retake_snapshots_modal->input->snapshot_id_tablet ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="snapshot_id_mobile"
                                    id="heatmap_retake_snapshots_snapshot_id_mobile"
                            >
                            <label class="custom-control-label clickable" for="heatmap_retake_snapshots_snapshot_id_mobile"><i class="fa fa-fw fa-mobile"></i> <?= $this->language->heatmap_retake_snapshots_modal->input->snapshot_id_mobile ?></label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->submit ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#heatmap_retake_snapshots').on('show.bs.modal', event => {
        let heatmap_id = $(event.relatedTarget).data('heatmap-id');

        $(event.currentTarget).find('input[name="heatmap_id"]').val(heatmap_id);
    });


    $('form[name="heatmap_retake_snapshots"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'heatmaps-ajax/retake_snapshots',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                let notification_container = $(event.currentTarget).find('.notification-container');

                if(data.status == 'error') {

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {

                        /* Hide modal */
                        $('#heatmap_retake_snapshots').modal('hide');

                        /* Clear input values */
                        $('form[name="heatmap_retake_snapshots"] input').val('');

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
