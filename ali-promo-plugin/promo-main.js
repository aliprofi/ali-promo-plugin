jQuery(document).ready(function($) {
    
    // Функция для открытия ссылки в новой вкладке
    function GoTo(url) {
        if (url) {
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    }

    // Обработка промокодов
    $('strong[data-promo]').each(function() {
        var $promoStrong = $(this);
        var promoCode = $promoStrong.data('promo').trim();
        
        if (!promoCode) return;

        var visiblePart = promoCode.substring(0, 3);
        var hiddenPart = '***';
        var maskedCode = visiblePart + hiddenPart;

        // Получаем URL для редиректа со страницы
        var promoUrl = $('.alipromo-cta-button').attr('href');
        
        // Создаем новую структуру
        var $wrapper = $('<div class="alipromo-code-wrapper"></div>');
        var $text = $('<div class="alipromo-code-text"></div>').html(maskedCode);
        var $button = $('<button class="alipromo-code-button"></button>').text(aliPromoTexts.show || 'Show');

        $wrapper.append($text).append($button);
        $promoStrong.replaceWith($wrapper);

        // Клик по кнопке "ПОКАЗАТЬ"
        $button.on('click', function() {
            var $currentButton = $(this);

            // Перенаправляем пользователя
            GoTo(promoUrl);

            // Показываем полный код
            $text.text(promoCode);

            // Меняем кнопку на "КОПИРОВАТЬ"
            $currentButton.text(aliPromoTexts.copy || 'Copy');
            
            // Отвязываем старый обработчик и вешаем новый
            $currentButton.off('click').on('click', function() {
                navigator.clipboard.writeText(promoCode).then(function() {
                    // Успешно скопировано
                    var originalText = $currentButton.text();
                    $currentButton.text(aliPromoTexts.copied || 'Copied!');
                    
                    setTimeout(function() {
                        $currentButton.text(originalText);
                    }, 2000);

                }).catch(function(err) {
                    console.error('Failed to copy text: ', err);
                });
            });
        });
    });
});