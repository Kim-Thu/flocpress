<?php // resources/views/card/post.php
/** @var string $title @var string $permalink @var string $thumbnail @var string $excerpt */
?>
<article class="c-card">
    <a class="c-card__link" href="<?= esc_url($permalink); ?>">
        <?php if ($thumbnail !== '') : ?>
            <div class="c-card__media"><?= $thumbnail; // đã là HTML từ wp_get_attachment_image ?></div>
        <?php endif; ?>
        <div class="c-card__body">
            <h3 class="c-card__title"><?= e($title); ?></h3>
            <p class="c-card__excerpt"><?= e($excerpt); ?></p>
        </div>
    </a>
</article>

