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

<style>
/* サイドバーのモダンスタイル（街コン用） */
.sidebar-modern {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg, 24px);
}

.sidebar-card {
    background: var(--bg-primary, #ffffff);
    border-radius: var(--border-radius-lg, 12px);
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.1));
    overflow: hidden;
    transition: all 0.3s ease;
}

.sidebar-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md, 0 4px 6px rgba(0,0,0,0.1));
}

.sidebar-header {
    padding: var(--spacing-md, 16px);
    background: var(--bg-secondary, #f8f9fa);
    border-bottom: 1px solid var(--border-color, #dee2e6);
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.125rem;
    color: var(--text-primary, #2d3436);
}

.sidebar-content {
    padding: var(--spacing-md, 16px);
}

/* リンクカード */
.sidebar-link-card {
    display: block;
    position: relative;
    overflow: hidden;
}

.sidebar-card-content {
    position: relative;
}

.sidebar-image {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.3s ease;
}

.sidebar-link-card:hover .sidebar-image {
    transform: scale(1.05);
}

.sidebar-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    padding: var(--spacing-lg, 24px) var(--spacing-md, 16px) var(--spacing-md, 16px);
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.sidebar-link-card:hover .sidebar-overlay {
    transform: translateY(0);
}

.sidebar-cta {
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

/* イベントアイテム */
.event-item {
    margin-bottom: var(--spacing-md, 16px);
    padding-bottom: var(--spacing-md, 16px);
    border-bottom: 1px solid var(--border-color, #dee2e6);
}

.event-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.event-item a {
    text-decoration: none;
    color: inherit;
    display: block;
}

.event-image {
    width: 100%;
    height: auto;
    border-radius: var(--border-radius-md, 8px);
    margin-bottom: var(--spacing-sm, 8px);
}

.event-item h4 {
    margin: var(--spacing-sm, 8px) 0;
    font-size: 1rem;
    color: var(--text-primary, #2d3436);
}

.event-description {
    font-size: 0.875rem;
    color: var(--text-secondary, #636e72);
    margin-bottom: var(--spacing-sm, 8px);
}

/* メルマガフォーム */
.newsletter-form {
    margin: 0;
}

.form-description {
    margin-bottom: var(--spacing-md, 16px);
    font-size: 0.875rem;
    color: var(--text-secondary, #636e72);
}

.form-group {
    margin-bottom: var(--spacing-sm, 8px);
}

.form-control {
    width: 100%;
    padding: var(--spacing-sm, 8px) var(--spacing-md, 16px);
    border: 1px solid var(--border-color, #dee2e6);
    border-radius: var(--border-radius-md, 8px);
    font-size: 1rem;
}

/* SNSボタン */
.social-button {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm, 8px);
    padding: var(--spacing-sm, 8px) var(--spacing-lg, 24px);
    background: #000;
    color: white;
    text-decoration: none;
    border-radius: 24px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.social-button:hover {
    transform: scale(1.05);
    background: #1a1a1a;
}

.social-button svg {
    width: 20px;
    height: 20px;
}

/* ボタンスタイル */
.btn {
    display: inline-block;
    padding: var(--spacing-sm, 8px) var(--spacing-lg, 24px);
    font-size: 1rem;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    border: 2px solid transparent;
    border-radius: var(--border-radius-md, 8px);
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary {
    background-color: var(--primary-color, #FF6B35);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-hover, #E85A2C);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md, 0 4px 6px rgba(0,0,0,0.1));
}

.btn-sm {
    padding: 4px 12px;
    font-size: 0.875rem;
}

.w-100 {
    width: 100%;
}

.text-center {
    text-align: center;
}

/* 街コンページ用の追加調整 */
@media (min-width: 1024px) {
    #sideContent {
        width: 360px;
    }
    
    .sidebar-modern {
        padding: 0 var(--spacing-sm, 8px);
    }
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .sidebar-modern {
        margin-top: var(--spacing-xl, 32px);
    }
    
    .sidebar-card {
        margin: 0;
    }
}
</style>