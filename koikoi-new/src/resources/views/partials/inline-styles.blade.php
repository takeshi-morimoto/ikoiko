{{-- インラインスタイル定義 --}}
<style>
    /* Bladeで動的に生成される必要があるスタイルのみ */
    
    /* ナビゲーションの表示制御 */
    .navbar-collapse {
        display: flex !important;
        flex-basis: auto;
        flex-grow: 1;
        align-items: center;
    }
    
    @media (max-width: 991px) {
        .navbar-collapse {
            display: none !important;
        }
        .navbar-collapse.show {
            display: flex !important;
            flex-direction: column;
            width: 100%;
        }
    }
</style>