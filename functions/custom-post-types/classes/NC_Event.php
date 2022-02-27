<?php
// Other functionality may break, but we can avoid totally breaking the site
//  if we just check for this here
if (!class_exists('\Spatie\IcalendarGenerator\Components\Event')) {
    return;
}

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\ValueObjects\DateTimeValue;

class NC_Event extends Event {
    public $nc_event_data = array();

    public function __construct($post = null)
    {
        $post = get_post($post);

        if (!$post) throw new Exception('Invalid post passed to NC_Event.');
        if ($post->post_type != 'events') throw new Exception('Invalid post type passed to NC_Event');

        $name = $post->post_title;

        $uuid = get_field('event_uuid', $post->ID);
        $description = get_field('description', $post->ID);
        $withTime = true; // TODO - For all day events, I think we can set this to false

        $post_date = get_post_datetime($post);

        $tz = new DateTimeZone(wp_timezone_string());
        $start_time = new DateTime(get_field('start_date_time', $post->ID), $tz);
        $end_time = new DateTime(get_field('end_date_time', $post->ID), $tz);

        $this->startsAt($start_time);
        $this->endsAt($end_time);
        $this->description($description);

        parent::__construct($name);

        if ($uuid) {
            $this->uniqueIdentifier($uuid);
        }

        $this->createdAt($post_date);

        $this->nc_event_data = array(
            'name' => $name,
            'description' => $description,
            'start_date_time' => $start_time,
            'end_date_time' => $end_time,
            'post_date' => $post_date,
            'uuid' => $uuid,
        );
    }

    public static function export($title, $events = array())
    {
        // Create the calendar
        $cal = Calendar::create($title);
        $cal->event($events);

        return $cal->get();
    }
}
