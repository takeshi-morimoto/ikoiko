<picture>
    @if($webpSrc)
    {{-- WebP版がある場合は優先的に使用 --}}
    <source 
        type="image/webp" 
        srcset="{{ $webpSrc }}"
        @if(!empty($srcset))
        data-srcset="{{ implode(', ', array_map(fn($s) => str_replace(pathinfo($s, PATHINFO_EXTENSION), 'webp', $s), $srcset)) }}"
        @endif
    >
    @endif
    
    {{-- 通常の画像フォーマット --}}
    @if(!empty($srcset))
    <source 
        srcset="{{ implode(', ', $srcset) }}"
        sizes="{{ implode(', ', $sizes) }}"
    >
    @endif
    
    {{-- フォールバック用のimg要素 --}}
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}"
        class="{{ $class }}"
        loading="{{ $loading }}"
        @if(!empty($srcset))
        srcset="{{ implode(', ', $srcset) }}"
        sizes="{{ implode(', ', $sizes) }}"
        @endif
    >
</picture>