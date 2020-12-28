<?php defined('ALTUMCODE') || die() ?>

<?php

use Altum\Middlewares\Authentication;

?>

<?php if($this->settings->payment->is_enabled): ?>

    <?php
    $plans = [];
    $available_payment_frequencies = [];

    $plans_result = $this->database->query("SELECT * FROM `plans` WHERE `status` = 1");

    while($plan = $plans_result->fetch_object()) {
        $plans[] = $plan;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->{$value . '_price'}) {
                $available_payment_frequencies[$value] = true;
            }
        }
    }

    ?>

    <?php if(count($plans)): ?>
        <div class="mb-4 d-flex justify-content-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <?php if(isset($available_payment_frequencies['monthly'])): ?>
                    <label class="btn btn-outline-secondary active" data-payment-frequency="monthly">
                        <input type="radio" name="payment_frequency" checked> <?= $this->language->plan->custom_plan->monthly ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['annual'])): ?>
                    <label class="btn btn-outline-secondary" data-payment-frequency="annual">
                        <input type="radio" name="payment_frequency"> <?= $this->language->plan->custom_plan->annual ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['lifetime'])): ?>
                    <label class="btn btn-outline-secondary" data-payment-frequency="lifetime">
                        <input type="radio" name="payment_frequency"> <?= $this->language->plan->custom_plan->lifetime ?>
                    </label>
                <?php endif ?>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>


