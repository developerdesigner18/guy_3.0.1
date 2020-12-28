<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3"><i class="fa fa-fw fa-xs fa-users text-primary-900 mr-2"></i> <?= $this->language->admin_websites->header ?></h1>
</div>

<div class="row justify-content-between mb-4">
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <div class="card d-flex flex-row h-100">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-eye mr-1"></i> <?= $this->language->admin_websites->display->total_sessions_events ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->total_sessions_events) ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <div class="card d-flex flex-row h-100">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= $this->language->admin_websites->display->total_events_children ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->total_events_children) ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <div class="card d-flex flex-row h-100">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-video mr-1"></i> <?= $this->language->admin_websites->display->total_sessions_replays ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->total_sessions_replays) ?></span></div>
            </div>
        </div>
    </div>
</div>

<?php display_notifications() ?>

<div class="mt-3">
    <table id="results" class="table table-custom">
        <thead>
        <tr>
            <th><?= $this->language->admin_websites->table->email ?></th>
            <th><?= $this->language->admin_websites->table->host ?></th>
            <th><?= $this->language->admin_websites->table->usage ?></th>
            <th><?= $this->language->admin_websites->table->is_enabled ?></th>
            <th><?= $this->language->admin_websites->table->date ?></th>
            <th class="disable_export"></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datatables.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/datatables.min.js' ?>"></script>
<script>
let datatable = $('#results').DataTable({
    language: <?= json_encode($this->language->datatable) ?>,
    search: {
        search: <?= json_encode($_GET['email'] ?? '') ?>
    },
    serverSide: true,
    processing: true,
    ajax: {
        url: <?= json_encode(url('admin/websites/read')) ?>,
        type: 'POST'
    },
    autoWidth: false,
    lengthMenu: [[25, 50, 100], [25, 50, 100]],
    buttons: [
        {
            extend: 'csvHtml5',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: ':not(.disable_export)'
            }
        }
    ],
    columns: [
        {
            data: 'email',
            searchable: true,
            sortable: true
        },
        {
            data: 'host',
            searchable: true,
            sortable: true
        },
        {
            data: 'usage',
            searchable: false,
            sortable: false
        },
        {
            data: 'is_enabled',
            searchable: false,
            sortable: true
        },
        {
            data: 'date',
            searchable: false,
            sortable: true
        },
        {
            data: 'actions',
            searchable: false,
            sortable: false
        }
    ],
    responsive: true,
    drawCallback: () => {
        $('[data-toggle="tooltip"]').tooltip();
    },
    dom: "<'row'<'col-sm-12 col-md-6'<'row'<'col-auto'B><'col-auto'l>>><'col-sm-12 col-md-6'f>>" +
        "<'table-responsive table-custom-container my-3'tr>" +
        "<'row'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7'p>>"
});
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
