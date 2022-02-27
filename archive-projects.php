<?php
get_header();
$projects = array();
if (have_posts()) {
    while(have_posts()) {
        the_post();
        $projects[] = array(
            'title' => get_the_title(),
            'github_link' => get_field('github_link'),
            'post_thumbnail' => get_the_post_thumbnail(),
            'permalink' => get_the_permalink(),
        );
    }
}
?>
<div class="project-grid flex-grid">
    <?php if (!count($projects)) { ?>
        <div class="no-projects">
            No projects yet, please check back later!
        </div>
    <?php } else { ?>
        <?php foreach ($projects as $project) { ?>
            <div class="project-block">
                <div class="project-image">
                    <?php if ($project['post_thumbnail']) { ?>
                        <a href="<?= esc_url($project['permalink']) ?>"><?= $project['post_thumbnail'] ?></a>
                    <?php } else { ?>
                        <a href="<?= esc_url($project['permalink']) ?>"><img class="attachment-post-thumbnail size-post-thumbnail wp-post-image" src="<?= get_stylesheet_directory_uri() ?>/assets/images/no-image-available.png"/></a>
                    <?php } ?>
                </div>
                <div class="project-title text-center">
                    <a href="<?= esc_url($project['permalink']) ?>"><?= $project['title'] ?></a>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<?php
get_footer();
?>
