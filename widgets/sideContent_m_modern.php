<!-- モダンなサイドバー（街コン用） -->
<aside class="sidebar-modern">
    <!-- アニメコンを探す -->
    <div class="sidebar-card">
        <a href="/ikoiko/" class="sidebar-link-card">
            <div class="sidebar-card-content">
                <img src="/ikoiko/img/sidebar/160124animesearch_300x100.jpg" alt="アニメコンを探す" class="sidebar-image" loading="lazy">
                <div class="sidebar-overlay">
                    <span class="sidebar-cta">アニメコンはこちら →</span>
                </div>
            </div>
        </a>
    </div>

    <!-- おススメイベント -->
    <div class="sidebar-card">
        <div class="sidebar-header">
            <h3>おすすめイベント</h3>
        </div>
        <div class="sidebar-content">
            <div class="event-item">
                <a href="https://koikoi.co.jp/ikoiko/event_m/hiroshima_hiroshima_hiru_lovefes">
                    <img src="https://koikoi.co.jp/ikoiko/img/event_img/contents/lovefes/lovefes80.jpg" 
                         alt="LOVE FES HIROSHIMA" class="event-image" loading="lazy">
                    <h4>LOVE FES HIROSHIMA</h4>
                    <p class="event-description">月に1度の大規模開催！</p>
                    <span class="btn btn-primary btn-sm">詳細を見る</span>
                </a>
            </div>
            
            <div class="event-item">
                <a href="https://koikoi.co.jp/ikoiko/event_m/miyagi_sendai_hiru_lovefes">
                    <img src="https://koikoi.co.jp/ikoiko/img/event_img/contents/lovefes/lovefes100.jpg" 
                         alt="LOVE FES SENDAI" class="event-image" loading="lazy">
                    <h4>LOVE FES SENDAI</h4>
                    <p class="event-description">街コンジャパンとの共同開催</p>
                    <span class="btn btn-primary btn-sm">詳細を見る</span>
                </a>
            </div>
        </div>
    </div>

    <!-- メルマガ登録 -->
    <div class="sidebar-card">
        <div class="sidebar-header">
            <h3>メルマガ登録</h3>
        </div>
        <div class="sidebar-content">
            <form method="post" action="https://d.bmb.jp/bm/p/f/tf.php?id=machikonkoikoi&task=regist" class="newsletter-form">
                <p class="form-description">最新イベント情報をお届け！</p>
                <div class="form-group">
                    <input type="email" name="form[mail]" placeholder="メールアドレス" class="form-control" required>
                </div>
                <button type="submit" name="regist" class="btn btn-primary w-100">登録する</button>
            </form>
        </div>
    </div>

    <!-- SNSフォロー -->
    <div class="sidebar-card">
        <div class="sidebar-header">
            <h3>公式SNS</h3>
        </div>
        <div class="sidebar-content text-center">
            <a href="https://twitter.com/machikonkoikoi" target="_blank" rel="noopener noreferrer" 
               class="social-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                <span>@machikonkoikoi</span>
            </a>
        </div>
    </div>

    <!-- スタッフ募集 -->
    <div class="sidebar-card">
        <a href="/ikoiko/スタッフ募集_m.php" class="sidebar-link-card">
            <img src="/ikoiko/img/sidebar/151216parttimejob_300x100.jpg" 
                 alt="アルバイトスタッフ募集" class="sidebar-image" loading="lazy">
            <div class="sidebar-overlay">
                <span class="sidebar-cta">スタッフ募集中 →</span>
            </div>
        </a>
    </div>

    <!-- 幸せ報告 -->
    <div class="sidebar-card">
        <a href="/ikoiko/contact.php" class="sidebar-link-card">
            <img src="/ikoiko/img/sidebar/170312happymail_600x600.jpg" 
                 alt="幸せ報告募集中" class="sidebar-image" loading="lazy">
            <div class="sidebar-overlay">
                <span class="sidebar-cta">幸せ報告はこちら →</span>
            </div>
        </a>
    </div>
</aside>

<!-- スタイルは sideContent_modern.php と共通 -->

<style>
/* 街コンページ用のサイドバー追加調整 */
@media (min-width: 1024px) {
    #sideContent {
        width: 340px; /* サイドバーの幅を少し広げる */
    }
    
    .sidebar-modern {
        padding: 0 var(--spacing-sm);
    }
}

/* サイドバー内の画像サイズ調整 */
.sidebar-modern .event-item img {
    max-width: 100%;
    height: auto;
}

.sidebar-modern .sidebar-card {
    margin-bottom: var(--spacing-md);
}
</style>