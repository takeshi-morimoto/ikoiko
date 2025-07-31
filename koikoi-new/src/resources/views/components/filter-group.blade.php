@props(['label', 'name', 'options' => [], 'selected' => null, 'placeholder' => 'すべて'])

<div class="filter-group">
    <label>{{ $label }}</label>
    <select name="{{ $name }}" class="filter-select" onchange="this.form.submit()">
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>