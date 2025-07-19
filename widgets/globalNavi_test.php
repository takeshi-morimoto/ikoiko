<!-- グローバルナビゲーション（全幅テスト版） -->
<style>
/* topContainerの幅制限を一時的に解除 */
.global-nav-wrapper {
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    background: #FFFFFF;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.global-nav-inner {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 40px;
}

.global-nav-list-test {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0;
    width: 100%;
}

.global-nav-list-test li {
    flex: 1;
    display: flex;
}

.global-nav-link-test {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 20px 15px;
    text-decoration: none;
    color: #333;
    font-weight: 600;
    font-size: 14px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    border-bottom: 3px solid transparent;
    min-height: 70px;
    white-space: nowrap;
}

.global-nav-link-test:hover {
    background: rgba(255, 107, 53, 0.05);
    color: #FF6B35;
    border-bottom-color: #FF6B35;
}

.global-nav-link-test small {
    display: block;
    font-weight: 400;
    font-size: 11px;
    color: #999;
    margin-top: 2px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.global-nav-link-test:hover small {
    color: #FF6B35;
}

@media (max-width: 992px) {
    .global-nav-wrapper {
        display: none;
    }
}
</style>

<div class="global-nav-wrapper">
    <nav id="globalNavi" class="global-nav-inner desktop-only">
        <ul class="global-nav-list-test">
            <li>
                <a href="/ikoiko/" class="global-nav-link-test">
                    <div>
                        TOP<br><small>anime</small>
                    </div>
                </a>
            </li>
            <li>
                <a href="/ikoiko/初めて.php" class="global-nav-link-test">
                    <div>
                        初めての方<br><small>for beginner</small>
                    </div>
                </a>
            </li>
            <li>
                <a href="/ikoiko/参加.php" class="global-nav-link-test">
                    <div>
                        参加までの流れ<br><small>arrange</small>
                    </div>
                </a>
            </li>
            <li>
                <a href="/ikoiko/よくある質問.php" class="global-nav-link-test">
                    <div>
                        よくある質問<br><small>FAQ</small>
                    </div>
                </a>
            </li>
            <li>
                <a href="/ikoiko/スタッフ募集.php" class="global-nav-link-test">
                    <div>
                        スタッフ募集<br><small>recruitment</small>
                    </div>
                </a>
            </li>
            <li>
                <a href="/ikoiko/contact.php" class="global-nav-link-test">
                    <div>
                        お問い合わせ<br><small>contact</small>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</div>