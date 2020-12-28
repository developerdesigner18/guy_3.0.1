<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="cities_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="p-3">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title"><?= $this->language->cities_modal->header ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= $this->language->global->close ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= $this->language->cities_modal->subheader ?></p>
            </div>

            <div class="modal-body">
                <div class="notification-container"></div>

                <div id="cities_result"></div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#cities_modal').on('show.bs.modal', event => {
        let loading_html = $('#loading').html();

        /* Basic data to use for fetching extra data */
        let website_id = $('input[name="website_id"]').val();
        let tracking_type = <?= json_encode($this->website->tracking_type) ?>;
        let start_date = $('input[name="start_date"]').val();
        let end_date = $('input[name="end_date"]').val();
        let country_code = $(event.relatedTarget).data('country-code');

        /* Place the loading html */
        $('#cities_result').html(loading_html);

        /* Build the query */
        let url_query = build_url_query({
            website_id,
            start_date,
            end_date,
            global_token,
            request_type: 'cities',
            limit: -1,
            bounce_rate: true,
            country_code
        });

        $.ajax({
            type: 'GET',
            url: `${url}dashboard-ajax-${tracking_type}?${url_query}`,
            success: (data) => {
                if (data.status == 'error') {
                    let notification_container = $(event.currentTarget).find('.notification-container');

                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    $('#cities_result').html(data.details.html);

                }
            },
            dataType: 'json'
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
