<header id="pageHeader" class="site-header">
    <div class="container">
        <div class="header-content">
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
            
            <!-- コンタクト情報 -->
            <div class="header-contact">
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
            <div class="mobile-contact-info">
                <a href="tel:03-6754-6371" class="mobile-phone">
                    <img src="/ikoiko/img/common/icon/icon_tel.png" alt="電話" class="icon-phone-mobile">
                    03-6754-6371
                </a>
            </div>
        </div>
    </nav>
</header>

<style>
/* ヘッダースタイル */
.site-header {
    background-color: var(--bg-primary, #ffffff);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.site-header .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    width: 100%;
}

/* ロゴ */
.header-logo {
    flex-shrink: 0;
}

.header-logo .logo-link {
    display: block;
    height: 50px;
}

.header-logo #logo {
    height: 100%;
    width: auto;
    transition: height 0.3s ease;
}

/* ナビゲーション */
.header-nav {
    display: flex;
    gap: 24px;
    align-items: center;
    margin: 0 auto; /* 中央配置 */
}

.nav-link {
    color: #2D3436;
    font-weight: 500;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.2s ease;
    position: relative;
}

.nav-link:hover {
    background-color: #F8F9FA;
    color: #FF6B35;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: #FF6B35;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 80%;
}

/* コンタクト情報 */
.header-contact {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
}

.contact-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.payment-info,
.phone-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.payment-info {
    padding: 6px 12px;
    background-color: #F8F9FA;
    border-radius: 20px;
    font-size: 14px;
}

.icon-payment,
.icon-phone {
    height: 20px;
    width: auto;
}

.phone-number {
    color: #FF6B35;
    font-weight: 600;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.2s ease;
}

.phone-number:hover {
    color: #E85A2C;
}

/* モバイルメニューボタン */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    position: relative;
    width: 40px;
    height: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.hamburger-line {
    display: block;
    width: 24px;
    height: 2px;
    background-color: #2D3436;
    transition: all 0.3s ease;
    position: relative;
}

.hamburger-line:nth-child(1) {
    margin-bottom: 6px;
}

.hamburger-line:nth-child(2) {
    margin-bottom: 6px;
}

.hamburger-line:nth-child(3) {
    /* 最後の線はマージン不要 */
}

.mobile-menu-toggle.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
    margin-bottom: 0;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(2) {
    opacity: 0;
    margin-bottom: 0;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* モバイルナビゲーション */
.mobile-nav {
    position: fixed;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100vh;
    background-color: #ffffff;
    transition: left 0.3s ease;
    z-index: 9999;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.mobile-nav.active {
    left: 0;
}

.mobile-nav-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 2px solid #E9ECEF;
    background-color: #F8F9FA;
}

.mobile-nav-title {
    font-size: 18px;
    font-weight: 600;
    color: #2D3436;
    margin: 0;
}

.mobile-nav-close {
    background: none;
    border: none;
    font-size: 32px;
    line-height: 1;
    color: #636E72;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.mobile-nav-close:hover {
    background-color: #E9ECEF;
    color: #2D3436;
}

.close-icon {
    font-weight: 300;
}

.mobile-nav-content {
    padding: 24px;
    overflow-y: auto;
    height: calc(100vh - 80px);
}

.mobile-nav-link {
    display: block;
    padding: 16px 20px;
    color: #2D3436;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    border-bottom: 1px solid #E9ECEF;
    transition: all 0.2s ease;
}

.mobile-nav-link:hover {
    background-color: #F8F9FA;
    color: #FF6B35;
    padding-left: 28px;
}

.mobile-contact-info {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 2px solid #E9ECEF;
}

.mobile-phone {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #FF6B35;
    text-decoration: none;
    font-size: 20px;
    font-weight: 600;
}

.icon-phone-mobile {
    height: 24px;
}

/* スクロール時のヘッダー縮小 */
.site-header.scrolled {
    padding: 0;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.site-header.scrolled .header-content {
    padding: 8px 0;
}

.site-header.scrolled #logo {
    height: 40px;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .desktop-only {
        display: none !important;
    }
    
    .mobile-only {
        display: block !important;
    }
    
    .mobile-menu-toggle {
        display: flex; /* blockからflexに変更 */
    }
    
    .header-content {
        padding: 8px 0;
    }
    
    .header-logo #logo {
        height: 40px;
    }
    
    .payment-info {
        padding: 4px 8px;
    }
    
    .payment-info span {
        display: none;
    }
}

@media (min-width: 769px) {
    .mobile-only {
        display: none !important;
    }
    
    .mobile-nav {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // スクロール時のヘッダー縮小
    const header = document.querySelector('.site-header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // モバイルメニューのトグル
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.getElementById('mobileNav');
    const mobileNavClose = document.querySelector('.mobile-nav-close');
    
    // メニューを開く
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.add('active');
            mobileNav.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // メニューを閉じる関数
    function closeMobileMenu() {
        if (mobileMenuToggle) mobileMenuToggle.classList.remove('active');
        if (mobileNav) mobileNav.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // 閉じるボタンでメニューを閉じる
    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', closeMobileMenu);
    }
    
    // モバイルメニューのリンククリックで閉じる
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
    
    // メニュー外をクリックしたら閉じる
    mobileNav.addEventListener('click', function(e) {
        if (e.target === this) {
            closeMobileMenu();
        }
    });
    
    // Google Analytics（既存のコード）
    if (typeof jQuery !== 'undefined' && typeof ga !== 'undefined') {
        jQuery("a").click(function(e) {        
            var ahref = jQuery(this).attr('href');
            var eventType = ahref.includes("/ikoiko/") || ahref.indexOf("http") === -1 
                            ? '内部リンク' : '外部リンク';
            ga('send', 'event', eventType, 'クリック', ahref);
        });
    }
});
</script>