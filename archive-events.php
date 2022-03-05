<?php
get_header();
?>
<div id="event-calendar">
    <?= do_shortcode('[fullcalendar]') ?>
</div>

<?php get_template_part('parts/modal-event-creator') ?>
<?php get_template_part('parts/modal-event-editor') ?>

<style type="text/css">
tr.fc-minor {
    border-bottom-width: 2px;
}
</style>
<script type="text/javascript">
// Make it so that clicking events opens them in a new tab (so we keep our place)
jQuery(document).ready(function() {
    jQuery('.wpfc-calendar').on( 'click', '.fc-event', function(e){
        e.preventDefault();
        window.open( jQuery(this).attr('href'), '_blank' );
    });
});

// Modify the arguments passed to full calendar
jQuery(document).on('wpfc_fullcalendar_args', function(event, args) {
    // Disable scrollbar on agenda view
    args.height = 'auto';

    args.selectable = true;

    args.select = function(start, end, jsEvent, view) {
        jQuery('#eventCreatorModal').modal('show');

        var start_dt = start.format();
        if (!start.hasTime()) {
            start_dt += 'T00:00:00';
        }
        var end_dt = end.format();
        if (!end.hasTime()) {
            end_dt += 'T00:00:00';
        }

        for (var i; i < tinyMCE.editors.length; i++) {
            tinyMCE.editors[i].undoManager.clear();
        }
        jQuery('#new_event_title').val('');
        jQuery('#new_event_title').focus();
        jQuery('#new_event_start_date').val(start_dt);
        jQuery('#new_event_end_date').val(end_dt);
        jQuery('#new_event_all_day').prop('checked', !(start.hasTime() && end.hasTime()));
        tinyMCE.get('new_event_description').setContent('');
    }

    args.eventResize = function(event, delta, revertFunc) {

        //alert(event.title + " end is now " + event.end.format());

        //if (!confirm("is this okay?")) {
        //    revertFunc();
        //}
        var data = {
            id: event.id,
            start_date_time: event.start.format(),
            end_date_time: event.end.format(),
        };

console.log(data);
        jc_edit_event(data, jQuery(document));
    };

    <?php if (isset($_REQUEST['date'])) { ?>
        args.defaultDate = "<?= esc_attr($_REQUEST['date']) ?>";
    <?php } ?>

    <?php if (isset($_REQUEST['view'])) { ?>
        args.defaultView = "<?= esc_attr($_REQUEST['view']) ?>";
    <?php } ?>

    args.navLinks = true;
    args.eventLimit = 4;
});

(function($) {
    $(document).on('jc-events-updated', function(event, args) {
        $('.wpfc-calendar').first().fullCalendar('refetchEvents');
    });
})(jQuery);
</script>
<?php
get_footer();
?>
