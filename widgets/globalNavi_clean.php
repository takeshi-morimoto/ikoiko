<!-- グローバルナビゲーション（クリーンバージョン） -->
<nav id="globalNavi" class="global-nav-clean desktop-only">
    <div class="container">
        <ul class="global-nav-list">
            <li>
                <a href="/ikoiko/" class="global-nav-link">TOP<br><small>anime</small></a>
            </li>
            <li>
                <a href="/ikoiko/初めて.php" class="global-nav-link">初めての方<br><small>for beginner</small></a>
            </li>
            <li>
                <a href="/ikoiko/参加.php" class="global-nav-link">参加までの流れ<br><small>arrange</small></a>
            </li>
            <li>
                <a href="/ikoiko/よくある質問.php" class="global-nav-link">よくある質問<br><small>FAQ</small></a>
            </li>
            <li>
                <a href="/ikoiko/スタッフ募集.php" class="global-nav-link">スタッフ募集<br><small>recruitment</small></a>
            </li>
            <li>
                <a href="/ikoiko/contact.php" class="global-nav-link">お問い合わせ<br><small>contact</small></a>
            </li>
        </ul>
    </div>
</nav>

<style>
/* クリーンなグローバルナビゲーション */
.global-nav-clean {
    background: #FFFFFF;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    position: relative;
    color: #333; /* 明示的に色を指定 */
}

.global-nav-clean .container {
    max-width: 1400px; /* より広い最大幅 */
    width: 100%;
    margin: 0 auto;
    padding: 0 40px;
}

.global-nav-clean .global-nav-list {
    display: flex;
    justify-content: stretch;
    align-items: stretch;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0;
    width: 100%;
}

.global-nav-clean .global-nav-list li {
    flex: 1 1 0;
    display: flex;
    text-align: center;
}

.global-nav-clean .global-nav-link {
    display: block;
    width: 100%;
    padding: 18px 10px;
    text-decoration: none;
    color: #333;
    font-weight: 600;
    font-size: 14px;
    line-height: 1.4;
    transition: all 0.3s ease;
    position: relative;
    border-bottom: 3px solid transparent;
    min-height: 70px;
    box-sizing: border-box;
}

.global-nav-clean .global-nav-link:hover {
    background: rgba(255, 107, 53, 0.05);
    color: #FF6B35;
    border-bottom-color: #FF6B35;
}

.global-nav-clean .global-nav-link small {
    display: block;
    font-weight: 400;
    font-size: 11px;
    color: #999;
    margin-top: 2px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.global-nav-clean .global-nav-link:hover small {
    color: #FF6B35;
}

/* レスポンシブ */
@media (max-width: 992px) {
    .global-nav-clean {
        display: none;
    }
}
</style>