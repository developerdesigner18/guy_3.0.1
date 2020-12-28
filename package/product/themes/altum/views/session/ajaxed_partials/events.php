<?php defined('ALTUMCODE') || die() ?>

<div class="animate__animated animate__fadeIn">
<?php $i = 1; ?>
<?php foreach($data->events as $event): ?>
    <div class="card bg-gray-200 border-0">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 mb-md-0">
                <span class=""><i class="<?= $this->language->analytics->{$event->type}->icon ?> text-muted"></i> <?= $this->language->analytics->{$event->type}->name ?> <span class="ml-1 text-primary"><?= $event->title ?></span></span>
                <small class="text-muted"><?= \Altum\Date::get($event->date, 3) ?></small>
            </div>

            <div class="d-flex flex-column">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <span class="mb-2 mb-md-0"><small><?= $this->website->scheme . $this->website->host . $event->path ?></small></span>

                    <small class="text-muted" data-toggle="tooltip" title="<?= $this->language->analytics->viewport ?>">
                        <i class="fa fa-window-maximize"></i> <?= $event->viewport_width . 'x' . $event->viewport_height ?>
                    </small>
                </div>

                <?php if($event->referrer_host): ?>
                <span class="mt-2 mt-md-0"><small><?= sprintf($this->language->analytics->referred_by, $event->referrer_host . $event->referrer_path) ?></small></span>
                <?php endif ?>
            </div>

            <?php $j = 1; ?>
            <?php if(isset($data->events_children[$event->event_id])): ?>
                <div class="my-3">
                    <?php foreach($data->events_children[$event->event_id] as $event_child): ?>
                    <div class="card bg-gray-400 border-0 p-2">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <span>
                                <i class="<?= $this->language->analytics->{$event_child->type}->icon ?> text-muted"></i> <?= $this->language->analytics->{$event_child->type}->name ?>

                                <?php if($event_child->type == 'click'): ?>
                                    <small class="ml-1">
                                        <span class="text-primary"><?= sprintf($this->language->analytics->{$event_child->type}->value, !empty($event_child->data->text) ? $event_child->data->text : 'N/A') ?></span>

                                        <?php if($event_child->count > 1): ?>
                                        x <?= $event_child->count ?>
                                        <?php endif ?>
                                    </small>
                                <?php elseif($event_child->type == 'resize'): ?>
                                    <small class="ml-1 text-primary"><?= sprintf($this->language->analytics->{$event_child->type}->value,$event_child->data->viewport->width . 'x' . $event_child->data->viewport->height) ?></small>
                                <?php elseif($event_child->type == 'scroll'): ?>
                                    <?php //TODO: Remove ?? 0 after a few updates. ?>
                                    <small class="ml-1 text-primary"><?= sprintf($this->language->analytics->{$event_child->type}->value, $event_child->data->scroll->percentage ?? 0) ?></small>
                                <?php endif ?>
                            </span>
                            <small class="text-muted"><?= \Altum\Date::get($event_child->date, 3) ?></small>
                        </div>
                    </div>

                    <?php if($j++ != count($data->events_children[$event->event_id])): ?>
                    <div class="text-center"><i class="fa fa-fw fa-arrow-down fa-sm text-gray-400"></i></div>
                    <?php endif ?>

                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <?php if($i++ != count($data->events)): ?>
        <div class="text-center"><i class="fa fa-fw fa-arrow-down fa-sm text-muted"></i></div>
    <?php endif ?>
<?php endforeach ?>
</div>
