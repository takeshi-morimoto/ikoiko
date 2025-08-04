@extends('layouts.app')

@section('title', 'サーバーエラー | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-exclamation-triangle" style="font-size: 100px; color: #FF6B6B;"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-muted">500</h1>
                
                <h2 class="mb-4">サーバーエラーが発生しました</h2>
                
                <p class="text-muted mb-5">
                    申し訳ございません。サーバーで問題が発生しました。<br>
                    しばらく時間をおいてから、もう一度お試しください。<br>
                    問題が解決しない場合は、お問い合わせください。
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-5">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        トップページへ戻る
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-danger">
                        <i class="fas fa-envelope me-2"></i>
                        お問い合わせ
                    </a>
                </div>
                
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    エラーが続く場合は、以下の情報をお問い合わせ時にお知らせください：
                    <div class="mt-2 small text-start">
                        <strong>エラーコード:</strong> 500<br>
                        <strong>発生時刻:</strong> {{ now()->format('Y年m月d日 H:i') }}<br>
                        @if(app()->bound('sentry') && app('sentry')->getLastEventId())
                            <strong>イベントID:</strong> {{ app('sentry')->getLastEventId() }}
                        @endif
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
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
    
    .display-1 {
        font-size: 120px;
        line-height: 1;
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