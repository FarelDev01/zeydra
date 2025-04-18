var app;
var version;

var settings = [{
    appName: 'Zeydra',
    version: '1.5-flash',

}]

settings.forEach((config) => {
    app = config.appName;
    version = config.version;
})

function $(query) {
    return document.querySelector(query);
}

$('.header h1').innerText = app
function backPage() {
    document.querySelector('.account-container').style.display = 'none'
    location.reload()
}

document.querySelector('.back').addEventListener('click', function() {
    document.querySelector('.account-container').style.display = 'none'
})