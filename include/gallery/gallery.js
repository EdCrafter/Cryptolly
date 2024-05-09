
(function ($) {
   
    $.fn.imageGallery = function () {
        // Создание кнопок перехода
        var $prevBtn = $('<button class="gallery-prev-btn"><</button>');
        var $nextBtn = $('<button class="gallery-next-btn">></button>');

        // Обработчик клика по изображению
        this.on('click', function () {
            var $img = $(this);
            var imgSrc = $img.attr('src');

            // Устанавливаем активным изображение
            $('.gallery-img').removeClass('gallery-active');
            $img.addClass('gallery-active');

            // Создаем модальное окно для просмотра изображения
            var $modal = $('<div class="image-gallery-modal">').appendTo('body');
            var $modalImg = $('<img>').attr('src', imgSrc).appendTo($modal);
            $modalImg.css('scale','1.7');
            $modal.append($prevBtn);
            $modal.append($nextBtn);
            // Закрытие модального окна при клике вне изображения
            $modal.on('click', function () {
                $modal.remove();
            });

            // Запрещаем закрытие модального окна при клике на само изображение
            $modalImg.on('click', function (event) {
                event.stopPropagation();
            });

            $prevBtn.on('click', function (event) {
                event.stopPropagation();
                var $currentImg = $('.gallery-active');
                var $prevImg = $currentImg.prev();
                if ($prevImg.length === 0) {
                    $prevImg = $currentImg.siblings().last();
                }
                $currentImg.removeClass('gallery-active');
                $prevImg.addClass('gallery-active');
                var imgSrc = $prevImg.attr('src');
                $('.image-gallery-modal > img').remove();
                $('<img>').attr('src', imgSrc).appendTo('.image-gallery-modal').css('scale','1.7');
            });
    
            // Обработчик клика по кнопке Next
            $nextBtn.on('click', function (event) {
                event.stopPropagation();
                var $currentImg = $('.gallery-active');
                var $nextImg = $currentImg.next();
                if ($nextImg.length === 0) {
                    $nextImg = $currentImg.siblings().first();
                }
                $currentImg.removeClass('gallery-active');
                $nextImg.addClass('gallery-active');
                var imgSrc = $nextImg.attr('src');
                $('.image-gallery-modal > img').remove();
                $('<img>').attr('src', imgSrc).appendTo('.image-gallery-modal').css('scale','1.7');
            });
        });

        return this; // Для поддержки цепочек вызовов jQuery
    };
})(jQuery);
