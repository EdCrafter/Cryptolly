document.addEventListener('DOMContentLoaded', function () {
    redirectToElement();
});

function redirectToElement() {
    var tickerI = document.getElementsByName('ticker');
    var nameI = document.getElementsByName('name');
    var priceFrom = document.getElementsByName('priceFrom')[0].value;
    var priceTo = document.getElementsByName('priceTo')[0].value;
    var changeFrom = document.getElementsByName('changeFrom')[0].value;
    var changeTo = document.getElementsByName('changeTo')[0].value;
    var changes_per = document.getElementsByName('changes_per')[0].value;
    var limit = document.getElementsByName('limit')[0].value;
    var ticker = tickerI[0].value;
    var name = nameI[0].value;
    if (
        (ticker.trim() !== "" && ticker.length > 0) ||
        (name.trim() !== "" && name.length > 0) ||
        (priceFrom.trim() !== "" && priceFrom.length > 0) ||
        (priceTo.trim() !== "" && priceTo.length > 0) ||
        (changeFrom.trim() !== "" && changeFrom.length > 0) ||
        (changeTo.trim() !== "" && changeTo.length > 0) ||
        (changes_per.trim() !== "" && changes_per.length > 0) ||
        (limit.trim() !== "" && limit.length > 0)
    ) {
        document.getElementById('prices').scrollIntoView({ behavior: 'smooth' });
    }
}