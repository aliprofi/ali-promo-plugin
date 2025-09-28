<?php
/**
 * Plugin Name: Ali Promo
 * Plugin URI: https://alipromocode.com
 * Description: Плагин для создания и управления страницами с промокодами, включая микроразметку, шорткоды и интерактивные элементы для копирования кодов.
 * Version: 1.1.1
 * Author: Ali Promo
 * Author URI: https://alipromocode.com
 * Text Domain: ali-promo
 */

// Запретить прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

class AliPromoPlugin {

    public $countries;

    public function __construct() {
        $this->define_countries();
        add_action('init', array($this, 'init'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_data'));
        add_action('wp_head', array($this, 'add_hreflang_and_schema'));
        add_filter('single_template', array($this, 'load_single_template'));
        add_filter('archive_template', array($this, 'load_archive_template'));
        add_filter('taxonomy_template', array($this, 'load_archive_template'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_filter('the_content', array($this, 'add_thumbnail_to_content'), 10);
        add_shortcode('alipromo_countries', array($this, 'shortcode_countries_block'));
        add_shortcode('alipromo_related', array($this, 'shortcode_related_promos'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    private function define_countries() {
        $this->countries = [
            'global' => ['name' => 'Global (выборочно)', 'lang' => 'en', 'hreflang' => 'x-default', 'native_name' => '🌍 Global', 'more_text' => 'More promo codes', 'locale' => 'en_US'],
            'us' => ['name' => 'США', 'lang' => 'en', 'hreflang' => 'en-US', 'native_name' => 'USA', 'more_text' => 'More promo codes', 'locale' => 'en_US'],
            'es' => ['name' => 'Испания', 'lang' => 'es', 'hreflang' => 'es-ES', 'native_name' => 'España', 'more_text' => 'Más códigos promocionales', 'locale' => 'es_ES'],
            'fr' => ['name' => 'Франция', 'lang' => 'fr', 'hreflang' => 'fr-FR', 'native_name' => 'France', 'more_text' => 'Plus de codes promo', 'locale' => 'fr_FR'],
            'br' => ['name' => 'Бразилия', 'lang' => 'pt', 'hreflang' => 'pt-BR', 'native_name' => 'Brasil', 'more_text' => 'Mais códigos promocionais', 'locale' => 'pt_BR'],
            'pl' => ['name' => 'Польша', 'lang' => 'pl', 'hreflang' => 'pl-PL', 'native_name' => 'Polska', 'more_text' => 'Więcej kodów promocyjnych', 'locale' => 'pl_PL'],
            'kr' => ['name' => 'Южная Корея', 'lang' => 'ko', 'hreflang' => 'ko-KR', 'native_name' => '대한민국', 'more_text' => '더 많은 프로모션 코드 보기', 'locale' => 'ko_KR'],
            'sa' => ['name' => 'Саудовская Аравия', 'lang' => 'ar', 'hreflang' => 'ar-SA', 'native_name' => 'المملكة العربية السعودية', 'more_text' => 'المزيد من أكواد الخصم', 'locale' => 'ar'],
            'ae' => ['name' => 'ОАЭ', 'lang' => 'ar', 'hreflang' => 'ar-AE', 'native_name' => 'الإمارات العربية المتحدة', 'more_text' => 'المزيد من أكواد الخصم', 'locale' => 'ar'],
            'kw' => ['name' => 'Кувейт', 'lang' => 'ar', 'hreflang' => 'ar-KW', 'native_name' => 'الكويت', 'more_text' => 'المزيد من أكواد الخصم', 'locale' => 'ar'],
            'om' => ['name' => 'Оман', 'lang' => 'ar', 'hreflang' => 'ar-OM', 'native_name' => 'عمان', 'more_text' => 'المزيد من أكواد الخصم', 'locale' => 'ar'],
            'bh' => ['name' => 'Бахрейн', 'lang' => 'ar', 'hreflang' => 'ar-BH', 'native_name' => 'البحرين', 'more_text' => 'المزيد من أكواد الخصم', 'locale' => 'ar'],
            'qa' => ['name' => 'Катар', 'lang' => 'ar', 'hreflang' => 'ar-QA', 'native_name' => 'قطر', 'more_text' => 'المزيد من أكواد الخصм', 'locale' => 'ar'],
            'ru' => ['name' => 'Россия', 'lang' => 'ru', 'hreflang' => 'ru-RU', 'native_name' => 'Россия', 'more_text' => 'Больше промокодов', 'locale' => 'ru_RU'],
            'cis' => ['name' => 'Страны СНГ', 'lang' => 'ru', 'hreflang' => 'ru', 'native_name' => 'СНГ', 'more_text' => 'Больше промокодов', 'locale' => 'ru_RU'],
            'it' => ['name' => 'Италия', 'lang' => 'it', 'hreflang' => 'it-IT', 'native_name' => 'Italia', 'more_text' => 'Altri codici promozionali', 'locale' => 'it_IT'],
            'de' => ['name' => 'Германия', 'lang' => 'de', 'hreflang' => 'de-DE', 'native_name' => 'Deutschland', 'more_text' => 'Mehr Promo-Codes', 'locale' => 'de_DE'],
            'nl' => ['name' => 'Нидерланды', 'lang' => 'nl', 'hreflang' => 'nl-NL', 'native_name' => 'Nederland', 'more_text' => 'Meer promocodes', 'locale' => 'nl_NL'],
            'ua' => ['name' => 'Украина', 'lang' => 'uk', 'hreflang' => 'uk-UA', 'native_name' => 'Україна', 'more_text' => 'Більше промокодів', 'locale' => 'uk'],
            'cl' => ['name' => 'Чили', 'lang' => 'es', 'hreflang' => 'es-CL', 'native_name' => 'Chile', 'more_text' => 'Más códigos promocionales', 'locale' => 'es_CL'],
            'il' => ['name' => 'Израиль', 'lang' => 'he', 'hreflang' => 'he-IL', 'native_name' => 'ישראל', 'more_text' => 'עוד קודי פרומו', 'locale' => 'he_IL'],
            'gb' => ['name' => 'Великобритания', 'lang' => 'en', 'hreflang' => 'en-GB', 'native_name' => 'United Kingdom', 'more_text' => 'More promo codes', 'locale' => 'en_GB'],
            'mx' => ['name' => 'Мексика', 'lang' => 'es', 'hreflang' => 'es-MX', 'native_name' => 'México', 'more_text' => 'Más códigos promocionales', 'locale' => 'es_MX'],
            'ca' => ['name' => 'Канада', 'lang' => 'en', 'hreflang' => 'en-CA', 'native_name' => 'Canada', 'more_text' => 'More promo codes', 'locale' => 'en_CA'],
            'au' => ['name' => 'Австралия', 'lang' => 'en', 'hreflang' => 'en-AU', 'native_name' => 'Australia', 'more_text' => 'More promo codes', 'locale' => 'en_AU'],
        ];
    }

    public function init() {
        $this->register_post_type();
        $this->register_taxonomy();
    }
    
    public function register_post_type() {
        $labels = array(
            'name' => 'Промокоды',
            'singular_name' => 'Промокод',
            'menu_name' => 'Промокоды',
            'add_new' => 'Добавить промокод',
            'add_new_item' => 'Добавить страницу промокодов',
            'edit_item' => 'Редактировать страницу',
            'all_items' => 'Все промокоды',
            'search_items' => 'Искать промокоды',
            'not_found' => 'Промокоды не найдены',
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'rewrite' => array('slug' => 'promo', 'with_front' => false),
            'has_archive' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-tickets-alt',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest' => true,
            'taxonomies' => array('promo_country'),
        );
        register_post_type('promo_post', $args);
    }
    
    public function register_taxonomy() {
        $labels = array(
            'name' => 'Страны',
            'singular_name' => 'Страна',
            'search_items' => 'Искать страны',
            'all_items' => 'Все страны',
            'parent_item' => 'Родительская страна',
            'parent_item_colon' => 'Родительская страна:',
            'edit_item' => 'Редактировать страну',
            'update_item' => 'Обновить страну',
            'add_new_item' => 'Добавить новую страну',
            'new_item_name' => 'Название новой страны',
            'menu_name' => 'Страны',
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'country'),
        );
        register_taxonomy('promo_country', array('promo_post'), $args);
    }

    public function add_meta_boxes() {
        add_meta_box('promo_meta_box', 'Параметры промокодов', array($this, 'meta_box_callback'), 'promo_post', 'side', 'high');
    }

    public function meta_box_callback($post) {
        wp_nonce_field('promo_meta_box_nonce', 'promo_meta_box_nonce');
        $start_date = get_post_meta($post->ID, '_promo_start_date', true);
        $end_date = get_post_meta($post->ID, '_promo_end_date', true);
        $promo_url = get_post_meta($post->ID, '_promo_url', true);
        $country_code = get_post_meta($post->ID, '_promo_country_code', true);

        echo '<p><label for="promo_start_date"><strong>Дата начала:</strong></label><br><input type="date" id="promo_start_date" name="promo_start_date" value="' . esc_attr($start_date) . '" style="width:100%;" /></p>';
        echo '<p><label for="promo_end_date"><strong>Дата окончания:</strong></label><br><input type="date" id="promo_end_date" name="promo_end_date" value="' . esc_attr($end_date) . '" style="width:100%;" /></p>';
        echo '<p><label for="promo_url"><strong>URL страницы акции:</strong></label><br><input type="url" id="promo_url" name="promo_url" value="' . esc_url($promo_url) . '" placeholder="https://..." style="width:100%;" /></p>';
        
        echo '<p><label for="promo_country_code"><strong>Страна:</strong></label><br><select id="promo_country_code" name="promo_country_code" style="width:100%;">';
        echo '<option value="">-- Выберите страну --</option>';
        foreach ($this->countries as $code => $data) {
            echo '<option value="' . esc_attr($code) . '" ' . selected($country_code, $code, false) . '>' . esc_html($data['name']) . '</option>';
        }
        echo '</select></p>';
        echo '<p><em>Для промокодов СНГ (кроме РФ) выберите "Страны СНГ". В тексте можно уточнить список стран.</em></p>';
    }

    public function save_meta_data($post_id) {
        if (!isset($_POST['promo_meta_box_nonce']) || !wp_verify_nonce($_POST['promo_meta_box_nonce'], 'promo_meta_box_nonce')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        $country_code = isset($_POST['promo_country_code']) ? sanitize_text_field($_POST['promo_country_code']) : '';

        update_post_meta($post_id, '_promo_start_date', sanitize_text_field($_POST['promo_start_date']));
        update_post_meta($post_id, '_promo_end_date', sanitize_text_field($_POST['promo_end_date']));
        update_post_meta($post_id, '_promo_url', esc_url_raw($_POST['promo_url']));
        update_post_meta($post_id, '_promo_country_code', $country_code);

        // Связываем пост с термином таксономии
        if (!empty($country_code) && isset($this->countries[$country_code])) {
            wp_set_object_terms($post_id, $this->countries[$country_code]['name'], 'promo_country', false);
        }
    }

    public function add_hreflang_and_schema() {
        if (!is_singular('promo_post')) return;

        global $post;
        $country_code = get_post_meta($post->ID, '_promo_country_code', true);

        if ($country_code && isset($this->countries[$country_code])) {
            $hreflang = $this->countries[$country_code]['hreflang'];
            echo '<link rel="alternate" hreflang="' . esc_attr($hreflang) . '" href="' . esc_url(get_permalink($post->ID)) . '" />' . "\n";
        }

        $start_date = get_post_meta($post->ID, '_promo_start_date', true) ?: get_the_date('Y-m-d');
        $end_date = get_post_meta($post->ID, '_promo_end_date', true);
        $promo_url = get_post_meta($post->ID, '_promo_url', true);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'mainEntity' => [
                '@type' => 'DiscountOffer',
                'description' => get_the_title(),
                'availabilityStarts' => $start_date,
                'businessFunction' => 'http://purl.org/goodrelations/v1#Sell',
            ]
        ];
        
        if ($end_date) {
            $schema['mainEntity']['availabilityEnds'] = $end_date;
        }
        if ($promo_url) {
            $schema['mainEntity']['url'] = esc_url($promo_url);
        }
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url($post->ID, 'full');
        }

        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    public function add_thumbnail_to_content($content) {
        if (is_singular('promo_post') && in_the_loop() && is_main_query() && has_post_thumbnail()) {
            return get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'promo-post-thumbnail')) . $content;
        }
        return $content;
    }

    public function shortcode_countries_block($atts) {
        $atts = shortcode_atts(['exclude' => ''], $atts);
        $exclude_codes = array_map('trim', explode(',', $atts['exclude']));
        
        ob_start();
        ?>
        <div class="alipromo-countries-block">
            <nav class="alipromo-countries-nav">
                <?php foreach ($this->countries as $code => $data): ?>
                    <?php if (!in_array($code, $exclude_codes)): 
                        $term = get_term_by('name', $data['name'], 'promo_country');
                        if ($term && !is_wp_error($term)):
                    ?>
                         <a href="#country-<?php echo esc_attr($code); ?>"><?php echo esc_html($data['native_name']); ?></a>
                    <?php endif; endif; ?>
                <?php endforeach; ?>
            </nav>

            <div class="alipromo-countries-grid">
            <?php foreach ($this->countries as $code => $data): ?>
                <?php if (in_array($code, $exclude_codes)) continue; ?>
                <?php
                $args = [
                    'post_type' => 'promo_post',
                    'posts_per_page' => 4,
                    'meta_query' => [
                        [
                            'key' => '_promo_country_code',
                            'value' => $code,
                            'compare' => '=',
                        ],
                    ],
                    'post_status' => 'publish'
                ];
                $query = new WP_Query($args);
                if ($query->have_posts()):
                ?>
                <section id="country-<?php echo esc_attr($code); ?>" class="alipromo-country-section">
                    <h2 class="alipromo-country-title"><?php echo esc_html($data['native_name']); ?></h2>
                    <div class="alipromo-posts-list">
                        <?php while ($query->have_posts()): $query->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="alipromo-post-card">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php endif; ?>
                                <h3><?php the_title(); ?></h3>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                    <?php 
                    $term = get_term_by('name', $data['name'], 'promo_country');
                    if ($term && !is_wp_error($term)) :
                    ?>
                    <div class="alipromo-more-link-wrapper">
                         <a href="<?php echo esc_url(get_term_link($term)); ?>" class="alipromo-more-link"><?php echo esc_html($data['more_text']); ?></a>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function shortcode_related_promos($atts) {
        $atts = shortcode_atts(['count' => 4, 'title' => ''], $atts);
        global $post;
        
        $country_code = get_post_meta($post->ID, '_promo_country_code', true);
        if (!$country_code) return '';
        
        $lang = $this->countries[$country_code]['lang'] ?? 'en';
        $title = !empty($atts['title']) ? $atts['title'] : $this->get_localized_text($lang, 'related_title');

        $args = [
            'post_type' => 'promo_post',
            'posts_per_page' => intval($atts['count']),
            'post__not_in' => array($post->ID),
            'meta_query' => [
                [
                    'key' => '_promo_country_code',
                    'value' => $country_code,
                    'compare' => '=',
                ],
            ],
        ];
        $query = new WP_Query($args);

        if (!$query->have_posts()) return '';

        ob_start();
        ?>
        <div class="alipromo-related-block">
            <h2 class="alipromo-related-title"><?php echo esc_html($title); ?></h2>
            <div class="alipromo-related-grid">
                <?php while ($query->have_posts()): $query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="alipromo-post-card">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function get_localized_text($lang, $key, $default = '') {
        $texts = [
            'dates' => ['ru' => 'Сроки действия:', 'en' => 'Validity:', 'es' => 'Vigencia:', 'pl' => 'Ważność:', 'it' => 'Validità:', 'fr' => 'Validité:', 'de' => 'Gültigkeit:', 'nl' => 'Geldigheid:', 'uk' => 'Термін дії:', 'pt' => 'Validade:', 'he' => 'תוקף:', 'ar' => 'فترة الصلاحية:', 'ko' => '유효기간:'],
            'from' => ['ru' => 'с', 'en' => 'from', 'es' => 'desde', 'pl' => 'od', 'it' => 'dal', 'fr' => 'du', 'de' => 'vom', 'nl' => 'van', 'uk' => 'з', 'pt' => 'de', 'he' => 'מ-', 'ar' => 'من', 'ko' => '부터'],
            'to' => ['ru' => 'по', 'en' => 'to', 'es' => 'hasta', 'pl' => 'do', 'it' => 'al', 'fr' => 'au', 'de' => 'bis', 'nl' => 'tot', 'uk' => 'по', 'pt' => 'até', 'he' => 'עד', 'ar' => 'إلى', 'ko' => '까지'],
            'button' => ['ru' => 'Перейти к акции', 'en' => 'Go to promotion', 'es' => 'Ir a la promoción', 'pl' => 'Przejdź do promocji', 'it' => 'Vai alla promozione', 'fr' => 'Aller à la promotion', 'de' => 'Zur Aktion gehen', 'nl' => 'Ga naar de promotie', 'uk' => 'Перейти до акції', 'pt' => 'Ir para a promoção', 'he' => 'עבור למבצע', 'ar' => 'اذهب إلى العرض', 'ko' => '프로모션으로 이동'],
            'show' => ['ru' => 'Показать', 'en' => 'Show', 'es' => 'Mostrar', 'pl' => 'Pokaż', 'it' => 'Mostra', 'fr' => 'Montrer', 'de' => 'Anzeigen', 'nl' => 'Tonen', 'uk' => 'Показати', 'pt' => 'Mostrar', 'he' => 'הצג', 'ar' => 'إظهار', 'ko' => '보여주다'],
            'copy' => ['ru' => 'Копировать', 'en' => 'Copy', 'es' => 'Copiar', 'pl' => 'Kopiuj', 'it' => 'Copia', 'fr' => 'Copier', 'de' => 'Kopieren', 'nl' => 'Kopiëren', 'uk' => 'Копіювати', 'pt' => 'Copiar', 'he' => 'העתק', 'ar' => 'نسخ', 'ko' => '복사'],
            'copied' => ['ru' => 'Скопировано!', 'en' => 'Copied!', 'es' => '¡Copiado!', 'pl' => 'Skopiowano!', 'it' => 'Copiato!', 'fr' => 'Copié !', 'de' => 'Kopiert!', 'nl' => 'Gekopieerd!', 'uk' => 'Скопійовано!', 'pt' => 'Copiado!', 'he' => 'הועתק!', 'ar' => 'تم النسخ!', 'ko' => '복사됨!'],
            'related_title' => ['ru' => 'Другие промокоды', 'en' => 'Other promo codes', 'es' => 'Otros códigos promocionales', 'pl' => 'Inne kody promocyjne', 'it' => 'Altri codici promozionali', 'fr' => 'Autres codes promo', 'de' => 'Weitere Promo-Codes', 'nl' => 'Andere promocodes', 'uk' => 'Інші промокоди', 'pt' => 'Outros códigos promocionais', 'he' => 'קודי פרומו אחרים', 'ar' => 'أكواد خصم أخرى', 'ko' => '기타 프로모션 코드'],
        ];

        if (isset($texts[$key][$lang])) {
            return $texts[$key][$lang];
        }
        
        if (isset($texts[$key]['en'])) {
            return $texts[$key]['en'];
        }

        return $default;
    }

    public function enqueue_assets() {
        if (is_singular('promo_post') || is_post_type_archive('promo_post') || is_tax('promo_country') || (get_post() && has_shortcode(get_post()->post_content, 'alipromo_countries'))) {
            wp_enqueue_style('alipromo-google-font-kurale', 'https://fonts.googleapis.com/css2?family=Kurale&display=swap', array(), null);
            wp_enqueue_style('alipromo-styles', plugin_dir_url(__FILE__) . 'promo-styles.css', array(), '1.1.1');
            wp_enqueue_script('alipromo-script', plugin_dir_url(__FILE__) . 'promo-main.js', array('jquery'), '1.1.1', true);
            
            global $post;
            if (is_singular('promo_post')) {
                $country_code = get_post_meta($post->ID, '_promo_country_code', true);
                if ($country_code && isset($this->countries[$country_code])) {
                    $lang = $this->countries[$country_code]['lang'];
                    wp_localize_script('alipromo-script', 'aliPromoTexts', [
                        'show' => $this->get_localized_text($lang, 'show', 'Show'),
                        'copy' => $this->get_localized_text($lang, 'copy', 'Copy'),
                        'copied' => $this->get_localized_text($lang, 'copied', 'Copied!'),
                    ]);
                }
            }
        }
    }
    
    public function load_single_template($template) {
        if (is_singular('promo_post')) {
            $plugin_template = plugin_dir_path(__FILE__) . 'single-promo_post.php';
            if (file_exists($plugin_template)) return $plugin_template;
        }
        return $template;
    }
    
    public function load_archive_template($template) {
        if (is_post_type_archive('promo_post') || is_tax('promo_country')) {
            $plugin_template = plugin_dir_path(__FILE__) . 'archive-promo_post.php';
            if (file_exists($plugin_template)) return $plugin_template;
        }
        return $template;
    }

    public function activate() { 
        $this->init();
        flush_rewrite_rules(); 
    }
    
    public function deactivate() { 
        flush_rewrite_rules(); 
    }
}

function ali_promo_plugin_instance() {
    static $instance = null;
    if ($instance === null) {
        $instance = new AliPromoPlugin();
    }
    return $instance;
}
ali_promo_plugin_instance();