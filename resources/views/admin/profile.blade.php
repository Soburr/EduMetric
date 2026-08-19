@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">👤 My Profile</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Manage your account details and password
    </div>
</div>

<div style="display:grid; grid-template-columns:280px 1fr; gap:20px; align-items:start;">

    {{-- Avatar card --}}
    <div class="card" style="text-align:center; padding:32px 24px;">
        <div style="width:88px; height:88px; border-radius:22px; background:var(--blue-900);
                    display:flex; align-items:center; justify-content:center;
                    font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:32px; color:#fff; margin:0 auto 16px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' '), 1, 1)) }}
        </div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:17px; color:var(--blue-900); margin-bottom:4px;">
            {{ $user->name }}
        </div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-bottom:8px;">
            {{ $user->email }}
        </div>
        <span style="display:inline-block; background:var(--blue-50); color:var(--blue-600);
                     font-size:11.5px; font-weight:600; padding:4px 12px; border-radius:20px;">
            Administrator
        </span>

        @if($user->phone)
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--gray-100);
                        font-size:13px; color:var(--gray-500);">
                📞 {{ $user->phone }}
            </div>
        @endif

        <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--gray-100);
                    font-size:12px; color:var(--gray-400);">
            Member since {{ $user->created_at->format('M Y') }}
        </div>
    </div>

    {{-- Forms --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Personal Info --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:20px;
                        padding-bottom:14px; border-bottom:1px solid var(--gray-100);">
                ✏️ Personal Information
            </div>

            @if(session('success'))
                <div style="background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2);
                            color:#065f46; border-radius:8px; padding:12px 16px; margin-bottom:16px;
                            font-size:13.5px; font-weight:600;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label class="field-label">Full Name</label>
                        <input type="text" name="name" class="field-input"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">Email Address</label>
                        <input type="email" name="email" class="field-input"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="field-label">Phone Number</label>
                    <input type="text" name="phone" class="field-input"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="e.g. 08012345678" style="max-width:320px;">
                    @error('phone')
                        <div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                        style="background:var(--blue-900); color:#fff; border:none; padding:11px 24px;
                               border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                               font-weight:600; cursor:pointer;">
                    💾 Save Changes
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:20px;
                        padding-bottom:14px; border-bottom:1px solid var(--gray-100);">
                🔐 Change Password
            </div>

            @if(session('password_success'))
                <div style="background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2);
                            color:#065f46; border-radius:8px; padding:12px 16px; margin-bottom:16px;
                            font-size:13.5px; font-weight:600;">
                    ✓ {{ session('password_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.password') }}">
                @csrf
                @method('PUT')

                <div style="display:flex; flex-direction:column; gap:16px; max-width:400px;">
                    <div>
                        <label class="field-label">Current Password</label>
                        <input type="password" name="current_password" class="field-input" required>
                        @error('current_password')
                            <div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">New Password</label>
                        <input type="password" name="password" class="field-input"
                               placeholder="Minimum 8 characters" required>
                        @error('password')
                            <div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="field-input" required>
                    </div>

                    <div>
                        <button type="submit"
                                style="background:var(--blue-900); color:#fff; border:none; padding:11px 24px;
                                       border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                                       font-weight:600; cursor:pointer;">
                            🔐 Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .field-label {
        display: block; font-size: 12.5px; font-weight: 600;
        color: #374151; margin-bottom: 5px;
    }
    .field-input {
        width: 100%; padding: 10px 12px; border-radius: 8px;
        border: 1px solid var(--gray-200); font-size: 13.5px;
        font-family: 'DM Sans', sans-serif; color: var(--gray-700);
        background: #fff; box-sizing: border-box;
    }
    .field-input:focus {
        outline: none; border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
    @media (max-width: 768px) {
        .profile-layout { grid-template-columns: 1fr !important; }
    }
</style>
@endpush