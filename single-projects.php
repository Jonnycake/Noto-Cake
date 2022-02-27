<?php
$thumbnail = get_the_post_thumbnail();
$title = get_the_title();
$description = get_field('description');
$source_link = get_field('source_link');
$live_link = get_field('live_link');

$has_links = $source_link || $live_link;

get_header();
?>
<div class="row">
    <div class="project-thumbnail col-12 col-md-8 offset-md-2">
        <?php if (has_post_thumbnail()) { ?>
            <?= get_the_post_thumbnail() ?>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="project-title col-12 col-md-8 offset-md-2 text-center">
        <h1 class="no-margin"><?= get_the_title() ?></h1>
    </div>
</div>
<div class="row">
    <div class=" project-description col-12 col-md-6 offset-md-2">
        <h2 class="no-margin">Description</h2>
        <?= get_field('description') ?>
    </div>
    <div class="project-links col-12 col-md-2">
        <h2 class="no-margin">Project Links</h2>
        <ul>
        <?php if ($source_link) { ?>
            <li><a target="_blank" href="<?= esc_url($source_link) ?>" title="View the source!"><i class="fas fa-laptop-code"></i> View Source</a></li>
        <?php } ?>
        <?php if ($live_link) { ?>
            <li><a target="_blank" href="<?= esc_url($live_link) ?>" title="See it live!"><i class="fas fa-eye"></i> View Live</a></li>
        <?php } ?>
        <?php if (!$has_links) { ?>
            <li>No project links available</li>
        <?php } ?>
        </ul>
    </div>
</div>
<div class="row project-navigation">
    <div class="prev-post-link col-6">
        <?php previous_post_link() ?>
    </div>
    <div class="next-post-link col-6">
        <?php next_post_link() ?>
    </div>
</div>
<?php
get_footer();
?>
