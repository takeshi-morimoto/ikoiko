@props([
    'headers' => [],
    'data' => [],
    'actions' => false,
    'striped' => true,
    'hover' => true,
    'responsive' => true,
    'sortable' => false,
    'searchable' => false,
    'pagination' => null,
    'emptyMessage' => 'データがありません'
])

@php
    $tableClasses = ['table'];
    if ($striped) $tableClasses[] = 'table-striped';
    if ($hover) $tableClasses[] = 'table-hover';
    
    $tableClass = implode(' ', $tableClasses);
@endphp

<div class="{{ $responsive ? 'table-responsive' : '' }}">
    @if($searchable)
        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="検索..." id="tableSearch">
            </div>
        </div>
    @endif
    
    <table class="{{ $tableClass }}" {{ $sortable ? 'data-sortable="true"' : '' }}>
        @if($headers)
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th {{ isset($header['sortable']) && $header['sortable'] && $sortable ? 'data-sortable="true"' : '' }}
                            {{ isset($header['width']) ? 'style="width: ' . $header['width'] . '"' : '' }}>
                            {{ is_string($header) ? $header : $header['title'] }}
                            @if(isset($header['sortable']) && $header['sortable'] && $sortable)
                                <i class="fas fa-sort ms-1"></i>
                            @endif
                        </th>
                    @endforeach
                    @if($actions)
                        <th width="120">アクション</th>
                    @endif
                </tr>
            </thead>
        @endif
        
        <tbody>
            @if($data && count($data) > 0)
                @foreach($data as $row)
                    <tr>
                        @foreach($headers as $key => $header)
                            @php
                                $field = is_string($header) ? strtolower($header) : ($header['field'] ?? $key);
                                $value = is_array($row) ? ($row[$field] ?? '') : ($row->$field ?? '');
                                
                                if (isset($header['formatter']) && is_callable($header['formatter'])) {
                                    $value = $header['formatter']($value, $row);
                                }
                            @endphp
                            <td>
                                @if(isset($header['html']) && $header['html'])
                                    {!! $value !!}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                        
                        @if($actions)
                            <td>
                                @if(is_callable($actions))
                                    {!! $actions($row) !!}
                                @else
                                    {{ $actions }}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i><br>
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if($pagination)
    <div class="d-flex justify-content-center">
        {{ $pagination }}
    </div>
@endif

@if($searchable || $sortable)
    @push('scripts')
    <script>
        @if($searchable)
        document.getElementById('tableSearch')?.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
        @endif
        
        @if($sortable)
        // Simple table sorting functionality
        document.querySelectorAll('th[data-sortable="true"]').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const column = Array.from(this.parentNode.children).indexOf(this);
                
                const isAscending = this.classList.contains('asc');
                
                // Remove existing sort classes
                this.parentNode.querySelectorAll('th').forEach(th => {
                    th.classList.remove('asc', 'desc');
                    th.querySelector('i').className = 'fas fa-sort ms-1';
                });
                
                // Sort rows
                rows.sort((a, b) => {
                    const aText = a.children[column].textContent.trim();
                    const bText = b.children[column].textContent.trim();
                    
                    const comparison = aText.localeCompare(bText, 'ja', { numeric: true });
                    return isAscending ? -comparison : comparison;
                });
                
                // Apply new sort class and icon
                this.classList.add(isAscending ? 'desc' : 'asc');
                this.querySelector('i').className = isAscending 
                    ? 'fas fa-sort-down ms-1' 
                    : 'fas fa-sort-up ms-1';
                
                // Reorder DOM
                rows.forEach(row => tbody.appendChild(row));
            });
        });
        @endif
    </script>
    @endpush
@endif