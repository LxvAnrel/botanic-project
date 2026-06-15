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

    async function getRegistration() {
        return navigator.serviceWorker.register('/sw.js');
    }

    // Verifica se o usuário já está inscrito neste dispositivo
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

    // Solicita permissão, inscreve e envia ao backend. Retorna true/false.
    async function subscribe() {
        if (!pushSupported()) {
            alert('Seu navegador não suporta notificações push.');
            return false;
        }

        const vapidPublic = meta('vapid-public-key');
        if (!vapidPublic) {
            console.error('VAPID public key ausente.');
            return false;
        }

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return false;

        const reg = await getRegistration();
        await navigator.serviceWorker.ready;

        let sub = await reg.pushManager.getSubscription();
        if (!sub) {
            sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublic),
            });
        }

        const key = sub.getKey('p256dh');
        const token = sub.getKey('auth');

        const res = await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': meta('csrf-token'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                endpoint: sub.endpoint,
                public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                content_encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
            }),
        });

        return res.ok;
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
            return false;
        }
    }

    window.Flora.push = { supported: pushSupported, isSubscribed, subscribe, unsubscribe };
})();
