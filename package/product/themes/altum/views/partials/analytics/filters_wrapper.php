<?php defined('ALTUMCODE') || die() ?>

<div id="filters" class="card border-0 my-4" style="display: none;">
    <div class="card-body">

        <div class="row justify-content-between">
            <div class="col-12 col-md-auto">
                <h2 class="h4"><?= $this->language->analytics->filters->header ?></h2>
            </div>

            <div class="col-12 col-md-auto dropdown">
                <button type="button" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->analytics->filters->create ?>
                </button>
                <div id="create_filters_list" class="dropdown-menu dropdown-menu-right">
                    <?php if(!$data->available_filters || $data->available_filters == 'websites_visitors'): ?>
                    <h6 class="dropdown-header"><?= $this->language->analytics->visitors ?></h6>
                    <button type="button" class="dropdown-item" data-filter-by="country_code"><i class="fa fa-fw fa-sm fa-globe text-muted"></i> <?= $this->language->analytics->filters->by->country_code ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="screen_resolution"><i class="fa fa-fw fa-sm fa-desktop text-muted"></i> <?= $this->language->analytics->filters->by->screen_resolution ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="browser_language"><i class="fa fa-fw fa-sm fa-language text-muted"></i> <?= $this->language->analytics->filters->by->browser_language ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="os_name"><i class="fa fa-fw fa-sm fa-server text-muted"></i> <?= $this->language->analytics->filters->by->os_name ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="device_type"><i class="fa fa-fw fa-sm fa-laptop text-muted"></i> <?= $this->language->analytics->filters->by->device_type ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="browser_name"><i class="fa fa-fw fa-sm fa-window-restore text-muted"></i> <?= $this->language->analytics->filters->by->browser_name ?></button>
                    <?php endif ?>

                    <?php if(!$data->available_filters || $data->available_filters == 'sessions_events'): ?>
                    <h6 class="dropdown-header"><?= $this->language->analytics->pageviews ?></h6>
                    <button type="button" class="dropdown-item" data-filter-by="path"><i class="fa fa-fw fa-sm fa-copy text-muted"></i> <?= $this->language->analytics->filters->by->path ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="title"><i class="fa fa-fw fa-sm fa-heading text-muted"></i> <?= $this->language->analytics->filters->by->title ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="referrer_host"><i class="fa fa-fw fa-sm fa-random text-muted"></i> <?= $this->language->analytics->filters->by->referrer_host ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="utm_source"><i class="fa fa-fw fa-sm fa-link text-muted"></i> <?= $this->language->analytics->filters->by->utm_source ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="utm_medium"><i class="fa fa-fw fa-sm fa-link text-muted"></i> <?= $this->language->analytics->filters->by->utm_medium ?></button>
                    <button type="button" class="dropdown-item" data-filter-by="utm_campaign"><i class="fa fa-fw fa-sm fa-link text-muted"></i> <?= $this->language->analytics->filters->by->utm_campaign ?></button>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <p class="text-muted"><?= $this->language->analytics->filters->subheader ?></p>

        <form id="filters_form" action="" method="post" role="form">
            <div id="filters_list"></div>

            <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->submit ?></button>
        </form>

    </div>
</div>

<template id="template_filter">
    <div class="filter">
        <input type="hidden" name="filter_by[]" value="" />

        <div class="row mb-4 mb-md-3">
            <div class="col-12 col-md-3 mb-3 mb-md-0">
                <span id="template_filter_by_display" class="font-weight-bold"></span>
            </div>

            <div class="col-12 col-md-3 mb-3 mb-md-0">
                <select name="filter_rule[]" class="form-control form-control-lg ml-md-3">
                    <option value="is"><?= $this->language->analytics->filters->rule->is ?></option>
                    <option value="is_not"><?= $this->language->analytics->filters->rule->is_not ?></option>
                    <option value="contains"><?= $this->language->analytics->filters->rule->contains ?></option>
                    <option value="starts_with"><?= $this->language->analytics->filters->rule->starts_with ?></option>
                    <option value="ends_with"><?= $this->language->analytics->filters->rule->ends_with ?></option>
                </select>
            </div>

            <div class="col-12 col-md-5 mb-3 mb-md-0">
                <input type="text" name="filter_value[]" class="form-control form-control-lg ml-md-3" />
            </div>

            <div class="col-1 d-flex">
                <button type="button" class="btn btn-outline-gray-400 ml-md-3 text-muted align-self-center filter_delete" data-toggle="tooltip" title="<?= $this->language->global->delete ?>"><i class="fa fa-fw fa-times fa-sm"></i></button>
            </div>
        </div>
    </div>
</template>

<?php ob_start() ?>
<script>
    /* Populate with already existing filters */
    let filters_cookie = get_cookie('filters');
    let template = document.querySelector('#template_filter');

    if(filters_cookie) {
        let filters = JSON.parse(filters_cookie);

        let filters_to_show = 0;

        for(let filter of filters) {
            /* Prepare template */
            let clone = template.content.cloneNode(true);

            let filter_origin = $(`button[data-filter-by="${filter.by}"]`);


            /* Add the data in the template */
            $(clone).find('#template_filter_by_display').html(filter_origin.html());
            $(clone).find('[name="filter_by\[\]"]').val(filter.by);
            $(clone).find(`[name="filter_rule\[\]"] option[value="${filter.rule}"]`).attr('selected', 'selected');
            $(clone).find('[name="filter_value\[\]"]').val(filter.value);

            /* Hide the filter if it shouldn't show */
            if(!filter_origin.length) {
                $(clone).find('.filter').hide();
            }

            else {
                filters_to_show++;
            }

            $('#filters_list').append(clone);
        }

        if(filters_to_show) {
            $('#filters').show();
        }
    }

    /* Create new filter handler */
    $('#create_filters_list > button').on('click', event => {

        let template = document.querySelector('#template_filter');

        /* Prepare template */
        let clone = template.content.cloneNode(true);

        $(clone).find('#template_filter_by_display').html($(event.currentTarget).html());
        $(clone).find('[name="filter_by\[\]"]').val($(event.currentTarget).data('filter-by'));

        /* Add */
        $('#filters_list').append(clone);

        /* Initiate handlers */
        initiate_delete_handler();

        /* Refresh tooltips */
        $('[data-toggle="tooltip"]').tooltip();
    });

    /* Delete handler */
    let initiate_delete_handler = () => {
        $('.filter_delete').off().on('click', event => {
            $(event.currentTarget).tooltip('hide');

            $(event.currentTarget).closest('.filter').remove();

            event.preventDefault();
        });
    };

    /* Initiate handlers */
    initiate_delete_handler();

    /* Handling the form submission */
    $('#filters_form').on('submit', event => {

        let form = $(event.currentTarget).serializeArray();
        let filters = [];

        for(let i = 0; i <= form.length -1; i += 3) {

            filters.push({
                by: form[i].value,
                rule: form[i+1].value,
                value: form[i+2].value
            })

        }

        /* Set the cookie */
        set_cookie('filters', JSON.stringify(filters), 30);

        redirect(<?= json_encode(\Altum\Routing\Router::$controller_key) ?>);

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
