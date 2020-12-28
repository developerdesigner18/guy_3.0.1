<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="replays_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-fw fa-sm fa-trash-alt text-gray-700"></i>
                    <?= $this->language->replays_delete_modal->header ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="replays_delete" method="post" role="form">
                    <div class="notification-container"></div>

                    <p class="text-muted"><?= $this->language->replays_delete_modal->subheader ?></p>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-danger"><?= $this->language->global->delete ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    $('form[name="replays_delete"]').on('submit', event => {
        let website_id = $('input[name="website_id"]').val();
        let start_date = $('input[name="start_date"]').val();
        let end_date = $('input[name="end_date"]').val();

        $.ajax({
            type: 'POST',
            url: 'replays-ajax/delete',
            data: {
                global_token,
                start_date,
                end_date,
                website_id
            },
            success: (data) => {
                let notification_container = $(event.currentTarget).find('.notification-container');
                notification_container.html('');

                if (data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Clear input values */
                    $(event.currentTarget).find('input[name="session_id"]').val('');

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {
                        /* Hide modal */
                        $('#replays_delete').modal('hide');

                        /* Reload datatable */
                        if(typeof datatable !== 'undefined') {
                            datatable.ajax.reload();
                        } else {
                            redirect('replays');
                        }

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
