<header id="pageHeader" class="site-header">
    <div class="header-container">
        <div class="header-content">
            <!-- 左側: ロゴとナビゲーション -->
            <div class="header-left">
                <!-- ロゴ -->
                <div class="header-logo">
                    <a href="/ikoiko/" class="logo-link">
                        <img id="logo" src="/ikoiko/img/common/icon/logo.png" alt="KOIKOI アニメコン・街コン公式サイト" loading="lazy">
                    </a>
                </div>
                
                <!-- ナビゲーション（デスクトップ） -->
                <nav class="header-nav desktop-only" role="navigation" aria-label="メインナビゲーション">
                    <a href="/ikoiko/" class="nav-link">アニメコン</a>
                    <a href="/ikoiko/machi/" class="nav-link">街コン</a>
                </nav>
            </div>
            
            <!-- 右側: コンタクト情報 -->
            <div class="header-right">
                <div class="contact-info">
                    <div class="payment-info">
                        <img src="/ikoiko/img/common/icon/icon_card.png" alt="カード決済" class="icon-payment" loading="lazy">
                        <span class="desktop-only">コンビニ・カード決済OK</span>
                    </div>
                    <div class="phone-info desktop-only">
                        <img src="/ikoiko/img/common/icon/icon_tel.png" alt="電話" class="icon-phone" loading="lazy">
                        <a href="tel:03-6754-6371" class="phone-number">03-6754-6371</a>
                    </div>
                </div>
                
                <!-- モバイルメニューボタン -->
                <button class="mobile-menu-toggle mobile-only" aria-label="メニューを開く">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- モバイルナビゲーション -->
<nav class="mobile-nav" id="mobileNav" aria-label="モバイルナビゲーション">
        <div class="mobile-nav-header">
            <h2 class="mobile-nav-title">メニュー</h2>
            <button class="mobile-nav-close" aria-label="メニューを閉じる">
                <span class="close-icon">×</span>
            </button>
        </div>
        <div class="mobile-nav-content">
            <a href="/ikoiko/" class="mobile-nav-link">アニメコン</a>
            <a href="/ikoiko/machi/" class="mobile-nav-link">街コン</a>
            
            <div class="mobile-nav-divider"></div>
            
            <a href="/ikoiko/" class="mobile-nav-link">TOP</a>
            <a href="/ikoiko/初めて.php" class="mobile-nav-link">初めての方</a>
            <a href="/ikoiko/参加.php" class="mobile-nav-link">参加までの流れ</a>
            <a href="/ikoiko/よくある質問.php" class="mobile-nav-link">よくある質問</a>
            <a href="/ikoiko/スタッフ募集.php" class="mobile-nav-link">スタッフ募集</a>
            <a href="/ikoiko/contact.php" class="mobile-nav-link">お問い合わせ</a>
            
            <div class="mobile-contact-info">
                <a href="tel:03-6754-6371" class="mobile-phone">
                    <img src="/ikoiko/img/common/icon/icon_tel.png" alt="電話" class="icon-phone-mobile">
                    03-6754-6371
                </a>
            </div>
        </div>
</nav>

<script>
// ヘッダースクロールエフェクト
let lastScrollTop = 0;
const header = document.getElementById('pageHeader');
const scrollThreshold = 100;
let isScrolling;

window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // スクロール時のクラス追加
    if (scrollTop > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
    
    // ヘッダーの表示/非表示
    if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
        header.classList.add('hidden');
    } else {
        header.classList.remove('hidden');
    }
    
    lastScrollTop = scrollTop;
    
    // スクロール終了検知
    window.clearTimeout(isScrolling);
    isScrolling = setTimeout(() => {
        header.classList.remove('hidden');
    }, 500);
});

// モバイルメニューの開閉
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const mobileNav = document.getElementById('mobileNav');
const mobileNavClose = document.querySelector('.mobile-nav-close');
const body = document.body;

function openMobileMenu() {
    mobileNav.classList.add('active');
    mobileMenuToggle.classList.add('active');
    body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    mobileNav.classList.remove('active');
    mobileMenuToggle.classList.remove('active');
    body.style.overflow = '';
}

mobileMenuToggle.addEventListener('click', openMobileMenu);
mobileNavClose.addEventListener('click', closeMobileMenu);

// オーバーレイクリックで閉じる
mobileNav.addEventListener('click', (e) => {
    if (e.target === mobileNav) {
        closeMobileMenu();
    }
});

// モバイルリンククリックで閉じる
document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
});

// pageTopがあるため、ボディパディングは不要

// パフォーマンス最適化のためのデバウンス
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// スクロールイベントの最適化
window.addEventListener('scroll', debounce(() => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}, 10));
</script>

