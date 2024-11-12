<div id="pageHeader">
    <a class="mb_none" href="//koikoi.co.jp/ikoiko/">
        <img class="mb_none" id="logo" src="//koikoi.co.jp/ikoiko/img/common/icon/logo.png" alt="ロゴ">
    </a>

    <div id="contact">
        <div class="box_001">
            <span>コンビニ・カード決済もOK！</span>
            <img class="icon_card" src="//koikoi.co.jp/ikoiko/img/common/icon/icon_card.png" alt="カード決済アイコン">
        </div>

        <div class="box_002 mb_none">
            <img class="icon_tel" src="//koikoi.co.jp/ikoiko/img/common/icon/icon_tel.png" alt="電話アイコン">
            <span>03-6754-6371</span>
        </div>
    </div>

    <script>
    jQuery(function() {  
        jQuery("a").click(function(e) {        
            var ahref = jQuery(this).attr('href');
            var eventType = ahref.includes("//koikoi.co.jp/ikoiko/") || ahref.indexOf("http") === -1 
                            ? '内部リンク' : '外部リンク';
            ga('send', 'event', eventType, 'クリック', ahref);
        });
    });
    </script>
</div>
