<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="team_association_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-fw fa-sm fa-trash-alt text-gray-700"></i>
                    <?= $this->language->team_association_delete_modal->header ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="team_association_delete" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="delete" />
                    <input type="hidden" name="team_association_id" value="" />

                    <div class="notification-container"></div>

                    <p class="text-muted"><?= $this->language->team_association_delete_modal->subheader ?></p>

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
    /* On modal show load new data */
    $('#team_association_delete').on('show.bs.modal', event => {
        let team_association_id = $(event.relatedTarget).data('team-association-id');

        $(event.currentTarget).find('input[name="team_association_id"]').val(team_association_id);
    });

    $('form[name="team_association_delete"]').on('submit', event => {
        let team_association_id = $(event.currentTarget).find('input[name="team_association_id"]').val();

        $.ajax({
            type: 'POST',
            url: 'teams-associations-ajax',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                let notification_container = $(event.currentTarget).find('.notification-container');
                notification_container.html('');

                if (data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Clear input values */
                    $(event.currentTarget).find('input[name="team_association_id"]').val('');

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {
                        /* Hide modal */
                        $('#team_association_delete').modal('hide');

                        location.reload();

                    }, 1000);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
