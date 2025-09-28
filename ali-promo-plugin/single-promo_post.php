<?php get_header(); ?>

<div id="primary" class="content-area alipromo-single-container">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post(); 
            // Получаем экземпляр нашего плагина
            $ali_promo = ali_promo_plugin_instance();
        
            // Получаем метаданные поста
            $start_date = get_post_meta(get_the_ID(), '_promo_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_promo_end_date', true);
            $promo_url = get_post_meta(get_the_ID(), '_promo_url', true);
            $country_code = get_post_meta(get_the_ID(), '_promo_country_code', true);
            
            // Определяем язык и локаль страницы из централизованных данных
            $lang = 'ru'; // Язык по умолчанию
            $locale = 'ru_RU'; // Локаль по умолчанию

            if ($country_code && isset($ali_promo->countries[$country_code])) {
                $lang = $ali_promo->countries[$country_code]['lang'];
                $locale = $ali_promo->countries[$country_code]['locale'];
            }
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('alipromo-article'); ?>>
            <header class="alipromo-header">
                <h1 class="alipromo-title"><?php the_title(); ?></h1>
            </header>
            
            <div class="alipromo-content">
                <?php if ($start_date || $end_date) : 
                
                    // Временно переключаем локаль для корректного отображения дат
                    switch_to_locale($locale);
                ?>
                <div class="alipromo-dates">
                    <strong><?php echo esc_html($ali_promo->get_localized_text($lang, 'dates')); ?></strong>
                    <?php if ($start_date) echo ' ' . esc_html($ali_promo->get_localized_text($lang, 'from')) . ' ' . date_i18n(get_option('date_format'), strtotime($start_date)); ?>
                    <?php if ($end_date) echo ' ' . esc_html($ali_promo->get_localized_text($lang, 'to')) . ' ' . date_i18n(get_option('date_format'), strtotime($end_date)); ?>
                </div>
                <?php 
                    // Возвращаем локаль к исходной
                    restore_current_locale();
                
                endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php if ($promo_url) : ?>
                <div class="alipromo-cta-block">
                    <a href="<?php echo esc_url($promo_url); ?>" class="alipromo-cta-button" target="_blank" rel="nofollow noopener">
                        <?php echo esc_html($ali_promo->get_localized_text($lang, 'button')); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <footer class="alipromo-footer">
                <?php echo do_shortcode('[alipromo_related count="4"]'); ?>
            </footer>
            
        </article>

        <?php endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>