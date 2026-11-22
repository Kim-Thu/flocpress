<?php
declare(strict_types=1);

defined('ABSPATH') || exit;

get_header(); ?>

<main class="l-main">
    <div class="l-container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('c-card'); ?>>
                    <h2 class="c-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="c-card__body">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Không có bài viết nào.', 'flocpress'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer();

