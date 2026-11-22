<?php // archive.php — controller mỏng
get_header();
?>
<main class="l-main">
    <div class="l-container">
        <ul class="p-post-list">
            <?php while (have_posts()) : the_post(); ?>
                <li class="p-post-list__item">
                    <?php view('card.post', [
                        'title'     => get_the_title(),
                        'permalink' => (string) get_permalink(),
                        'thumbnail' => (string) get_the_post_thumbnail(null, 'card', ['loading' => 'lazy']),
                        'excerpt'   => wp_trim_words(get_the_excerpt(), 24),
                    ]); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</main>
<?php get_footer();

