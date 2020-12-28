<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="goal_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $this->language->goal_create_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted"><?= $this->language->goal_create_modal->subheader ?></p>

                <form name="goal_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <div class="btn-group btn-group-sm btn-group-toggle d-flex flex-fill" data-toggle="buttons">
                            <label class="btn btn-sm btn-secondary active" data-target="#goal_create_type_pageview" data-toggle="pill" role="tab">
                                <input type="radio" name="type" value="pageview" checked> <?= $this->language->goal_create_modal->input->type_pageview ?>
                            </label>

                            <label class="btn btn-sm btn-secondary" data-target="#goal_create_type_custom" data-toggle="pill" role="tab">
                                <input type="radio" name="type" value="custom"> <?= $this->language->goal_create_modal->input->type_custom ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-signature text-gray-700 mr-1"></i> <?= $this->language->goal_create_modal->input->name ?></label>
                        <input type="text" class="form-control form-control-lg" name="name" required="required" />
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="goal_create_type_pageview">

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-sm fa-link text-gray-700 mr-1"></i> <?= $this->language->goal_create_modal->input->path ?></label>
                                <div class="input-group">
                                    <div id="path_prepend" class="input-group-prepend">
                                        <span class="input-group-text"><?= $this->website->host . $this->website->path . '/' ?></span>
                                    </div>

                                    <input type="text" name="path" class="form-control form-control-lg" placeholder="<?= $this->language->goal_create_modal->input->path_placeholder ?>" />
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="goal_create_type_custom">

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-sm fa-fingerprint text-gray-700 mr-1"></i> <?= $this->language->goal_create_modal->input->key ?></label>
                                <input type="text" class="form-control form-control-lg" name="key" value="<?= string_generate(16) ?>" placeholder="<?= $this->language->goal_create_modal->input->key_placeholder ?>" />
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-fw fa-sm fa-code text-gray-700 mr-1"></i> <?= $this->language->goal_create_modal->input->code ?></label>
                                <input type="text" class="form-control form-control-lg" name="code" value="" readonly="readonly" />
                                <small class="form-text text-muted"><?= $this->language->goal_create_modal->input->code_help ?></small>
                            </div>

                        </div>
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

    let goal_create_update_code = () => {

        let key = $('#goal_create_modal input[name="key"]').val();

        let code = `<?= $this->settings->analytics->pixel_exposed_identifier ?>.goal('${key}')`;

        $('#goal_create_modal input[name="code"]').val(code);

    };

    $('#goal_create_modal input[name="key"]').on('change paste keyup', goal_create_update_code);

    goal_create_update_code();


    $('form[name="goal_create"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'goals-ajax/create',
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
                        $('#goal_create').modal('hide');

                        /* Clear input values */
                        $('form[name="goal_create"] input').val('');

                        /* Refresh */
                        redirect('dashboard/goals');

                        /* Remove the notification */
                        notification_container.html('');

                    }, 1000);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
