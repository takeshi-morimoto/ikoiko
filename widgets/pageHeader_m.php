<a class="mb_none" href="//koikoi.co.jp/ikoiko/"><img class="mb_none" id="logo" src="//koikoi.co.jp/ikoiko/img/common/icon/logo.png" alt="ロゴ" /></a>

<div id="contact">
    <div class="box_001">
        <span>コンビニ・カード決済もOK！</span>
        <img class="icon_card" src="//koikoi.co.jp/ikoiko/img/common/icon/icon_card.png" alt="カード決済アイコン" />
    </div>

    <div class="box_002 mb_none">
        <img class="icon_tel" src="//koikoi.co.jp/ikoiko/img/common/icon/icon_tel.png" alt="電話アイコン" />
        <span>03-6754-6371</span>
    </div>
</div>

<script type="text/javascript">
jQuery(function() {  
    jQuery("a").click(function(e) {        
        var ahref = jQuery(this).attr('href');
        if (ahref.indexOf("//koikoi.co.jp/ikoiko/") != -1 || ahref.indexOf("http") == -1) {
            ga('send', 'event', '内部リンク', 'クリック', ahref);
        } else { 
            ga('send', 'event', '外部リンク', 'クリック', ahref);
        }
    });
});
</script>
