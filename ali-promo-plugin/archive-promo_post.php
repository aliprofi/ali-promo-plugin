<?php get_header(); ?>

<div id="primary" class="content-area alipromo-archive-container">
    <main id="main" class="site-main">

        <header class="alipromo-archive-header">
            <h1 class="alipromo-archive-title">
                <?php
                if (is_tax('promo_country')) {
                    single_term_title();
                } else {
                    post_type_archive_title();
                }
                ?>
            </h1>
            <div class="alipromo-archive-description">
                <?php echo term_description(); ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="alipromo-archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="alipromo-post-card">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="alipromo-card-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="alipromo-card-content">
                            <h3 class="alipromo-card-title"><?php the_title(); ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>

        <?php else : ?>
            <p>Промокоды не найдены.</p>
        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>