<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="website_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->website_update_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="website_update" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="website_id" value="" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->website_update_modal->input->name ?></label>
                        <input type="text" class="form-control form-control-lg" name="name" />
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
                        <select name="tracking_type" class="form-control form-control-lg" disabled="disabled">
                            <option value="lightweight"><?= $this->language->website_create_modal->input->tracking_type_lightweight ?></option>
                            <option value="normal"><?= $this->language->website_create_modal->input->tracking_type_normal ?></option>
                        </select>
                        <small data-tracking-type="lightweight" class="form-text text-muted d-none"><?= $this->language->website_create_modal->input->tracking_type_lightweight_help ?></small>
                        <small data-tracking-type="normal" class="form-text text-muted d-none"><?= $this->language->website_create_modal->input->tracking_type_normal_help ?></small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="is_enabled"
                                    id="website_update_is_enabled"
                            >
                            <label class="custom-control-label clickable" for="website_update_is_enabled"><?= $this->language->website_update_modal->input->is_enabled ?></label>
                            <small class="form-text text-muted"><?= $this->language->website_update_modal->input->is_enabled_help ?></small>
                        </div>
                    </div>

                    <div data-tracking-type="normal" class="d-none">
                        <div <?= $this->user->plan_settings->events_children_limit ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->events_children_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="events_children_is_enabled"
                                        id="website_update_events_children_is_enabled"
                                        <?= $this->user->plan_settings->events_children_limit ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_update_events_children_is_enabled"><?= $this->language->website_update_modal->input->events_children_is_enabled ?></label>
                                <small class="form-text text-muted"><?= $this->language->website_update_modal->input->events_children_is_enabled_help ?></small>
                            </div>
                        </div>

                        <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                        <div <?= $this->user->plan_settings->sessions_replays_limit ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                            <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->sessions_replays_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="sessions_replays_is_enabled"
                                        id="website_update_sessions_replays_is_enabled"
                                        <?= $this->user->plan_settings->sessions_replays_limit ? null : 'disabled="disabled"' ?>
                                />
                                <label class="custom-control-label clickable" for="website_update_sessions_replays_is_enabled"><?= $this->language->website_update_modal->input->sessions_replays_is_enabled ?></label>
                                <small class="form-text text-muted"><?= $this->language->website_update_modal->input->sessions_replays_is_enabled_help ?></small>
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
                                    id="website_update_email_reports_is_enabled"
                                    <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'disabled="disabled"' ?>
                            >
                            <label class="custom-control-label clickable" for="website_update_email_reports_is_enabled"><?= $this->language->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} ?></label>
                            <small class="form-text text-muted"><?= $this->language->website_update_modal->input->email_reports_help ?></small>
                        </div>
                    </div>
                    <?php endif ?>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-user-slash text-muted mr-1"></i> <?= $this->language->website_update_modal->input->excluded_ips ?></label>
                        <textarea class="form-control form-control-lg" name="excluded_ips"></textarea>
                        <small class="form-text text-muted"><?= $this->language->website_update_modal->input->excluded_ips_help ?></small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>

    /* On modal show load new data */
    $('#website_update').on('show.bs.modal', event => {
        let website_id = $(event.relatedTarget).data('website-id');
        let name = $(event.relatedTarget).data('name');
        let scheme = $(event.relatedTarget).data('scheme');
        let host = $(event.relatedTarget).data('host');
        let tracking_type = $(event.relatedTarget).data('tracking-type');
        let is_enabled = $(event.relatedTarget).data('is-enabled');
        let events_children_is_enabled = $(event.relatedTarget).data('events-children-is-enabled');
        let sessions_replays_is_enabled = $(event.relatedTarget).data('sessions-replays-is-enabled');
        let email_reports_is_enabled = $(event.relatedTarget).data('email-reports-is-enabled');
        let excluded_ips = $(event.relatedTarget).data('excluded-ips');

        $(event.currentTarget).find('input[name="website_id"]').val(website_id);
        $(event.currentTarget).find('input[name="name"]').val(name);
        $(event.currentTarget).find('select[name="scheme"]').val(scheme);
        $(event.currentTarget).find('input[name="host"]').val(host).trigger('change');
        $(event.currentTarget).find('input[name="events_children_is_enabled"]').prop('checked', events_children_is_enabled);
        $(event.currentTarget).find('input[name="sessions_replays_is_enabled"]').prop('checked', sessions_replays_is_enabled);
        $(event.currentTarget).find('input[name="is_enabled"]').prop('checked', is_enabled);
        $(event.currentTarget).find('input[name="email_reports_is_enabled"]').prop('checked', email_reports_is_enabled);
        $(event.currentTarget).find('textarea[name="excluded_ips"]').val(excluded_ips);

        switch(tracking_type) {
            case 'lightweight':

                document.querySelectorAll('#website_update [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                document.querySelectorAll('#website_update [data-tracking-type="normal"]').forEach(element => {
                    element.classList.add('d-none');
                });

                document.querySelector('#website_update select[name="tracking_type"] option[value="normal"]').removeAttribute('selected');
                document.querySelector('#website_update select[name="tracking_type"] option[value="lightweight"]').selected = 'selected';

                break;

            case 'normal':

                document.querySelectorAll('#website_update [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.add('d-none');
                });

                document.querySelectorAll('#website_update [data-tracking-type="normal"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                document.querySelector('#website_update select[name="tracking_type"] option[value="lightweight"]').removeAttribute('selected');
                document.querySelector('#website_update select[name="tracking_type"] option[value="normal"]').selected = 'selected';

                break;
        }
    });


    $('form[name="website_update"]').on('submit', event => {

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
                        $('#website_update').modal('hide');

                        /* Clear input values */
                        $('form[name="website_update"] input').val('');

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
