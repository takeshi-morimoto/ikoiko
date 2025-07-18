jQuery(function($) {
    // DOM要素が存在しない場合は処理を中断
    if ($('.areaSearch_mb').length === 0) {
        return;
    }
    
    // 地域タブの切り替え機能
    $('.areaSearch_mb .region_1 li, .areaSearch_mb .region_2 li').click(function() {
        // 全てのタブから選択状態を解除
        $('.areaSearch_mb .region_1 li, .areaSearch_mb .region_2 li').removeClass('current_u current_l');
        
        // クリックされたタブに選択状態を追加
        $(this).addClass('current_u');
        
        // タブのインデックスを取得
        var tabIndex = $(this).index();
        var isRegion1 = $(this).closest('ul').hasClass('region_1');
        
        // region_2の場合はインデックスを調整
        if (!isRegion1) {
            tabIndex += 4;
        }
        
        // 全ての都道府県リストを非表示
        $('.areaSearch_mb .area ul').hide();
        
        // 対応する都道府県リストを表示
        $('.areaSearch_mb .area ul').eq(tabIndex).show();
        
        // 下線の位置を調整（current_lクラス）
        if (isRegion1) {
            $('.areaSearch_mb .region_1 li').removeClass('current_l');
            $(this).addClass('current_l');
        } else {
            $('.areaSearch_mb .region_2 li').removeClass('current_l');
            $(this).addClass('current_l');
        }
    });
    
    // 初期状態：最初のタブを選択
    $('.areaSearch_mb .region_1 li:first').trigger('click');
    
    // レスポンシブ対応：画面サイズに応じて表示を切り替え
    function checkScreenSize() {
        if ($(window).width() <= 768) {
            // モバイル表示
            $('.areaSearch_mb').show();
            $('.areaSearch').hide();
        } else {
            // PC表示
            $('.areaSearch_mb').hide();
            $('.areaSearch').show();
        }
    }
    
    // 初期チェック
    checkScreenSize();
    
    // ウィンドウリサイズ時にチェック
    $(window).resize(function() {
        checkScreenSize();
    });
});