// PLN Kantor Management - PWA Installation & Service Worker Management
// Version 1.0.0

class PLNKantorPWA {
    constructor() {
        this.deferredPrompt = null;
        this.isStandalone = false;
        this.registration = null;

        this.init();
    }

    async init() {
        // Check if running in standalone mode
        this.checkStandalone();

        // Unregister any existing service workers
        await this.unregisterServiceWorkers();

        // Service worker disabled - no offline support needed
        // await this.registerServiceWorker();

        // PWA installation disabled - no install prompt needed
        // this.setupInstallPrompt();

        // Update notification disabled - no service worker updates

        // Offline detection disabled - no offline support
        // this.setupOfflineDetection();

        // Push notifications disabled - no service worker
        // this.setupPushNotifications();

        console.log('🚀 PLN Kantor PWA initialized (service worker disabled)');
    }

    async unregisterServiceWorkers() {
        if ('serviceWorker' in navigator) {
            try {
                const registrations = await navigator.serviceWorker.getRegistrations();
                for (let registration of registrations) {
                    await registration.unregister();
                    console.log('🗑️ PWA: Service worker unregistered');
                }
            } catch (error) {
                console.error('❌ PWA: Failed to unregister service workers:', error);
            }
        }
    }

    checkStandalone() {
        this.isStandalone = window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone ||
            document.referrer.includes('android-app://');

        if (this.isStandalone) {
            document.body.classList.add('pwa-standalone');
            console.log('📱 PWA: Running in standalone mode');
        }
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                this.registration = await navigator.serviceWorker.register('/sw.js');
                console.log('✅ PWA: Service Worker registered successfully');

                // Listen for service worker updates
                this.registration.addEventListener('updatefound', () => {
                    const newWorker = this.registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateNotification();
                        }
                    });
                });

            } catch (error) {
                console.error('❌ PWA: Service Worker registration failed:', error);
            }
        }
    }

    setupInstallPrompt() {
        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('📲 PWA: Install prompt available');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        // Listen for app installed event
        window.addEventListener('appinstalled', () => {
            console.log('🎉 PWA: App installed successfully');
            this.hideInstallButton();
            this.showInstallSuccess();
        });
    }

    showInstallButton() {
        // Create install button if not exists
        if (!document.getElementById('pwa-install-btn')) {
            const installBtn = document.createElement('button');
            installBtn.id = 'pwa-install-btn';
            installBtn.className = 'btn btn-primary position-fixed';
            installBtn.style.cssText = `
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                border-radius: 50px;
                padding: 12px 20px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                background: linear-gradient(45deg, #3b82f6, #1d4ed8);
                border: none;
            `;
            installBtn.innerHTML = `
                <i class="fas fa-download me-2"></i>
                Install App
            `;

            installBtn.addEventListener('click', () => {
                this.installApp();
            });

            // Add hover effect
            installBtn.addEventListener('mouseenter', () => {
                installBtn.style.transform = 'translateY(-2px)';
                installBtn.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.2)';
            });

            installBtn.addEventListener('mouseleave', () => {
                installBtn.style.transform = 'translateY(0)';
                installBtn.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
            });

            document.body.appendChild(installBtn);

            // Animate in
            setTimeout(() => {
                installBtn.style.opacity = '1';
                installBtn.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    hideInstallButton() {
        const installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            installBtn.style.opacity = '0';
            installBtn.style.transform = 'translateY(20px)';
            setTimeout(() => {
                installBtn.remove();
            }, 300);
        }
    }

    async installApp() {
        if (!this.deferredPrompt) {
            console.warn('⚠️ PWA: Install prompt not available');
            return;
        }

        try {
            // Show install prompt
            this.deferredPrompt.prompt();

            // Wait for user response
            const result = await this.deferredPrompt.userChoice;
            console.log('👤 PWA: User response:', result.outcome);

            if (result.outcome === 'accepted') {
                console.log('✅ PWA: User accepted install');
            } else {
                console.log('❌ PWA: User dismissed install');
            }

            // Clear the prompt
            this.deferredPrompt = null;
            this.hideInstallButton();

        } catch (error) {
            console.error('❌ PWA: Install failed:', error);
        }
    }

    showInstallSuccess() {
        // Show success toast
        this.showToast('🎉 Aplikasi berhasil diinstall!', 'success');
    }

    showUpdateNotification() {
        // Create update notification
        const updateBanner = document.createElement('div');
        updateBanner.id = 'pwa-update-banner';
        updateBanner.className = 'alert alert-info position-fixed w-auto';
        updateBanner.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 1001;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            max-width: 350px;
            border: none;
            background: linear-gradient(45deg, #06b6d4, #0891b2);
            color: white;
            border-left: 4px solid #0891b2;
        `;

        updateBanner.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-sync-alt me-3"></i>
                <div class="flex-grow-1">
                    <strong>Update Tersedia!</strong>
                    <div class="small">Versi baru aplikasi telah tersedia</div>
                </div>
                <button class="btn btn-sm btn-light ms-2" onclick="plnPWA.updateApp()">
                    Update
                </button>
                <button class="btn-close btn-close-white ms-2" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        document.body.appendChild(updateBanner);

        // Auto remove after 10 seconds
        setTimeout(() => {
            if (document.getElementById('pwa-update-banner')) {
                updateBanner.remove();
            }
        }, 10000);
    }

    updateApp() {
        if (this.registration && this.registration.waiting) {
            // Tell service worker to skip waiting
            this.registration.waiting.postMessage({ type: 'SKIP_WAITING' });

            // Reload page after service worker takes control
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                window.location.reload();
            });

            // Remove update banner
            const banner = document.getElementById('pwa-update-banner');
            if (banner) banner.remove();

            this.showToast('🔄 Mengupdate aplikasi...', 'info');
        }
    }

    setupOfflineDetection() {
        // Handle online/offline events
        window.addEventListener('online', () => {
            console.log('🌐 PWA: Back online');
            this.showToast('🌐 Koneksi kembali normal', 'success');
            this.syncOfflineData();
        });

        window.addEventListener('offline', () => {
            console.log('📵 PWA: Gone offline');
            this.showToast('📵 Mode offline - Data akan disinkronkan nanti', 'warning');
        });

        // Check initial connection status
        if (!navigator.onLine) {
            this.showOfflineBanner();
        }
    }

    showOfflineBanner() {
        const banner = document.createElement('div');
        banner.id = 'offline-banner';
        banner.className = 'alert alert-warning position-fixed w-100';
        banner.style.cssText = `
            top: 0;
            left: 0;
            right: 0;
            z-index: 1002;
            margin: 0;
            border-radius: 0;
            text-align: center;
            border: none;
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: white;
        `;

        banner.innerHTML = `
            <i class="fas fa-wifi me-2"></i>
            Mode Offline - Beberapa fitur mungkin terbatas
        `;

        document.body.appendChild(banner);
        document.body.style.paddingTop = '60px';

        // Remove when back online
        window.addEventListener('online', () => {
            banner.remove();
            document.body.style.paddingTop = '0';
        }, { once: true });
    }

    async syncOfflineData() {
        try {
            // Trigger background sync if supported
            if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
                const registration = await navigator.serviceWorker.ready;
                await registration.sync.register('background-sync-offline-actions');
                console.log('🔄 PWA: Background sync registered');
            }
        } catch (error) {
            console.error('❌ PWA: Background sync failed:', error);
        }
    }

    async setupPushNotifications() {
        if (!('Notification' in window) || !('serviceWorker' in navigator)) {
            console.warn('⚠️ PWA: Push notifications not supported');
            return;
        }

        // Check permission status
        let permission = Notification.permission;

        if (permission === 'default') {
            // Show permission request after user interaction
            this.showNotificationPermissionPrompt();
        } else if (permission === 'granted') {
            console.log('✅ PWA: Notification permission granted');
            await this.subscribeForPush();
        }
    }

    showNotificationPermissionPrompt() {
        // Add notification permission button to header if not exists
        setTimeout(() => {
            const header = document.querySelector('.modern-header');
            if (header && !document.getElementById('notification-permission-btn')) {
                const notifBtn = document.createElement('button');
                notifBtn.id = 'notification-permission-btn';
                notifBtn.className = 'btn btn-outline-primary btn-sm';
                notifBtn.innerHTML = '<i class=\"fas fa-bell me-2\"></i>Izinkan Notifikasi';

                notifBtn.addEventListener('click', async () => {
                    await this.requestNotificationPermission();
                });

                header.appendChild(notifBtn);
            }
        }, 2000);
    }

    async requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                console.log('✅ PWA: Notification permission granted');
                this.showToast('🔔 Notifikasi diaktifkan!', 'success');
                await this.subscribeForPush();

                // Remove permission button
                const btn = document.getElementById('notification-permission-btn');
                if (btn) btn.remove();

            } else {
                console.log('❌ PWA: Notification permission denied');
                this.showToast('🔕 Notifikasi ditolak', 'warning');
            }
        } catch (error) {
            console.error('❌ PWA: Notification permission error:', error);
        }
    }

    async subscribeForPush() {
        try {
            if (!this.registration) return;

            const subscription = await this.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlB64ToUint8Array(window.vapidPublicKey || '')
            });

            console.log('✅ PWA: Push subscription created');

            // Send subscription to server
            await this.sendSubscriptionToServer(subscription);

        } catch (error) {
            console.error('❌ PWA: Push subscription failed:', error);
        }
    }

    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/push-subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                },
                body: JSON.stringify({
                    subscription: subscription,
                    user_id: window.currentUserId || null
                })
            });

            if (response.ok) {
                console.log('✅ PWA: Subscription sent to server');
            }
        } catch (error) {
            console.error('❌ PWA: Failed to send subscription:', error);
        }
    }

    urlB64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    }

    showToast(message, type = 'info') {
        // Create toast container if not exists
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed';
            container.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 1050;
            `;
            document.body.appendChild(container);
        }

        // Create toast
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show`;
        toast.style.cssText = `
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            max-width: 350px;
            animation: slideInRight 0.3s ease;
        `;

        toast.innerHTML = `
            ${message}
            <button type=\"button\" class=\"btn-close\" onclick=\"this.parentElement.remove()\"></button>
        `;

        container.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }, 5000);
    }

    // Cache management
    async clearCache() {
        if ('caches' in window) {
            const cacheNames = await caches.keys();
            await Promise.all(cacheNames.map(name => caches.delete(name)));
            console.log('🧹 PWA: Cache cleared');
            this.showToast('🧹 Cache berhasil dibersihkan', 'success');
        }
    }

    // Get cache size
    async getCacheSize() {
        if ('caches' in window && 'storage' in navigator && 'estimate' in navigator.storage) {
            const estimate = await navigator.storage.estimate();
            const cacheSize = estimate.usage || 0;
            const quota = estimate.quota || 0;

            return {
                used: this.formatBytes(cacheSize),
                available: this.formatBytes(quota),
                percentage: Math.round((cacheSize / quota) * 100)
            };
        }
        return null;
    }

    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .pwa-standalone .navbar-brand {
        padding-left: env(safe-area-inset-left);
    }
    
    .pwa-standalone .navbar-nav {
        padding-right: env(safe-area-inset-right);
    }
`;
document.head.appendChild(style);

// Initialize PWA when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.plnPWA = new PLNKantorPWA();
});

// Export for global access
window.PLNKantorPWA = PLNKantorPWA;