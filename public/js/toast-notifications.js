/**
 * Advanced Toast Notification System
 * PLN Icon Plus - Modern Notification Library
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if not exists
        if (!document.getElementById('toast-container')) {
            this.createContainer();
        }
        this.container = document.getElementById('toast-container');
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }

    show(message, type = 'info', duration = 5000, options = {}) {
        const toast = this.createToast(message, type, options);
        this.container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            this.remove(toast);
        }, duration);

        return toast;
    }

    createToast(message, type, options) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            loading: 'fas fa-spinner fa-spin'
        };

        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8',
            loading: '#6c757d'
        };

        toast.style.cssText = `
            background: white;
            border-left: 4px solid ${colors[type]};
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            padding: 16px;
            display: flex;
            align-items: center;
            animation: slideInRight 0.3s ease-out;
            position: relative;
            min-width: 300px;
            max-width: 400px;
        `;

        toast.innerHTML = `
            <div style="margin-right: 12px; color: ${colors[type]}; font-size: 18px;">
                <i class="${icons[type]}"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #333; margin-bottom: 4px;">
                    ${options.title || this.getDefaultTitle(type)}
                </div>
                <div style="color: #666; font-size: 14px;">
                    ${message}
                </div>
                ${options.timestamp ? `<div style="font-size: 12px; color: #999; margin-top: 4px;">${new Date().toLocaleTimeString()}</div>` : ''}
            </div>
            <button onclick="this.parentElement.remove()" style="
                background: none;
                border: none;
                color: #999;
                cursor: pointer;
                font-size: 16px;
                padding: 4px;
                margin-left: 8px;
            ">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Add CSS animation
        if (!document.getElementById('toast-animations')) {
            const style = document.createElement('style');
            style.id = 'toast-animations';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                .toast-notification.removing {
                    animation: slideOutRight 0.3s ease-in forwards;
                }
            `;
            document.head.appendChild(style);
        }

        return toast;
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Berhasil!',
            error: 'Error!',
            warning: 'Peringatan!',
            info: 'Informasi',
            loading: 'Memproses...'
        };
        return titles[type] || 'Notifikasi';
    }

    remove(toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    // Convenience methods
    success(message, options = {}) {
        return this.show(message, 'success', 4000, options);
    }

    error(message, options = {}) {
        return this.show(message, 'error', 6000, options);
    }

    warning(message, options = {}) {
        return this.show(message, 'warning', 5000, options);
    }

    info(message, options = {}) {
        return this.show(message, 'info', 4000, options);
    }

    loading(message, options = {}) {
        return this.show(message, 'loading', 0, options);
    }
}

// Global instance
window.Toast = new ToastNotification();

// Global helper functions
window.showToast = {
    success: (message, options) => window.Toast.success(message, options),
    error: (message, options) => window.Toast.error(message, options),
    warning: (message, options) => window.Toast.warning(message, options),
    info: (message, options) => window.Toast.info(message, options),
    loading: (message, options) => window.Toast.loading(message, options)
};

// Notification Center Integration
class NotificationCenter {
    constructor() {
        this.notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
        this.init();
    }

    init() {
        this.updateBadge();
        this.renderNotifications();
    }

    addNotification(type, title, message, persistent = false) {
        const notification = {
            id: Date.now(),
            type: type,
            title: title,
            message: message,
            timestamp: new Date().toISOString(),
            read: false,
            persistent: persistent
        };

        this.notifications.unshift(notification);
        this.saveNotifications();
        this.updateBadge();
        this.renderNotifications();
    }

    markAsRead(id) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification) {
            notification.read = true;
            this.saveNotifications();
            this.updateBadge();
            this.renderNotifications();
        }
    }

    markAllAsRead() {
        this.notifications.forEach(n => n.read = true);
        this.saveNotifications();
        this.updateBadge();
        this.renderNotifications();
    }

    removeNotification(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
        this.saveNotifications();
        this.updateBadge();
        this.renderNotifications();
    }

    updateBadge() {
        const badge = document.getElementById('notification-badge');
        const unreadCount = this.notifications.filter(n => !n.read).length;
        
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    renderNotifications() {
        const container = document.getElementById('notification-list');
        if (!container) return;

        if (this.notifications.length === 0) {
            container.innerHTML = `
                <li class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i><br>
                    Belum ada notifikasi
                </li>
            `;
            return;
        }

        let html = '';
        this.notifications.slice(0, 10).forEach(notification => {
            const timeAgo = this.getTimeAgo(notification.timestamp);
            const readClass = notification.read ? '' : 'bg-light';
            const iconClass = this.getIconClass(notification.type);
            
            html += `
                <li class="dropdown-item ${readClass}" onclick="notificationCenter.markAsRead(${notification.id})">
                    <div class="d-flex align-items-start">
                        <div class="me-2">
                            <i class="${iconClass} text-${notification.type === 'success' ? 'success' : notification.type === 'error' ? 'danger' : notification.type === 'warning' ? 'warning' : 'info'}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${notification.title}</div>
                            <div class="small text-muted">${notification.message}</div>
                            <div class="small text-muted">${timeAgo}</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); notificationCenter.removeNotification(${notification.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </li>
            `;
        });

        container.innerHTML = html;
    }

    getIconClass(type) {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        return icons[type] || 'fas fa-info-circle';
    }

    getTimeAgo(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diffInSeconds = Math.floor((now - time) / 1000);

        if (diffInSeconds < 60) return 'Baru saja';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit lalu`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam lalu`;
        return `${Math.floor(diffInSeconds / 86400)} hari lalu`;
    }

    saveNotifications() {
        localStorage.setItem('notifications', JSON.stringify(this.notifications));
    }
}

// Global notification center instance
window.notificationCenter = new NotificationCenter();

// Enhanced Toast class with notification center integration
class EnhancedToastNotification extends ToastNotification {
    show(message, type = 'info', duration = 5000, options = {}) {
        // Show toast
        const toast = super.show(message, type, duration, options);
        
        // Add to notification center if persistent
        if (options.persistent !== false) {
            window.notificationCenter.addNotification(
                type, 
                options.title || this.getDefaultTitle(type), 
                message, 
                options.persistent || false
            );
        }
        
        return toast;
    }
}

// Replace global instance
window.Toast = new EnhancedToastNotification();

// Global helper functions
window.showToast = {
    success: (message, options) => window.Toast.success(message, options),
    error: (message, options) => window.Toast.error(message, options),
    warning: (message, options) => window.Toast.warning(message, options),
    info: (message, options) => window.Toast.info(message, options),
    loading: (message, options) => window.Toast.loading(message, options)
};

// Global functions for notification center
window.markAllAsRead = () => window.notificationCenter.markAllAsRead();

// Auto-show flash messages as toasts (ONLY for session-based messages)
document.addEventListener('DOMContentLoaded', function() {
    // Only convert session-based flash messages, not bulk operations panels
    const alerts = document.querySelectorAll('.alert-success, .alert-danger, .alert-warning, .alert-info');
    
    alerts.forEach(alert => {
        // Skip if it's inside bulk operations panel or has data-no-toast attribute
        if (alert.closest('.bulk-actions-panel') || alert.hasAttribute('data-no-toast')) {
            return;
        }
        
        // Skip if it's empty or just contains bulk operations text
        const text = alert.textContent.trim();
        if (!text || text.includes('item dipilih') || text.includes('Hapus Terpilih') || text.includes('Export CSV')) {
            return;
        }
        
        // Convert to toast based on alert type
        if (alert.classList.contains('alert-success')) {
            window.Toast.success(text, { persistent: true });
            alert.style.display = 'none';
        } else if (alert.classList.contains('alert-danger')) {
            window.Toast.error(text, { persistent: true });
            alert.style.display = 'none';
        } else if (alert.classList.contains('alert-warning')) {
            window.Toast.warning(text, { persistent: true });
            alert.style.display = 'none';
        } else if (alert.classList.contains('alert-info')) {
            window.Toast.info(text, { persistent: true });
            alert.style.display = 'none';
        }
    });
});
