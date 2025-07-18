<div id="pageHeader">
    <a class="mb_none" href="/ikoiko/">
        <img class="mb_none" id="logo" src="/ikoiko/img/common/icon/logo.png" alt="KOIKOI アニメコン・街コン公式サイト" loading="lazy">
    </a>

    <div id="contact">
        <div class="box_001">
            <span>コンビニ・カード決済もOK！</span>
            <img class="icon_card" src="/ikoiko/img/common/icon/icon_card.png" alt="カード決済アイコン" loading="lazy">
        </div>

        <div class="box_002 mb_none">
            <img class="icon_tel" src="/ikoiko/img/common/icon/icon_tel.png" alt="電話アイコン" loading="lazy">
            <span>03-6754-6371</span>
        </div>
    </div>

    <script>
    jQuery(function() {  
        jQuery("a").click(function(e) {        
            var ahref = jQuery(this).attr('href');
            var eventType = ahref.includes("/ikoiko/") || ahref.indexOf("http") === -1 
                            ? '内部リンク' : '外部リンク';
            ga('send', 'event', eventType, 'クリック', ahref);
        });
    });
    </script>
</div>
