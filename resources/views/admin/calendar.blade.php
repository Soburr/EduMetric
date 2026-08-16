@extends('layouts.admin.app')

@section('title', 'School Calendar')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">📅 School Calendar</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Set the number of days school opened per term per session
    </div>
</div>

@if(session('success'))
    <div style="background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2);
                color:#065f46; border-radius:8px; padding:12px 16px; margin-bottom:20px;
                font-size:13.5px; font-weight:600;">
        ✓ {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.calendar.store') }}">
    @csrf
    @php $i = 0; @endphp
    @foreach($sessions as $session)
        <div class="card" style="margin-bottom:20px;">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:16px;">
                📆 {{ $session }} Session
            </div>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
                @foreach(['first' => 'First Term', 'second' => 'Second Term', 'third' => 'Third Term'] as $term => $label)
                    @php
                        $key      = $term . '_' . $session;
                        $existing = $calendars[$key] ?? null;
                    @endphp
                    <div>
                        <label class="field-label">{{ $label }} — Days Opened</label>
                        <input type="hidden" name="entries[{{ $i }}][term]"    value="{{ $term }}">
                        <input type="hidden" name="entries[{{ $i }}][session]" value="{{ $session }}">
                        <input type="number"
                               name="entries[{{ $i }}][days_opened]"
                               class="field-input"
                               placeholder="e.g. 90"
                               min="1" max="365"
                               value="{{ $existing?->days_opened }}">
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
    @endforeach

    <button type="submit"
            style="background:var(--blue-900); color:#fff; border:none; padding:11px 24px;
                   border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                   font-weight:600; cursor:pointer;">
        💾 Save Calendar
    </button>
</form>

@endsection

@push('styles')
<style>
    .field-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
    .field-input {
        width:100%; padding:10px 12px; border-radius:8px; border:1px solid var(--gray-200);
        font-size:13.5px; font-family:'DM Sans',sans-serif; color:var(--gray-700);
        background:#fff; box-sizing:border-box;
    }
    .field-input:focus { outline:none; border-color:var(--blue-500); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    @media (max-width:640px) { .grid-3 { grid-template-columns:1fr !important; } }
</style>
@endpush