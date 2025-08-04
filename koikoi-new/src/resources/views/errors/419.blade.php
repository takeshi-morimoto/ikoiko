@extends('layouts.app')

@section('title', 'セッションの有効期限切れ | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-clock" style="font-size: 100px; color: #6C757D;"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-muted">419</h1>
                
                <h2 class="mb-4">セッションの有効期限が切れました</h2>
                
                <p class="text-muted mb-5">
                    セキュリティ保護のため、一定時間操作がない場合は<br>
                    自動的にセッションが終了します。<br>
                    お手数ですが、ページを更新してもう一度お試しください。
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-5">
                    <button onclick="window.location.reload()" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i>
                        ページを更新
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>
                        トップページへ
                    </a>
                </div>
                
                <div class="info-section bg-light p-4 rounded">
                    <h5 class="mb-3">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        このエラーを防ぐには
                    </h5>
                    <ul class="text-start text-muted">
                        <li>フォーム入力中は定期的に保存する</li>
                        <li>長時間離席する場合は作業を保存する</li>
                        <li>複数のタブで同時に作業しない</li>
                    </ul>
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
        animation: rotate 4s linear infinite;
    }
    
    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .display-1 {
        font-size: 120px;
        line-height: 1;
    }
    
    .info-section ul {
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .display-1 {
            font-size: 80px;
        }
        .error-icon i {
            font-size: 80px !important;
        }
    }
</style>
@endsection