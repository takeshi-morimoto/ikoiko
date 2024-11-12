<a class="mb_none">
    <picture>
        <source srcset="//koikoi.co.jp/ikoiko/img/common/logo.webp" type="image/webp">
        <img class="mb_none" id="logo" src="//koikoi.co.jp/ikoiko/img/common/logo.png" alt="ロゴ">
    </picture>
</a>

<div id="contact">
    <div class="box_001">
        <span>コンビニ・カード決済もOK！</span>
        <picture>
            <source srcset="//koikoi.co.jp/ikoiko/img/common/icons/icon_card.webp" type="image/webp">
            <img class="icon_card" src="//koikoi.co.jp/ikoiko/img/common/icons/icon_card.png" alt="カード決済アイコン">
        </picture>
    </div>

    <div class="box_002 mb_none">
        <picture>
            <source srcset="//koikoi.co.jp/ikoiko/img/common/icons/icon_tel.webp" type="image/webp">
            <img class="icon_tel" src="//koikoi.co.jp/ikoiko/img/common/icons/icon_tel.png" alt="電話アイコン">
        </picture>
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
