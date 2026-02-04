function initSmartCaptcha(siteKey, callback) {
    const container = document.querySelector('.smart-captcha');
    if (!container) return console.error("Контейнер SmartCaptcha не найден.");

    container.setAttribute('data-sitekey', siteKey);

    const iframe = document.createElement('iframe');
    iframe.src = `https://smartcaptcha.yandex.com/?sitekey=${siteKey}&lang=ru`;
    iframe.width = '300';
    iframe.height = '100';
    iframe.style.border = 'none';
    container.appendChild(iframe);

    window.addEventListener('message', function(event) {
        if (!event.data) return;
        if (event.data.type === 'smart-captcha-token') {
            callback(event.data.token);
        }
    });
}
