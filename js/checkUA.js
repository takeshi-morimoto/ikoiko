// User Agent チェック関数
function checkUA() {
    var ua = navigator.userAgent.toLowerCase();
    
    // モバイル端末の判定
    var isMobile = /iphone|ipod|android|windows phone|blackberry/.test(ua);
    
    // タブレットの判定
    var isTablet = /ipad|android(?!.*mobile)/.test(ua);
    
    // PCの判定
    var isPC = !isMobile && !isTablet;
    
    return {
        isMobile: isMobile,
        isTablet: isTablet,
        isPC: isPC,
        ua: ua
    };
}

// グローバル変数として定義
window.checkUA = checkUA;