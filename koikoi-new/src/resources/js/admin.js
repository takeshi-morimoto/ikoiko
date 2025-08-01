/**
 * KOIKOI 管理画面 JavaScript
 * 共通の管理画面機能とユーティリティ
 */

// グローバル設定
const AdminConfig = {
    // Chart.js設定
    chartColors: {
        primary: '#3498db',
        success: '#27ae60',
        warning: '#f39c12',
        danger: '#e74c3c',
        info: '#17a2b8'
    },
    
    // アニメーション設定
    animations: {
        duration: 300,
        easing: 'ease'
    },
    
    // API エンドポイント
    api: {
        monthlyRevenue: '/admin/api/monthly-revenue',
        updateStats: '/admin/api/update-stats'
    }
};

// DOM読み込み完了時の初期化
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    setupEventHandlers();
});

/**
 * ダッシュボードの初期化
 */
function initializeDashboard() {
    if (document.getElementById('revenueChart')) {
        initializeRevenueChart();
    }
    
    // アラートの自動非表示
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
}

/**
 * 売上推移チャートの初期化
 */
function initializeRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // 実際のデータを取得する場合は fetch API を使用
    const chartData = {
        labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        datasets: [{
            label: '売上 (万円)',
            data: [120, 190, 300, 500, 200, 300, 450, 600, 400, 350, 200, 100],
            borderColor: AdminConfig.chartColors.primary,
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };
    
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f8f9fa'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    return revenueChart;
}

/**
 * イベントハンドラーのセットアップ
 */
function setupEventHandlers() {
    // サイドバーの切り替え（モバイル）
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    }
    
    // データエクスポートボタン
    const exportButton = document.querySelector('[onclick="exportData()"]');
    if (exportButton) {
        exportButton.removeAttribute('onclick');
        exportButton.addEventListener('click', exportData);
    }
    
    // 統計データ更新ボタン
    const updateStatsButton = document.getElementById('updateStatsBtn');
    if (updateStatsButton) {
        updateStatsButton.addEventListener('click', updateStats);
    }
}

/**
 * データエクスポート機能
 */
function exportData() {
    showNotification('データエクスポート機能は準備中です', 'info');
}

/**
 * 統計データの更新
 */
async function updateStats() {
    try {
        showLoading('統計データを更新中...');
        
        const response = await fetch(AdminConfig.api.updateStats, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });
        
        if (!response.ok) {
            throw new Error('統計データの更新に失敗しました');
        }
        
        const result = await response.json();
        hideLoading();
        showNotification(result.message || '統計データを更新しました', 'success');
        
        // ページをリロードして最新データを表示
        setTimeout(() => {
            location.reload();
        }, 1000);
        
    } catch (error) {
        hideLoading();
        showNotification(error.message || 'エラーが発生しました', 'error');
        console.error('Stats update error:', error);
    }
}

/**
 * 月別売上データの取得
 */
async function fetchMonthlyRevenue(year = new Date().getFullYear()) {
    try {
        const response = await fetch(`${AdminConfig.api.monthlyRevenue}?year=${year}`, {
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });
        
        if (!response.ok) {
            throw new Error('売上データの取得に失敗しました');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Revenue data fetch error:', error);
        return Array(12).fill(0); // フォールバック用の空データ
    }
}

/**
 * CSRFトークンの取得
 */
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

/**
 * 通知の表示
 */
function showNotification(message, type = 'info') {
    const alertTypes = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const alertClass = alertTypes[type] || alertTypes.info;
    const iconClass = icons[type] || icons.info;
    
    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // 既存の通知を削除
    const existingAlerts = document.querySelectorAll('.alert[style*="position: fixed"]');
    existingAlerts.forEach(alert => alert.remove());
    
    // 新しい通知を追加
    document.body.insertAdjacentHTML('beforeend', alertHTML);
    
    // 5秒後に自動削除
    setTimeout(() => {
        const alert = document.querySelector('.alert[style*="position: fixed"]');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

/**
 * ローディング表示
 */
function showLoading(message = '処理中...') {
    const loadingHTML = `
        <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
            <div class="card text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>${message}</div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', loadingHTML);
}

/**
 * ローディング非表示
 */
function hideLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

/**
 * フォームの確認ダイアログ
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * データテーブルの初期化（将来的な拡張用）
 */
function initializeDataTable(selector, options = {}) {
    const defaultOptions = {
        responsive: true,
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/ja.json'
        }
    };
    
    const finalOptions = Object.assign(defaultOptions, options);
    
    // DataTablesが利用可能な場合のみ初期化
    if (typeof $.fn.DataTable !== 'undefined') {
        return $(selector).DataTable(finalOptions);
    }
    
    return null;
}

/**
 * 日付フォーマット関数
 */
function formatDate(date, format = 'YYYY-MM-DD') {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    
    return format
        .replace('YYYY', year)
        .replace('MM', month)
        .replace('DD', day)
        .replace('HH', hours)
        .replace('mm', minutes);
}

/**
 * 数値のフォーマット（カンマ区切り）
 */
function formatNumber(num) {
    return new Intl.NumberFormat('ja-JP').format(num);
}

// CSRFトークンの設定
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.Laravel = {
        csrfToken: token.getAttribute('content')
    };
}