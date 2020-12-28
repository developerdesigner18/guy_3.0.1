<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="session_events_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="p-3">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title"><?= $this->language->session_events_modal->header ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= $this->language->session_events_modal->subheader ?></p>
            </div>

            <div class="modal-body">
                <div class="notification-container"></div>

                <div id="session_events_result"></div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#session_events_modal').on('show.bs.modal', event => {
        let session_id = $(event.relatedTarget).data('session-id');
        let loading_html = $('#loading').html();

        /* Place the loading html */
        $('#session_events_result').html(loading_html);

        $.ajax({
            type: 'GET',
            url: `session-ajax/${session_id}`,
            success: (data) => {
                if (data.status == 'error') {
                    let notification_container = $(event.currentTarget).find('.notification-container');

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    $('#session_events_result').html(data.details.html);

                }
            },
            dataType: 'json'
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
