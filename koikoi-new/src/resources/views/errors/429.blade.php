@extends('layouts.app')

@section('title', 'アクセス制限中 | KOIKOI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="fas fa-hand-paper" style="font-size: 100px; color: #DC3545;"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-muted">429</h1>
                
                <h2 class="mb-4">アクセスが多すぎます</h2>
                
                <p class="text-muted mb-5">
                    短時間に多くのリクエストが送信されたため、<br>
                    一時的にアクセスを制限しています。<br>
                    しばらくお待ちいただいてから、もう一度お試しください。
                </p>
                
                <div class="countdown-section mb-5">
                    <p class="text-muted mb-3">次回アクセス可能まで</p>
                    <div class="countdown display-4 text-primary" id="countdown">60</div>
                    <p class="text-muted">秒</p>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-5">
                    <button id="retry-button" class="btn btn-primary" disabled>
                        <i class="fas fa-redo me-2"></i>
                        <span id="retry-text">60秒後に再試行</span>
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>
                        トップページへ
                    </a>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>ご注意:</strong> 過度なアクセスは他のユーザーの利用を妨げる可能性があります。<br>
                    通常の利用方法でお楽しみください。
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
        animation: pulse 1s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    
    .display-1 {
        font-size: 120px;
        line-height: 1;
    }
    
    .countdown {
        font-weight: bold;
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

<script>
    // カウントダウンタイマー
    let seconds = 60;
    const countdownElement = document.getElementById('countdown');
    const retryButton = document.getElementById('retry-button');
    const retryText = document.getElementById('retry-text');
    
    const timer = setInterval(function() {
        seconds--;
        countdownElement.textContent = seconds;
        retryText.textContent = seconds + '秒後に再試行';
        
        if (seconds <= 0) {
            clearInterval(timer);
            countdownElement.textContent = '0';
            retryButton.disabled = false;
            retryButton.classList.remove('btn-primary');
            retryButton.classList.add('btn-success');
            retryText.textContent = '再試行する';
            
            retryButton.onclick = function() {
                window.location.reload();
            };
        }
    }, 1000);
</script>
@endsection