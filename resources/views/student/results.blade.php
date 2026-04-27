@extends('layouts.app')

@section('title', 'My Results')

@section('content')

    <div class="section-head" style="margin-bottom:20px;">
        <div class="section-title">📊 My Test Results</div>
    </div>

    @if(isset($results) && count($results) > 0)
        <div class="card" style="padding:0; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:14px 20px; text-align:left; font-weight:600; color:var(--blue-900);">Subject</th>
                        <th style="padding:14px 20px; text-align:left; font-weight:600; color:var(--blue-900);">Test</th>
                        <th style="padding:14px 20px; text-align:center; font-weight:600; color:var(--blue-900);">Score</th>
                        <th style="padding:14px 20px; text-align:center; font-weight:600; color:var(--blue-900);">Grade</th>
                        <th style="padding:14px 20px; text-align:left; font-weight:600; color:var(--blue-900);">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr style="border-bottom:1px solid var(--gray-100);">
                            <td style="padding:13px 20px; color:var(--gray-700);">{{ $result->subject }}</td>
                            <td style="padding:13px 20px; color:var(--gray-700);">{{ $result->test_title }}</td>
                            <td style="padding:13px 20px; text-align:center;">
                                <span style="font-weight:700; color:{{ $result->percentage >= 75 ? 'var(--green)' : ($result->percentage >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                                    {{ $result->score }}/{{ $result->total }} ({{ $result->percentage }}%)
                                </span>
                            </td>
                            <td style="padding:13px 20px; text-align:center;">
                                <span class="module-badge {{ $result->percentage >= 75 ? 'badge-green' : ($result->percentage >= 50 ? 'badge-blue' : 'badge-amber') }}">
                                    {{ $result->grade ?? '—' }}
                                </span>
                            </td>
                            <td style="padding:13px 20px; color:var(--gray-400); font-size:12.5px;">
                                {{ \Carbon\Carbon::parse($result->created_at)->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No results yet. Complete a test to see your scores here.</p>
            </div>
        </div>
    @endif

@endsection