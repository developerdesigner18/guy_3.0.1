<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="team_create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->team_create_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="team_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->team_create_modal->input->name ?></label>
                        <input type="text" class="form-control form-control-lg" name="name" required="required" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->team_create_modal->input->websites_ids ?></label>
                        <select multiple="multiple" name="websites_ids[]" class="form-control form-control-lg">
                            <?php foreach($this->websites as $key => $value) echo '<option value="' . $key . '">' . $value->name . ' - ' . $value->host . $value->path . '</option>' ?>
                        </select>
                        <small class="form-text text-muted"><?= $this->language->team_create_modal->input->websites_ids_help ?></small>
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
    $('form[name="team_create"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'teams-ajax',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                if (data.status == 'error') {
                    let notification_container = $(event.currentTarget).find('.notification-container');

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Hide modal */
                    $('#team_create').modal('hide');

                    /* Clear input values */
                    $('form[name="team_create"] input').val('');

                    /* Fade out refresh */
                    redirect(`team/${data.details.team_id}`);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
