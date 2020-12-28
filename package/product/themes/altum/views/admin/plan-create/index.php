<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= $this->language->admin_plan_create->header ?></h1>
</div>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_plans->main->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_plans->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="name"><?= $this->language->admin_plans->main->name ?></label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_plans->main->status ?></label>
                        <select id="status" name="status" class="form-control form-control-lg">
                            <option value="1"><?= $this->language->global->active ?></option>
                            <option value="0"><?= $this->language->global->disabled ?></option>
                            <option value="2"><?= $this->language->global->hidden ?></option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="monthly_price"><?= $this->language->admin_plans->main->monthly_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                    <input type="text" id="monthly_price" name="monthly_price" class="form-control form-control-lg" />
                                    <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->monthly_price) ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <label for="annual_price"><?= $this->language->admin_plans->main->annual_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                <input type="text" id="annual_price" name="annual_price" class="form-control form-control-lg" />
                                <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->annual_price) ?></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group">
                                <label for="lifetime_price"><?= $this->language->admin_plans->main->lifetime_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                <input type="text" id="lifetime_price" name="lifetime_price" class="form-control form-control-lg" />
                                <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->lifetime_price) ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span><?= $this->language->admin_plans->main->taxes_ids ?></span>
                        <div><small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->taxes_ids_help, '<a href="' . url('admin/taxes') .'">', '</a>') ?></small></div>
                    </div>

                    <?php if($data->taxes): ?>
                        <div class="row">
                            <?php foreach($data->taxes as $row): ?>
                                <div class="col-12 col-xl-6">
                                    <div class="custom-control custom-switch my-3">
                                        <input id="<?= 'tax_id_' . $row->tax_id ?>" name="taxes_ids[<?= $row->tax_id ?>]" type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label" for="<?= 'tax_id_' . $row->tax_id ?>"><?= $row->internal_name ?></label>
                                        <div><small><?= $row->name ?></small> - <small class="form-text text-muted"><?= $row->description ?></small></div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_plans->plan->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_plans->plan->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="websites_limit"><?= $this->language->admin_plans->plan->websites_limit ?></label>
                        <input type="number" id="websites_limit" name="websites_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->websites_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="sessions_events_limit"><?= $this->language->admin_plans->plan->sessions_events_limit ?> <small class="form-text text-muted"><?= $this->language->admin_plans->plan->per_month ?></small></label>
                        <input type="number" id="sessions_events_limit" name="sessions_events_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->sessions_events_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="events_children_limit"><?= $this->language->admin_plans->plan->events_children_limit ?> <small class="form-text text-muted"><?= $this->language->admin_plans->plan->per_month ?></small></label>
                        <input type="number" id="events_children_limit" name="events_children_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->events_children_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="events_children_retention"><?= $this->language->admin_plans->plan->events_children_retention ?> <small class="form-text text-muted"><?= $this->language->global->date->days ?></small></label>
                        <input type="number" id="events_children_retention" name="events_children_retention" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->events_children_retention_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="sessions_replays_limit"><?= $this->language->admin_plans->plan->sessions_replays_limit ?> <small class="form-text text-muted"><?= $this->language->admin_plans->plan->per_month ?></small></label>
                        <input type="number" id="sessions_replays_limit" name="sessions_replays_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->sessions_replays_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="sessions_replays_retention"><?= $this->language->admin_plans->plan->sessions_replays_retention ?> <small class="form-text text-muted"><?= $this->language->global->date->days ?></small></label>
                        <input type="number" id="sessions_replays_retention" name="sessions_replays_retention" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->sessions_replays_retention_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="sessions_replays_time_limit"><?= $this->language->admin_plans->plan->sessions_replays_time_limit ?> <small class="form-text text-muted"><?= $this->language->global->date->minutes ?></small></label>
                        <input type="number" id="sessions_replays_time_limit" name="sessions_replays_time_limit" min="1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->sessions_replays_time_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="websites_heatmaps_limit"><?= $this->language->admin_plans->plan->websites_heatmaps_limit ?></label>
                        <input type="number" id="websites_heatmaps_limit" name="websites_heatmaps_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->websites_heatmaps_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="websites_goals_limit"><?= $this->language->admin_plans->plan->websites_goals_limit ?></label>
                        <input type="number" id="websites_goals_limit" name="websites_goals_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->websites_goals_limit_help ?></small>
                    </div>

                    <div class="mb-3">
                        <div class="custom-control custom-switch">
                            <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input">
                            <label class="custom-control-label" for="email_reports_is_enabled"><?= $this->language->admin_plans->plan->email_reports_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->email_reports_is_enabled_help ?></small></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="custom-control custom-switch">
                            <input id="teams_is_enabled" name="teams_is_enabled" type="checkbox" class="custom-control-input">
                            <label class="custom-control-label" for="teams_is_enabled"><?= $this->language->admin_plans->plan->teams_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->teams_is_enabled_help ?></small></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="custom-control custom-switch">
                            <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input">
                            <label class="custom-control-label" for="no_ads"><?= $this->language->admin_plans->plan->no_ads ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->no_ads_help ?></small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->create ?></button>
                </div>
            </div>
        </form>

    </div>
</div>
