document.addEventListener('DOMContentLoaded', function () {
    // Функция будет выполнена после полной загрузки DOM
    focusInputOnContainerClick();
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
