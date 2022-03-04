<!-- Modal -->
<div class="modal fade" id="eventCreatorModal" tabindex="-1" aria-labelledby="eventCreatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventCreatorModalLabel">Add New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="new_event_title">Title</label>
                <input type="text" name="new_event_title" id="new_event_title" autofocus />
                <label for="new_event_start_date">Start Date</label>
                <input id="new_event_start_date" type="datetime-local" value="" name="new_event_start_date" />
                <label for="new_event_end_date">End Date</label>
                <input id="new_event_end_date" type="datetime-local" value="" name="new_event_end_date" />
                <label for="new_event_all_day">All Day?</label>
                <input id="new_event_all_day" type="checkbox" name="new_event_all_day" value="1" />
                <hr/>
                <label for="new_event_description">Description</label>
                <?php wp_editor('', 'new_event_description') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="new_event_save_button" type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
#eventCreatorModal label {
    font-weight: bold;
}
</style>
<script type="text/javascript">
(function($) {
    $('#new_event_all_day').change(function() {
        if ($('#new_event_all_day').is(':checked')) return;


        console.log('Checking time');
        var start_time = Date.parse($('#new_event_start_date').val());
        var end_time = Date.parse($('#new_event_end_date').val());
        var difference = end_time - start_time;

        var full_day = 1000 /*ms*/ * 60 /*sec*/ * 60 /*min*/ * 24 /*hr*/;

        if (difference >= full_day) {
            // Keep whatever hour difference, but get rid of the days
            difference %= full_day;
            console.log(start_time + difference);
            var new_end_date = new Date(start_time + difference);
            var m = moment(new_end_date.toISOString());
            $('#new_event_end_date').val(m.format().substring(0,19));
        }
    });
    $('#eventCreatorModal').on('shown.bs.modal', function() {
        console.log('test');
        document.getElementById('new_event_title').focus();
    });

    $('#new_event_save_button').click(function() {
        var data = {
            title: $('#new_event_title').val(),
            description: tinyMCE.get('new_event_description').getContent(),
            start_date_time: $('#new_event_start_date').val(),
            end_date_time: $('#new_event_end_date').val(),
            is_all_day: $('#new_event_all_day').is(':checked'),
        };
        jc_create_event(data, $(document));
    });
})(jQuery);
</script>
