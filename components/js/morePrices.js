document.addEventListener('DOMContentLoaded', function () {
    redirectToElement();
});

function redirectToElement() {
    var tickerI = document.getElementsByName('ticker');
    var nameI = document.getElementsByName('name');
    var ticker = tickerI[0].value;
    var name = nameI[0].value;
    if ((ticker.trim() !== "" && ticker.length>0)||(name.trim() !== "" && name.length>0)) {
        document.getElementById('prices').scrollIntoView({ behavior: 'smooth' });
    }
}