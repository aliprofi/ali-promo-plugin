# Ali Promo WordPress Plugin

**Автор:** Ali Profi  
**Сайт:** [alipromocode.com](https://alipromocode.com/)

Плагин для создания и управления промокодами на сайтах WordPress. Идеально подходит для купонных и партнерских сайтов, позволяя удобно группировать акции по странам и выводить их с помощью шорткодов.

-----

  - [Русская версия](https://www.google.com/search?q=%23%D1%80%D1%83%D1%81%D1%81%D0%BA%D0%B0%D1%8F-%D0%B2%D0%B5%D1%80%D1%81%D0%B8%D1%8F)
  - [English Version](https://www.google.com/search?q=%23english-version)

-----

## Русская версия

### 🚀 Основные возможности

  * **Тип записей "Промокоды"**: Специальный раздел в админ-панели для удобного добавления и управления страницами с акциями.
  * **Таксономия "Страны"**: Группировка промокодов по странам для удобной навигации и фильтрации.
  * **Дополнительные поля**: Возможность указать дату начала и окончания акции, а также целевую URL-ссылку.
  * **SEO-оптимизация**: Автоматическая генерация `hreflang` тегов на основе выбранной страны и микроразметки `Schema.org (DiscountOffer)` для лучшей индексации поисковыми системами.
  * **Интерактивные промокоды**: Промокоды в тексте скрыты под маской (`PRO***`). При первом клике на кнопку "Показать" пользователь переходит по целевой ссылке, а код открывается. При втором клике код копируется в буфер обмена.
  * **Гибкие шорткоды**:
      * `[alipromo_countries]` — выводит на странице блоки с промокодами, сгруппированными по странам.
      * `[alipromo_related]` — выводит похожие промокоды для той же страны (используется на страницах отдельных акций).
  * **Собственные шаблоны**: Плагин поставляется с готовыми шаблонами для архивов стран и одиночных страниц промокодов, что обеспечивает единообразный и привлекательный вид "из коробки".
  * **Готовые стили**: Приятный дизайн, адаптированный для большинства тем, с использованием собственного шрифта.

### ⚙️ Установка

1.  Скачайте ZIP-архив плагина.
2.  В админ-панели WordPress перейдите в `Плагины` \> `Добавить новый`.
3.  Нажмите `Загрузить плагин` и выберите скачанный ZIP-архив.
4.  Активируйте плагин после установки.

### 🛠️ Как использовать

#### 1\. Создание промокода

1.  В админ-панели перейдите в раздел `Промокоды` \> `Добавить промокод`.
2.  Заполните заголовок (например, "Скидка 25% на первую покупку") и основное описание акции.
3.  В блоке **"Параметры промокодов"** справа укажите:
      * **Дату начала и окончания** акции.
      * **URL страницы акции** (целевая ссылка, куда будет перенаправлен пользователь).
      * **Страну**, для которой предназначен промокод.
4.  Задайте `Изображение записи` — оно будет использоваться как баннер акции.
5.  Нажмите `Опубликовать`.

#### 2\. Вставка интерактивного промокода в текст

Чтобы вставить в текст записи промокод, который будет скрыт и станет интерактивным, используйте тег `<strong>` со специальным атрибутом `data-promo`.

**Пример:**

```html
Ваш промокод на скидку: <strong data-promo="SALE2025">SALE2025</strong>
```

Плагин автоматически найдет этот элемент и превратит его в интерактивный блок "Показать/Копировать".

#### 3\. Использование шорткодов

  * **Вывод всех стран на одной странице:**
    Создайте новую страницу (например, "Все промокоды") и вставьте в нее шорткод `[alipromo_countries]`. Плагин отобразит навигацию по странам и сетку с последними 4 промокодами для каждой страны.

    *Вы можете исключить определенные страны из этого блока:*

    ```shortcode
    [alipromo_countries exclude="ru, cis"]
    ```

  * **Вывод похожих промокодов:**
    Шорткод `[alipromo_related]` уже встроен в шаблон одиночной страницы промокода и выводит 4 похожих предложения из той же страны. При необходимости вы можете использовать его в тексте записи, изменив количество выводимых постов:

    ```shortcode
    [alipromo_related count="3"]
    ```

\#\#\#🎨 Кастомизация

  * **Стили**: Стили плагина находятся в файле `promo-styles.css`. Вы можете переопределить их в `style.css` вашей темы, используя более специфичные CSS-селекторы.
  * **Шаблоны**: Чтобы изменить верстку страниц, скопируйте файлы `single-promo_post.php` и `archive-promo_post.php` из папки плагина в папку вашей дочерней темы. WordPress будет использовать файлы из вашей темы, и вы сможете безопасно их редактировать.

-----

## English Version

### 🚀 Key Features

  * **"Promo Codes" Custom Post Type**: A dedicated section in the admin panel for easy management of promotional pages.
  * **"Countries" Taxonomy**: Groups promo codes by country for convenient navigation and filtering.
  * **Custom Meta Fields**: Allows specifying a start date, end date, and a target URL for each promotion.
  * **SEO-Optimized**: Automatically generates `hreflang` tags based on the selected country and `Schema.org (DiscountOffer)` structured data for better search engine indexing.
  * **Interactive Promo Codes**: Promo codes within the content are masked (e.g., `PRO***`). On the first click of the "Show" button, the user is redirected to the target URL, and the full code is revealed. On the second click, the code is copied to the clipboard.
  * **Flexible Shortcodes**:
      * `[alipromo_countries]` — Displays blocks of promo codes on a page, grouped by country.
      * `[alipromo_related]` — Displays similar promo codes for the same country (intended for single promo pages).
  * **Custom Templates**: The plugin includes ready-to-use templates for country archives and single promo pages, ensuring a consistent and attractive look out of the box.
  * **Built-in Styles**: A clean design adapted for most themes, using its own font.

### ⚙️ Installation

1.  Download the plugin's ZIP archive.
2.  In your WordPress admin panel, go to `Plugins` \> `Add New`.
3.  Click `Upload Plugin` and select the downloaded ZIP file.
4.  Activate the plugin after installation.

### 🛠️ How to Use

#### 1\. Creating a Promo Code

1.  In the admin panel, navigate to `Promo Codes` \> `Add Promo Code`.
2.  Fill in the title (e.g., "25% Off Your First Purchase") and the main description of the promotion.
3.  In the **"Promo Code Parameters"** meta box on the right, specify:
      * The **Start and End Date** of the promotion.
      * The **Promotion Page URL** (the target link where the user will be redirected).
      * The **Country** for which the promo code is valid.
4.  Set a `Featured Image` — it will be used as the promotion's banner.
5.  Click `Publish`.

#### 2\. Inserting an Interactive Promo Code into Content

To insert a promo code into your post content that will be interactive, use the `<strong>` tag with a special `data-promo` attribute.

**Example:**

```html
Your discount code is: <strong data-promo="SALE2025">SALE2025</strong>
```

The plugin will automatically find this element and convert it into an interactive "Show/Copy" block.

#### 3\. Using Shortcodes

  * **Displaying All Countries on One Page:**
    Create a new page (e.g., "All Promo Codes") and insert the `[alipromo_countries]` shortcode. The plugin will display country navigation and a grid with the last 4 promos for each country.

    *You can exclude specific countries from this block:*

    ```shortcode
    [alipromo_countries exclude="us, ca"]
    ```

  * **Displaying Related Promos:**
    The `[alipromo_related]` shortcode is already built into the single promo page template and displays 4 similar offers from the same country. If needed, you can use it within the post content and change the number of posts to display:

    ```shortcode
    [alipromo_related count="3"]
    ```

### 🎨 Customization

  * **Styles**: The plugin's styles are located in `promo-styles.css`. You can override them in your theme's `style.css` by using more specific CSS selectors.
  * **Templates**: To modify the layout, copy the `single-promo_post.php` and `archive-promo_post.php` files from the plugin's folder into your child theme's folder. WordPress will then use the templates from your theme, allowing you to edit them safely.
