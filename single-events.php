<?php
get_header();
$tz = new DateTimeZone(wp_timezone_string());
$start_date = new DateTime(get_field('start_date_time'), $tz);
$end_date = new DateTime(get_field('end_date_time'), $tz);
?>
<div class="row">
    <div class="event-title col-12 text-center">
        <h1 class="no-margin"><?= get_the_title() ?></h1>
    </div>
</div>
<div class="row">
    <div class="event-thumbnail col-12">
        <?php if (has_post_thumbnail()) { ?>
            <?= get_the_post_thumbnail() ?>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class=" event-description col-8 offset-2">
        <div class="row mb-2">
            <div class="col-md-2 col-12">
                <i class="fa fa-calendar" aria-hidden="true"></i> <strong>Start Time:</strong>
            </div>
            <div class="col-md-10 col-12">
                <?= $start_date->format('F j, Y g:i a') ?>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-2 col-12">
                <i class="fa fa-calendar" aria-hidden="true"></i> <strong>End Time:</strong>
            </div>
            <div class="col-md-10 col-12">
                <?= $end_date->format('F j, Y g:i a') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?= get_field('description') ?>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 text-center">
        <a href="<?= esc_url(get_post_type_archive_link('events')) ?>">Back to Event Calendar</a>
    </div>
</div>
<?php
get_footer();
?>
