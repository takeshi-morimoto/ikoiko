@extends('layouts.app')

@section('title', 'アクセスが拒否されました | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-lock" style="font-size: 100px; color: #FFA500;"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-muted">403</h1>
                
                <h2 class="mb-4">アクセスが拒否されました</h2>
                
                <p class="text-muted mb-5">
                    申し訳ございません。このページへのアクセス権限がありません。<br>
                    ログインが必要な場合は、ログインしてからお試しください。
                </p>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-5">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        トップページへ
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        ログイン
                    </a>
                </div>
                
                <div class="help-section">
                    <p class="text-muted">
                        <i class="fas fa-question-circle me-2"></i>
                        アクセス権限についてご不明な点がございましたら、<br>
                        <a href="{{ route('contact') }}">お問い合わせ</a>ください。
                    </p>
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
        animation: shake 0.5s ease-in-out infinite;
    }
    
    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        75% {
            transform: translateX(5px);
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