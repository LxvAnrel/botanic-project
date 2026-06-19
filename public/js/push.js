// Flora — inscrição em Web Push (vanilla)
window.Flora = window.Flora || {};

(function () {
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw = window.atob(base64);
        const output = new Uint8Array(raw.length);
        for (let i = 0; i < raw.length; ++i) output[i] = raw.charCodeAt(i);
        return output;
    }

    function meta(name) {
        const el = document.querySelector(`meta[name="${name}"]`);
        return el ? el.content : '';
    }

    function pushSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    }

    // Detecta se é mobile ou desktop para mostrar na UI
    function deviceType() {
        const ua = navigator.userAgent;
        if (/Android|iPhone|iPad|iPod|Mobile/i.test(ua)) return 'mobile';
        return 'desktop';
    }

    function deviceLabel() {
        return deviceType() === 'mobile' ? '📱 Celular' : '💻 PC';
    }

    async function getRegistration() {
        return navigator.serviceWorker.register('/sw.js');
    }

    async function isSubscribed() {
        if (!pushSupported() || Notification.permission !== 'granted') return false;
        try {
            const reg = await navigator.serviceWorker.getRegistration();
            if (!reg) return false;
            const sub = await reg.pushManager.getSubscription();
            return !!sub;
        } catch (e) {
            return false;
        }
    }

    // Retorna { ok: bool, error: string|null }
    async function subscribe() {
        if (!pushSupported()) {
            return { ok: false, error: 'Navegador não suporta notificações push.' };
        }

        const vapidPublic = meta('vapid-public-key');
        if (!vapidPublic) {
            return { ok: false, error: 'Servidor sem chave VAPID configurada.' };
        }

        const permission = await Notification.requestPermission();
        if (permission === 'denied') {
            return { ok: false, error: 'denied' };
        }
        if (permission !== 'granted') {
            return { ok: false, error: null };
        }

        try {
            const reg = await getRegistration();
            await navigator.serviceWorker.ready;

            let sub = await reg.pushManager.getSubscription();
            if (!sub) {
                sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublic),
                });
            }

            const key   = sub.getKey('p256dh');
            const token = sub.getKey('auth');

            const res = await fetch('/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': meta('csrf-token'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    endpoint:         sub.endpoint,
                    public_key:       key   ? btoa(String.fromCharCode.apply(null, new Uint8Array(key)))   : null,
                    auth_token:       token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                    content_encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
                }),
            });

            if (!res.ok) {
                const body = await res.text();
                return { ok: false, error: 'Erro do servidor: ' + res.status };
            }

            return { ok: true, error: null };
        } catch (e) {
            console.error('[Flora Push]', e);
            return { ok: false, error: e.message || 'Erro desconhecido.' };
        }
    }

    async function unsubscribe() {
        try {
            const reg = await navigator.serviceWorker.getRegistration();
            if (!reg) return true;
            const sub = await reg.pushManager.getSubscription();
            if (sub) {
                await fetch('/push/unsubscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': meta('csrf-token'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ endpoint: sub.endpoint }),
                });
                await sub.unsubscribe();
            }
            return true;
        } catch (e) {
            console.error('[Flora Push unsubscribe]', e);
            return false;
        }
    }

    window.Flora.push = { supported: pushSupported, isSubscribed, subscribe, unsubscribe, deviceLabel };
})();
