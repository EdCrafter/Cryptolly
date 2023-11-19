
document.addEventListener('DOMContentLoaded', function () {
    // Функция будет выполнена после полной загрузки DOM
    focusInputOnContainerClick();
    updateContent();
});



function focusInputOnContainerClick() {
    // Найти все элементы .input-container и добавить обработчик клика
    var containers = document.querySelectorAll('.input-container');
    containers.forEach(function (container) {
        container.addEventListener('click', function () {
            focusInput(container);
        });
    });
}

function focusInput(container) {
    // Найти элемент input внутри контейнера и установить фокус
    var inputElement = container.querySelector('input[type="email"]');
    if (inputElement) {
        inputElement.focus();
    }
}

window.addEventListener('resize', updateContent);

function updateContent() {
    var container = document.getElementById('assets-container');
    var windowWidth = window.innerWidth;
    if (windowWidth <= 600) {
        // Отображаем содержимое для узких экранов
        container.querySelector('.wide-content').style.display = 'none';
        container.querySelector('.narrow-content').style.display = 'block';
    } else if(windowWidth >1000) {
        // Отображаем содержимое для широких экранов
        container.querySelector('.wide-content').style.display = 'block';
        container.querySelector('.narrow-content').style.display = 'none';
    }
    else{
    }
}
