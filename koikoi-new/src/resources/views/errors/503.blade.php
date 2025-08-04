@extends('layouts.app')

@section('title', 'メンテナンス中 | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-tools" style="font-size: 100px; color: #17A2B8;"></i>
                </div>
                
                <h1 class="display-3 fw-bold text-info mb-4">メンテナンス中</h1>
                
                <div class="maintenance-message bg-light p-4 rounded mb-5">
                    <h3 class="mb-3">現在、システムメンテナンスを実施中です</h3>
                    
                    <p class="text-muted mb-4">
                        お客様により良いサービスをご提供するため、<br>
                        システムのメンテナンスを行っております。<br>
                        ご不便をおかけして申し訳ございません。
                    </p>
                    
                    @if(env('MAINTENANCE_END_TIME'))
                    <div class="maintenance-time alert alert-info">
                        <i class="fas fa-clock me-2"></i>
                        <strong>メンテナンス終了予定時刻:</strong><br>
                        {{ env('MAINTENANCE_END_TIME') }}
                    </div>
                    @endif
                </div>
                
                <div class="maintenance-info row text-start">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-wrench text-primary me-2"></i>
                                    メンテナンス内容
                                </h5>
                                <ul class="text-muted small mb-0">
                                    <li>システムの安定性向上</li>
                                    <li>新機能の追加</li>
                                    <li>セキュリティアップデート</li>
                                    <li>パフォーマンスの改善</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    お問い合わせ
                                </h5>
                                <p class="text-muted small mb-2">
                                    緊急のお問い合わせは以下までご連絡ください：
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>
                                    <a href="mailto:support@koikoi.co.jp">support@koikoi.co.jp</a>
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2"></i>
                                    03-1234-5678
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="social-links mt-5">
                    <p class="text-muted mb-3">最新情報はこちらでご確認ください</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-page {
        padding: 40px 0;
    }
    
    .error-icon {
        animation: rotate 3s linear infinite;
    }
    
    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .display-3 {
        font-size: 72px;
    }
    
    .maintenance-message {
        border-left: 4px solid #17A2B8;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .display-3 {
            font-size: 48px;
        }
        .error-icon i {
            font-size: 80px !important;
        }
    }
</style>

<script>
    // 定期的にページをリロードしてメンテナンス終了を確認
    setTimeout(function() {
        window.location.reload();
    }, 60000); // 1分ごとにリロード
</script>
@endsection