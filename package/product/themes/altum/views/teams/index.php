<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">

        <h1 class="h3"><i class="fa fa-fw fa-xs fa-user-shield text-gray-700"></i> <?= $this->language->teams->header ?></h1>
        <p class="text-muted"><?= $this->language->teams->subheader ?></p>

    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?php display_notifications() ?>

    <?php if($this->user->plan_settings->teams_is_enabled): ?>
        <div class="mb-6">
            <div class="mb-3 d-flex flex-column flex-md-row justify-content-between">
                <div>
                    <h2 class="h4"><?= $this->language->teams->teams->header ?></h2>
                </div>

                <?php if(!$this->team && $this->user->plan_settings->teams_is_enabled): ?>
                    <div class="col-auto p-0">
                        <button type="button" data-toggle="modal" data-target="#team_create" class="btn btn-primary rounded-pill"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->teams->teams->create ?></button>
                    </div>
                <?php endif ?>
            </div>

            <?php if($this->team): ?>
            <div class="alert alert-info" role="alert">
                <?= $this->language->global->info_message->team_not_allowed ?>
            </div>
            <?php else: ?>

                <?php if($data->teams_result->num_rows): ?>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= $this->language->teams->teams->team ?></th>
                                <th><?= $this->language->teams->teams->websites_ids ?></th>
                                <th><?= $this->language->teams->teams->users ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while($team = $data->teams_result->fetch_object()): ?>
                                <?php $team->websites_ids = json_decode($team->websites_ids) ?>
                                <tr data-team-id="<?= $team->team_id ?>">
                                    <td>
                                        <a href="<?= url('team/' . $team->team_id) ?>"><?= $team->name ?></a>
                                    </td>

                                    <td class="text-muted">
                                        <i class="fa fa-fw fa-server fa-sm"></i> <?= nr(count($team->websites_ids)) ?>
                                    </td>

                                    <td class="text-muted">
                                        <i class="fa fa-fw fa-users fa-sm"></i> <?= nr($team->users) ?>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column flex-md-row">
                                            <a
                                                    href="#"
                                                    class="mr-3 text-decoration-none"
                                                    data-toggle="modal"
                                                    data-target="#team_update"
                                                    data-team-id="<?= $team->team_id ?>"
                                                    data-name="<?= $team->name ?>"
                                                    data-websites-ids="<?= json_encode($team->websites_ids) ?>"
                                            >
                                                <i class="fa fa-fw fa-sm fa-pencil-alt"></i> <?= $this->language->global->edit ?>
                                            </a>
                                            <a
                                                    href="#"
                                                    class="text-muted text-decoration-none"
                                                    data-toggle="modal"
                                                    data-target="#team_delete"
                                                    data-team-id="<?= $team->team_id ?>"
                                            >
                                                <i class="fa fa-fw fa-sm fa-times"></i> <?= $this->language->global->delete ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile ?>

                            </tbody>
                        </table>
                    </div>

                <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->teams->teams->no_data ?>" />
                        <h2 class="h4 text-muted"><?= $this->language->teams->teams->no_data ?></h2>
                        <p><?= $this->language->teams->teams->no_data_help ?></a></p>
                    </div>
                <?php endif ?>

            <?php endif ?>

        </div>
    <?php endif ?>

    <div>
        <div class="mb-3">
            <h2 class="h4"><?= $this->language->teams->teams_associations->header ?></h2>
            <p class="text-muted"><?= $this->language->teams->teams_associations->subheader ?></p>
        </div>

        <?php if($data->teams_associations_result->num_rows): ?>

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th><?= $this->language->teams->teams_associations->team ?></th>
                        <th><?= $this->language->teams->teams_associations->websites_ids ?></th>
                        <th><?= $this->language->teams->teams_associations->is_accepted ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php while($team = $data->teams_associations_result->fetch_object()): ?>
                        <?php $team->websites_ids = json_decode($team->websites_ids) ?>
                        <tr data-team-association-id="<?= $team->team_association_id ?>">
                            <td>
                                <span><?= $team->name ?></span>
                            </td>

                            <td class="text-muted">
                                <i class="fa fa-fw fa-server fa-sm"></i> <?= nr(count($team->websites_ids)) ?>
                            </td>

                            <td>
                                <?php if($team->is_accepted): ?>
                                <span class="badge badge-pill badge-success">
                                    <i class="fa fa-fw fa-check"></i> <?= $this->language->teams->teams_associations->accepted_date ?>
                                </span>

                                    <small class="text-muted"><?= \Altum\Date::get($team->date, 2) ?></small>
                                <?php else: ?>
                                <span class="badge badge-pill badge-warning">
                                    <?= $this->language->team->teams_associations->is_accepted_invited ?>
                                </span>
                                <?php endif ?>
                            </td>

                            <td>
                                <div class="d-flex flex-column flex-md-row">
                                <?php if($team->is_accepted): ?>
                                    <a
                                            href="#"
                                            class="mr-3 text-decoration-none"
                                            data-team-login="true"
                                            data-team-id="<?= $team->team_id ?>"
                                    >
                                        <i class="fa fa-fw fa-sm fa-sign-in-alt"></i> <?= $this->language->teams->teams_associations->login ?>
                                    </a>
                                    <a
                                            href="#"
                                            class="text-muted text-decoration-none"
                                            data-toggle="modal"
                                            data-target="#team_association_delete"
                                            data-team-association-id="<?= $team->team_association_id ?>"
                                    >
                                        <i class="fa fa-fw fa-sm fa-times"></i> <?= $this->language->teams->teams_associations->delete ?>
                                    </a>
                                <?php else: ?>
                                    <a
                                            href="#"
                                            class="mr-3 text-decoration-none"
                                            data-team-association-accept="true"
                                            data-team-association-id="<?= $team->team_association_id ?>"
                                    >
                                        <i class="fa fa-fw fa-sm fa-sign-in-alt"></i> <?= $this->language->teams->teams_associations->accept ?>
                                    </a>
                                    <a
                                            href="#"
                                            class="text-muted text-decoration-none"
                                            data-toggle="modal"
                                            data-target="#team_association_delete"
                                            data-team-association-id="<?= $team->team_association_id ?>"
                                    >
                                        <i class="fa fa-fw fa-sm fa-times"></i> <?= $this->language->teams->teams_associations->decline ?>
                                    </a>
                                <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile ?>

                    </tbody>
                </table>
            </div>

        <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->teams->teams_associations->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->teams->teams_associations->no_data ?></h2>
            <p><?= $this->language->teams->teams_associations->no_data_help ?></a></p>
        </div>

        <?php endif ?>

    </div>

</section>

<?php ob_start() ?>
<script>
    /* Login for the team */
    $('[data-team-login]').on('click', event => {
        let team_id = $(event.currentTarget).data('team-id');

        /* Set the cookie */
        set_cookie('selected_team_id', team_id, 30);

        redirect('dashboard');

        event.preventDefault();
    });

    /* Accept request for the team association */
    $('[data-team-association-accept]').on('click', event => {
        let team_association_id = $(event.currentTarget).data('team-association-id');

        $.ajax({
            type: 'POST',
            url: 'teams-associations-ajax',
            data: {team_association_id, global_token, request_type: 'update'},
            success: (data) => {
                if(data.status == 'error') {
                    /* Nothing */
                }

                else if(data.status == 'success') {

                    redirect('teams');

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

