@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => '',
    'error' => '',
    'icon' => '',
    'size' => 'normal'
])

@php
    $inputId = $name . '_' . uniqid();
    $sizeClass = match($size) {
        'small' => 'form-control-sm',
        'large' => 'form-control-lg',
        default => ''
    };
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    <div class="{{ $icon ? 'input-group' : '' }}">
        @if($icon)
            <span class="input-group-text">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        
        <input 
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="form-control {{ $sizeClass }} {{ $error ? 'is-invalid' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes }}
        >
        
        @if($error)
            <div class="invalid-feedback">
                {{ $error }}
            </div>
        @endif
    </div>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>