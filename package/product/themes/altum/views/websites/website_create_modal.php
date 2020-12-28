<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="website_create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->website_create_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="website_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->website_create_modal->input->name ?></label>
                        <input type="text" class="form-control form-control-lg" name="name" required="required" />
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-network-wired text-muted mr-1"></i> <?= $this->language->website_create_modal->input->host ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select name="scheme" class="appearance-none select-custom-altum form-control form-control-lg input-group-text">
                                    <option value="https://">https://</option>
                                    <option value="http://">http://</option>
                                </select>
                            </div>

                            <input type="text" class="form-control form-control-lg" name="host" placeholder="<?= $this->language->website_create_modal->input->host_placeholder ?>" required="required" />
                        </div>
                        <small class="form-text text-muted"><?= $this->language->website_create_modal->input->host_help ?></small>
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-chart-bar text-muted mr-1"></i> <?= $this->language->website_create_modal->input->tracking_type ?></label>
                        <select name="tracking_type" class="form-control form-control-lg">
                            <option value="lightweight"><?= $this->language->website_create_modal->input->tracking_type_lightweight ?></option>
                            <option value="normal"><?= $this->language->website_create_modal->input->tracking_type_normal ?></option>
                        </select>
                        <small data-tracking-type="lightweight" class="form-text text-muted d-none"><?= $this->language->website_create_modal->input->tracking_type_lightweight_help ?></small>
                        <small data-tracking-type="normal" class="form-text text-muted d-none"><?= $this->language->website_create_modal->input->tracking_type_normal_help ?></small>
                    </div>

                    <div data-tracking-type="normal" class="d-none">

                        <div <?= $this->user->plan_settings->events_children_limit ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->events_children_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="events_children_is_enabled"
                                        id="website_create_events_children_is_enabled"
                                        <?= $this->user->plan_settings->events_children_limit ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_events_children_is_enabled"><?= $this->language->website_create_modal->input->events_children_is_enabled ?></label>
                                <small class="form-text text-muted"><?= $this->language->website_create_modal->input->events_children_is_enabled_help ?></small>
                            </div>
                        </div>

                        <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                        <div <?= $this->user->plan_settings->sessions_replays_limit ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->sessions_replays_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="sessions_replays_is_enabled"
                                        id="website_create_sessions_replays_is_enabled"
                                        <?= $this->user->plan_settings->sessions_replays_limit ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_sessions_replays_is_enabled"><?= $this->language->website_create_modal->input->sessions_replays_is_enabled ?></label>
                                <small class="form-text text-muted"><?= $this->language->website_create_modal->input->sessions_replays_is_enabled_help ?></small>
                            </div>
                        </div>
                        <?php endif ?>
                    </div>

                    <?php if($this->settings->analytics->email_reports_is_enabled): ?>
                    <div <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="email_reports_is_enabled"
                                        id="website_create_email_reports_is_enabled"
                                        <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_email_reports_is_enabled"><?= $this->language->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} ?></label>
                            </div>
                        </div>
                    <?php endif ?>

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
    'use strict';

    /* Tracking type handler */
    let tracking_type_handler = () => {
        let tracking_type = document.querySelector('#website_create select[name="tracking_type"]').value;

        switch(tracking_type) {
            case 'lightweight':

                document.querySelectorAll('#website_create [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                document.querySelectorAll('#website_create [data-tracking-type="normal"]').forEach(element => {
                    element.classList.add('d-none');
                });

                break;

            case 'normal':

                document.querySelectorAll('#website_create [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.add('d-none');
                });

                document.querySelectorAll('#website_create [data-tracking-type="normal"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                break;
        }

    };

    document.querySelector('#website_create select[name="tracking_type"]').addEventListener('change', tracking_type_handler);

    tracking_type_handler();


    $('form[name="website_create"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'websites-ajax',
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
                        $('#website_create').modal('hide');

                        /* Clear input values */
                        $('form[name="website_create"] input').val('');

                        /* Refresh */
                        redirect('websites');

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
