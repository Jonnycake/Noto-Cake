<?php
get_header();
?>
<div id="event-calendar">
</div>
<?php get_template_part('parts/modal-event-creator') ?>
<?php get_template_part('parts/modal-event-editor') ?>

<style type="text/css">
.fc-listWeek-button,
  .fc-dayGridMonth-button {
    margin-left: 0px !important;
}
</style>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('event-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
          start: 'today prev,next',
          center: 'title',
          end: 'timeGridDay listWeek dayGridMonth',
        },
      titleFormat: {year: 'numeric', month: 'short',},
      initialView: 'listWeek',
      events: jc_get_events,
      height: 'auto',
      contentHeight: 'auto',
      dayHeaderFormat: { weekday: 'short', month: 'numeric', day: 'numeric', omitCommas: true },
      navLinks: true,
      selectable: <?= get_current_user_id() ? 'true' : 'false' ?>,
      selectMirror: <?= get_current_user_id() ? 'true' : 'false' ?>,
      select: function(select_info) {
        jQuery('#eventCreatorModal').modal('show');

        var start_dt = select_info.startStr.substring(0,19);
        var end_dt = select_info.endStr.substring(0,19);

        if (select_info.allDay) {
            start_dt += 'T00:00:00';
            end_dt += 'T00:00:00';
        }

        console.log(select_info);

        for (var i; i < tinyMCE.editors.length; i++) {
            tinyMCE.editors[i].undoManager.clear();
        }
        jQuery('#new_event_title').val('');
        jQuery('#new_event_title').focus();
        jQuery('#new_event_start_date').val(start_dt);
        jQuery('#new_event_end_date').val(end_dt);
        jQuery('#new_event_all_day').prop('checked', select_info.allDay);
        tinyMCE.get('new_event_description').setContent('');
      },
      editable: <?= get_current_user_id() ? 'true' : 'false' ?>,
      eventResize: function(resize_info) {
        var event = resize_info.event;

        var data = {
            id: event.id,
            start_date_time: event.start.toISOString(),
            end_date_time: event.end.toISOString(),
            is_all_day: event.allDay,
        };

        jc_edit_event(data, jQuery(document));
      },
      eventDrop: function(drop_info) {
        var event = drop_info.event;

        var data = {
            id: event.id,
            start_date_time: event.start.toISOString(),
            end_date_time: event.end.toISOString(),
            is_all_day: event.allDay,
        };

        jc_edit_event(data, jQuery(document));
      },
      dayMaxEventRows: 3,
      buttonText: {
        listWeek: 'week',
      },
    });
    calendar.render();
    (function($) {
        $(document).on('jc-events-updated', function(event, args) {
            calendar.refetchEvents();
        });
    })(jQuery);
  });

</script>
<?php
get_footer();
?>
