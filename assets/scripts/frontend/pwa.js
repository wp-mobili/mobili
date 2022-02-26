const divInstall = document.getElementById('mi-pwa-installer');
const butInstall = document.getElementById('mi-pwa-install-btn');

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    window.deferredPrompt = event;
    divInstall.classList.toggle('hidden', false);
});

window.addEventListener('appinstalled', (event) => {
    window.deferredPrompt = null;
});

butInstall.addEventListener('click', async () => {
    const promptEvent = window.deferredPrompt;
    if (!promptEvent) {
        return;
    }

    promptEvent.prompt();
    const result = await promptEvent.userChoice;
    window.deferredPrompt = null;
    divInstall.classList.toggle('hidden', true);
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(mobiliArgs.serviceWorker);
}
