@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'options' => [],
    'placeholder' => '選択してください',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'help' => '',
    'error' => '',
    'size' => 'normal'
])

@php
    $inputId = $name . '_' . uniqid();
    $sizeClass = match($size) {
        'small' => 'form-select-sm',
        'large' => 'form-select-lg',
        default => ''
    };
    
    $selectedValue = old($name, $value);
    if ($multiple && !is_array($selectedValue)) {
        $selectedValue = $selectedValue ? [$selectedValue] : [];
    }
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
    
    <select 
        id="{{ $inputId }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        class="form-select {{ $sizeClass }} {{ $error ? 'is-invalid' : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes }}
    >
        @if(!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                <optgroup label="{{ $optionValue }}">
                    @foreach($optionLabel as $groupValue => $groupLabel)
                        <option value="{{ $groupValue }}" 
                            {{ $multiple 
                                ? (in_array($groupValue, $selectedValue) ? 'selected' : '') 
                                : ($groupValue == $selectedValue ? 'selected' : '') 
                            }}>
                            {{ $groupLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optionValue }}" 
                    {{ $multiple 
                        ? (in_array($optionValue, $selectedValue) ? 'selected' : '') 
                        : ($optionValue == $selectedValue ? 'selected' : '') 
                    }}>
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>
    
    @if($error)
        <div class="invalid-feedback">
            {{ $error }}
        </div>
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>