<div class="pricing pricing-palden d-flex flex-wrap justify-content-center">

    <?php if($this->settings->plan_free->status == 1): ?>

        <div class="pricing-item">
            <div class="pricing-deco">
                <h3 class="pricing-title"><?= $this->settings->plan_free->name ?></h3>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#f5f8ff"></path>
                </svg>

                <div class="pricing-price">
                    <?= $this->language->plan->free->price ?>
                </div>

                <div class="pricing-sub">&nbsp;</div>
            </div>

            <ul class="pricing-feature-list">
                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                    <div>
                        <?php if($this->settings->plan_free->settings->websites_limit == -1): ?>
                            <?= $this->language->global->plan_settings->unlimited_websites_limit ?>
                        <?php else: ?>
                            <?= sprintf($this->language->global->plan_settings->websites_limit, '<strong>' . nr($this->settings->plan_free->settings->websites_limit) . '</strong>') ?>
                        <?php endif ?>
                    </div>
                </li>

                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                    <div>
                        <?php if($this->settings->plan_free->settings->sessions_events_limit == -1): ?>
                            <?= $this->language->global->plan_settings->unlimited_sessions_events_limit ?>
                        <?php else: ?>
                            <?= sprintf($this->language->global->plan_settings->sessions_events_limit, '<strong>' . nr($this->settings->plan_free->settings->sessions_events_limit, 1, true) . '</strong>') ?>
                            <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                        <?php endif ?>
                    </div>
                </li>

                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_free->settings->events_children_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                    <div>
                        <?php if($this->settings->plan_free->settings->events_children_limit == -1): ?>
                            <?= $this->language->global->plan_settings->unlimited_events_children_limit ?>
                        <?php else: ?>
                            <?= sprintf($this->language->global->plan_settings->events_children_limit, '<strong>' . nr($this->settings->plan_free->settings->events_children_limit, 1, true) . '</strong>') ?>
                            <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                        <?php endif ?>
                    </div>
                </li>

                <?php if($this->settings->plan_free->settings->events_children_limit != 0): ?>
                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                        <div>
                            <?= sprintf($this->language->global->plan_settings->events_children_retention, '<strong>' . nr($this->settings->plan_free->settings->events_children_retention) . '</strong>') ?>
                        </div>
                    </li>
                <?php endif ?>

                <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_free->settings->sessions_replays_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($this->settings->plan_free->settings->sessions_replays_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_sessions_replays_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->sessions_replays_limit, '<strong>' . nr($this->settings->plan_free->settings->sessions_replays_limit, 1, true) . '</strong>') ?>
                                <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                            <?php endif ?>
                        </div>
                    </li>

                    <?php if($this->settings->plan_free->settings->sessions_replays_limit != 0): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                            <div>
                                <?= sprintf($this->language->global->plan_settings->sessions_replays_retention, '<strong>' . nr($this->settings->plan_free->settings->sessions_replays_retention) . '</strong>') ?>
                            </div>
                        </li>
                    <?php endif ?>
                <?php endif ?>

                <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_free->settings->websites_heatmaps_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($this->settings->plan_free->settings->websites_heatmaps_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_websites_heatmaps_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->websites_heatmaps_limit, '<strong>' . nr($this->settings->plan_free->settings->websites_heatmaps_limit, 1, true) . '</strong>') ?>
                            <?php endif ?>
                        </div>
                    </li>
                <?php endif ?>

                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_free->settings->websites_goals_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                    <div>
                        <?php if($this->settings->plan_free->settings->websites_goals_limit == -1): ?>
                            <?= $this->language->global->plan_settings->unlimited_websites_goals_limit ?>
                        <?php else: ?>
                            <?= sprintf($this->language->global->plan_settings->websites_goals_limit, '<strong>' . nr($this->settings->plan_free->settings->websites_goals_limit, 1, true) . '</strong>') ?>
                        <?php endif ?>
                    </div>
                </li>

                <?php if($this->settings->analytics->email_reports_is_enabled): ?>
                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_free->settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div>
                        <?= $this->settings->analytics->email_reports_is_enabled ? $this->language->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} : $this->language->global->plan_settings->email_reports_is_enabled ?>
                    </div>
                </li>
                <?php endif ?>

                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_free->settings->teams_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div>
                        <?= $this->language->global->plan_settings->teams_is_enabled ?>
                    </div>
                </li>

                <li class="pricing-feature d-flex align-items-baseline mb-2">
                    <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_free->settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div>
                        <?= $this->language->global->plan_settings->no_ads ?>
                    </div>
                </li>
            </ul>

            <?php if(Authentication::check() && $this->user->plan_id == 'free'): ?>
                <button class="btn btn-secondary btn-lg rounded-pill pricing-action-disabled"><?= $this->language->plan->button->already_free ?></button>
            <?php else: ?>
                <a href="<?= Authentication::check() ? url('pay/free') : url('register?redirect=pay/free') ?>" class="btn btn-primary btn-lg rounded-pill pricing-action"><?= $this->language->plan->button->choose ?></a>
            <?php endif ?>
        </div>

    <?php endif ?>

    <?php if($this->settings->payment->is_enabled): ?>

        <?php if($this->settings->plan_trial->status == 1): ?>
            <div class="pricing-item">
                <div class="pricing-deco">
                    <h3 class="pricing-title"><?= $this->settings->plan_trial->name ?></h3>

                    <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                        <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#f5f8ff"></path>
                </svg>

                    <div class="pricing-price">
                        <?= $this->language->plan->trial->price ?>
                    </div>

                    <div class="pricing-sub">&nbsp;</div>
                </div>

                <ul class="pricing-feature-list">
                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                        <div>
                            <?php if($this->settings->plan_trial->settings->websites_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_websites_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->websites_limit, '<strong>' . nr($this->settings->plan_trial->settings->websites_limit) . '</strong>') ?>
                            <?php endif ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                        <div>
                            <?php if($this->settings->plan_trial->settings->sessions_events_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_sessions_events_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->sessions_events_limit, '<strong>' . nr($this->settings->plan_trial->settings->sessions_events_limit, 1, true) . '</strong>') ?>
                                <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                            <?php endif ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_trial->settings->events_children_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($this->settings->plan_trial->settings->events_children_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_events_children_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->events_children_limit, '<strong>' . nr($this->settings->plan_trial->settings->events_children_limit, 1, true) . '</strong>') ?>
                                <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                            <?php endif ?>
                        </div>
                    </li>

                    <?php if($this->settings->plan_trial->settings->events_children_limit != 0): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                            <div>
                                <?= sprintf($this->language->global->plan_settings->events_children_retention, '<strong>' . nr($this->settings->plan_trial->settings->events_children_retention) . '</strong>') ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_trial->settings->sessions_replays_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                            <div>
                                <?php if($this->settings->plan_trial->settings->sessions_replays_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_sessions_replays_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->sessions_replays_limit, '<strong>' . nr($this->settings->plan_trial->settings->sessions_replays_limit, 1, true) . '</strong>') ?>
                                    <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                                <?php endif ?>
                            </div>
                        </li>

                        <?php if($this->settings->plan_trial->settings->sessions_replays_limit != 0): ?>
                            <li class="pricing-feature d-flex align-items-baseline mb-2">
                                <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                                <div>
                                    <?= sprintf($this->language->global->plan_settings->sessions_replays_retention, '<strong>' . nr($this->settings->plan_trial->settings->sessions_replays_retention) . '</strong>') ?>
                                </div>
                            </li>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_trial->settings->websites_heatmaps_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                            <div>
                                <?php if($this->settings->plan_trial->settings->websites_heatmaps_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_websites_heatmaps_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->websites_heatmaps_limit, '<strong>' . nr($this->settings->plan_trial->settings->websites_heatmaps_limit, 1, true) . '</strong>') ?>
                                <?php endif ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$this->settings->plan_trial->settings->websites_goals_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($this->settings->plan_trial->settings->websites_goals_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_websites_goals_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->websites_goals_limit, '<strong>' . nr($this->settings->plan_trial->settings->websites_goals_limit, 1, true) . '</strong>') ?>
                            <?php endif ?>
                        </div>
                    </li>

                    <?php if($this->settings->analytics->email_reports_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_trial->settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                            <div>
                                <?= $this->settings->analytics->email_reports_is_enabled ? $this->language->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} : $this->language->global->plan_settings->email_reports_is_enabled ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_trial->settings->teams_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                        <div>
                            <?= $this->language->global->plan_settings->teams_is_enabled ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= $this->settings->plan_trial->settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                        <div>
                            <?= $this->language->global->plan_settings->no_ads ?>
                        </div>
                    </li>
                </ul>

                <?php if(Authentication::check() && $this->user->plan_trial_done): ?>
                    <button class="btn btn-secondary btn-lg rounded-pill pricing-action-disabled"><?= $this->language->plan->button->disabled ?></button>
                <?php else: ?>
                    <a href="<?= Authentication::check() ? url('pay/trial') : url('register?redirect=pay/trial') ?>" class="btn btn-primary btn-lg rounded-pill pricing-action"><?= $this->language->plan->button->choose ?></a>
                <?php endif ?>
            </div>

        <?php endif ?>

        <?php foreach($plans as $plan): ?>

            <?php $plan->settings = json_decode($plan->settings) ?>

            <div
                class="pricing-item"
                data-plan-monthly="<?= json_encode((bool) $plan->monthly_price) ?>"
                data-plan-annual="<?= json_encode((bool) $plan->annual_price) ?>"
                data-plan-lifetime="<?= json_encode((bool) $plan->lifetime_price) ?>"
            >
                <div class="pricing-deco">
                    <h3 class="pricing-title"><?= $plan->name ?></h3>

                    <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                        <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                        <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#f5f8ff"></path>
                    </svg>

                    <div class="pricing-price">
                        <span class="d-none" data-plan-payment-frequency="monthly"><?= $plan->monthly_price ?></span>
                        <span class="d-none" data-plan-payment-frequency="annual"><?= $plan->annual_price ?></span>
                        <span class="d-none" data-plan-payment-frequency="lifetime"><?= $plan->lifetime_price ?></span>
                        <span class="pricing-currency"><?= $this->settings->payment->currency ?></span>
                    </div>

                    <div class="pricing-sub">
                        <span class="d-none" data-plan-payment-frequency="monthly"><?= $this->language->plan->custom_plan->monthly_payments ?></span>
                        <span class="d-none" data-plan-payment-frequency="annual"><?= $this->language->plan->custom_plan->annual_payments ?></span>
                        <span class="d-none" data-plan-payment-frequency="lifetime"><?= $this->language->plan->custom_plan->lifetime_payments ?></span>
                    </div>
                </div>

                <ul class="pricing-feature-list">
                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                        <div>
                            <?php if($plan->settings->websites_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_websites_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->websites_limit, '<strong>' . nr($plan->settings->websites_limit) . '</strong>') ?>
                            <?php endif ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
                        <div>
                            <?php if($plan->settings->sessions_events_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_sessions_events_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->sessions_events_limit, '<strong>' . nr($plan->settings->sessions_events_limit, 1, true) . '</strong>') ?>
                                <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                            <?php endif ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$plan->settings->events_children_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($plan->settings->events_children_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_events_children_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->events_children_limit, '<strong>' . nr($plan->settings->events_children_limit, 1, true) . '</strong>') ?>
                                <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                            <?php endif ?>
                        </div>
                    </li>

                    <?php if($plan->settings->events_children_limit != 0): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                            <div>
                                <?= sprintf($this->language->global->plan_settings->events_children_retention, '<strong>' . nr($plan->settings->events_children_retention) . '</strong>') ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <?php if($this->settings->analytics->sessions_replays_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= !$plan->settings->sessions_replays_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                            <div>
                                <?php if($plan->settings->sessions_replays_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_sessions_replays_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->sessions_replays_limit, '<strong>' . nr($plan->settings->sessions_replays_limit, 1, true) . '</strong>') ?>
                                    <small class="text-muted"><?= $this->language->global->plan_settings->per_month ?></small>
                                <?php endif ?>
                            </div>
                        </li>

                        <?php if($plan->settings->sessions_replays_limit != 0): ?>
                            <li class="pricing-feature d-flex align-items-baseline mb-2">
                                <i class="fa fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                                <div>
                                    <?= sprintf($this->language->global->plan_settings->sessions_replays_retention, '<strong>' . nr($plan->settings->sessions_replays_retention) . '</strong>') ?>
                                </div>
                            </li>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if($this->settings->analytics->websites_heatmaps_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= !$plan->settings->websites_heatmaps_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                            <div>
                                <?php if($plan->settings->websites_heatmaps_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_websites_heatmaps_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->websites_heatmaps_limit, '<strong>' . nr($plan->settings->websites_heatmaps_limit, 1, true) . '</strong>') ?>
                                <?php endif ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= !$plan->settings->websites_goals_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
                        <div>
                            <?php if($plan->settings->websites_goals_limit == -1): ?>
                                <?= $this->language->global->plan_settings->unlimited_websites_goals_limit ?>
                            <?php else: ?>
                                <?= sprintf($this->language->global->plan_settings->websites_goals_limit, '<strong>' . nr($plan->settings->websites_goals_limit, 1, true) . '</strong>') ?>
                            <?php endif ?>
                        </div>
                    </li>

                    <?php if($this->settings->analytics->email_reports_is_enabled): ?>
                        <li class="pricing-feature d-flex align-items-baseline mb-2">
                            <i class="fa fa-fw fa-sm mr-3 <?= $plan->settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                            <div>
                                <?= $this->settings->analytics->email_reports_is_enabled ? $this->language->global->plan_settings->{'email_reports_is_enabled_' . $this->settings->analytics->email_reports_is_enabled} : $this->language->global->plan_settings->email_reports_is_enabled ?>
                            </div>
                        </li>
                    <?php endif ?>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= $plan->settings->teams_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                        <div>
                            <?= $this->language->global->plan_settings->teams_is_enabled ?>
                        </div>
                    </li>

                    <li class="pricing-feature d-flex align-items-baseline mb-2">
                        <i class="fa fa-fw fa-sm mr-3 <?= $plan->settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                        <div>
                            <?= $this->language->global->plan_settings->no_ads ?>
                        </div>
                    </li>
                </ul>

                <a href="<?= Authentication::check() ? url('pay/' . $plan->plan_id) : url('register?redirect=pay/' . $plan->plan_id) ?>" class="btn btn-primary btn-lg rounded-pill pricing-action"><?= $this->language->plan->button->choose ?></a>
            </div>

        <?php endforeach ?>

        <?php ob_start() ?>
            <script>
            'use strict';

            let payment_frequency_handler = (event = null) => {

                let payment_frequency = null;

                if(event) {
                    payment_frequency = $(event.currentTarget).data('payment-frequency');
                } else {
                    payment_frequency = $('[name="payment_frequency"]:checked').closest('label').data('payment-frequency');
                }

                switch(payment_frequency) {
                    case 'monthly':
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break;

                    case 'annual':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break

                    case 'lifetime':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');

                        break
                }

                $(`[data-plan-payment-frequency="${payment_frequency}"]`).addClass('d-inline-block');

                $(`[data-plan-${payment_frequency}="true"]`).removeClass('d-none').addClass('');
                $(`[data-plan-${payment_frequency}="false"]`).addClass('d-none').removeClass('');

            };

            $('[data-payment-frequency]').on('click', payment_frequency_handler);

            payment_frequency_handler();
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php endif ?>
</div>
