@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'layouts.teacher.app')

@section('title', 'Annual Broadsheet')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between;
            flex-wrap:wrap; gap:12px; margin-bottom:24px;">
    <div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:20px; color:var(--blue-900);">🏆 Annual Broadsheet</div>
        <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
            End-of-year aggregate and final class positions
        </div>
    </div>
    @if($selectedClass && $annualSheet->count() > 0)
        <button onclick="window.print()"
        style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
               border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
               font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
    🖨️ Print
</button>
    @endif
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:24px;">
<form method="GET" action="{{ auth()->user()->role === 'admin' ? route('admin.broadsheet.annual') : route('teacher.broadsheet.annual') }}"
          style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; align-items:flex-end;"
          class="filter-grid">
        <div>
            <label class="field-label">Class</label>
            <select class="field-input" name="class_id" required>
                <option value="">-- Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label">Session</label>
            <select class="field-input" name="session" required>
                <option value="">-- Session --</option>
                @foreach($sessions as $session)
                    <option value="{{ $session }}"
                            {{ request('session') == $session ? 'selected' : '' }}>
                        {{ $session }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
    <button type="submit"
            style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                   border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                   font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
        🏆 Load Annual Sheet
    </button>
        </div>
    </form>
</div>

@if($selectedClass && $annualSheet->count() > 0)

    {{-- Header --}}
    <div style="background:var(--blue-900); color:#fff; border-radius:12px;
                padding:18px 24px; margin-bottom:20px; display:flex;
                align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:17px;">
                {{ $selectedClass->name }} — Annual Result
            </div>
            <div style="font-size:13px; opacity:.7; margin-top:3px;">
                Session: {{ $selectedSession }} &nbsp;·&nbsp;
                {{ $annualSheet->count() }} Students
            </div>
        </div>
        <div style="font-size:12px; opacity:.6;">
            Aggregate = Term 1 + Term 2 + Term 3 totals
        </div>
    </div>

    <div style="overflow-x:auto;" class="print-area">
        <table style="width:100%; border-collapse:collapse; font-size:13.5px; min-width:600px;">
            <thead>
                <tr style="background:var(--blue-900); color:#fff;">
                    <th style="padding:12px 16px; text-align:left; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Student</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; border-left:2px solid rgba(255,255,255,.15);">1st Term</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">2nd Term</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">3rd Term</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; border-left:2px solid rgba(255,255,255,.2);">Aggregate</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Position</th>
                </tr>
            </thead>
            <tbody>
                @foreach($annualSheet as $row)
                    <tr style="border-bottom:1px solid var(--gray-100);
                               background:{{ $loop->odd ? '#fff' : 'var(--gray-50)' }};"
                        onmouseover="this.style.background='var(--blue-50)'"
                        onmouseout="this.style.background='{{ $loop->odd ? '#fff' : 'var(--gray-50)' }}'">

                        <td style="padding:12px 16px;">
                            <div style="font-weight:600; color:var(--blue-900);">
                                {{ $row['student']->name }}
                            </div>
                            <div style="font-size:11.5px; color:var(--gray-400);">
                                {{ $row['student']->student_id }}
                            </div>
                        </td>

                        @foreach(['first','second','third'] as $term)
                            <td style="padding:12px 16px; text-align:center;
                                       color:var(--gray-600); font-weight:600;
                                       {{ $term === 'first' ? 'border-left:2px solid var(--gray-100);' : '' }}">
                                {{ $row['term_totals'][$term] > 0 ? number_format($row['term_totals'][$term], 1) : '—' }}
                            </td>
                        @endforeach

                        <td style="padding:12px 16px; text-align:center;
                                   font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                   font-size:16px; color:var(--blue-900);
                                   border-left:2px solid var(--gray-200);">
                            {{ number_format($row['aggregate'], 1) }}
                        </td>

                        <td style="padding:12px 16px; text-align:center;">
                            <span style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                         font-size:15px; color:var(--blue-900);">
                                {{ $row['position'] ?? '—' }}
                                @if(($row['position'] ?? 0) <= 3)
                                    {{ $row['position'] == 1 ? '🥇' : ($row['position'] == 2 ? '🥈' : '🥉') }}
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@elseif(request('class_id'))
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>No annual data found. Ensure all three terms have scores entered.</p>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
    .field-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
    .field-input {
        width:100%; padding:10px 12px; border-radius:8px; border:1px solid var(--gray-200);
        font-size:13.5px; font-family:'DM Sans',sans-serif; color:var(--gray-700);
        box-sizing:border-box; background:#fff;
    }
    .field-input:focus { outline:none; border-color:var(--blue-500); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    @media (max-width:640px) { .filter-grid { grid-template-columns:1fr !important; } }
    @media print {
        .sidebar, .topbar, form, button { display:none !important; }
        .page { padding:0 !important; }
        .main { margin-left:0 !important; }
        .print-area { overflow:visible !important; }
    }
</style>
@endpush