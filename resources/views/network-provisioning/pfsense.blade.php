@extends('layouts.app')

@section('content')
<style>
    .pfsense-row {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 2.5rem;
    }
    @media (min-width: 768px) {
        .pfsense-row {
            flex-direction: row;
        }
        .pfsense-segment + .pfsense-segment {
            border-left: 1px solid #cbd5e1;
        }
    }
    .pfsense-segment {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2rem 1.5rem;
    }
    .pfsense-segment:not(:last-child) {
        border-bottom: 1px solid #cbd5e1;
    }
    @media (min-width: 768px) {
        .pfsense-segment:not(:last-child) {
            border-bottom: none;
        }
    }
    .pfsense-segment h2 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 2rem;
        text-align: center;
    }
    .pfsense-table-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .pfsense-table-row:last-child {
        border-bottom: none;
    }
    .pfsense-label {
        font-size: .95rem;
        color: #64748b;
        font-weight: 500;
    }
    .pfsense-value {
        font-size: .95rem;
        color: #1e293b;
        font-weight: 500;
        text-align: right;
    }
    .pfsense-btn-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }
    .pfsense-action-btn {
        background-color: #13395d;
        color: white;
        border: 2px solid #fbbf0f;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 110px;
        justify-content: center;
        transition: all 0.2s;
    }
    .pfsense-action-btn:hover {
        background-color: #13395d;
        border-color: #fbbf0f;
    }
    .pfsense-main-btn {
        background-color: #13395d;
        color: white;
        border: 4px solid #fbbf0f;
        border-radius: 16px;
        padding: 1rem 2.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        min-width: 250px;
        margin: 0 auto;
        display: block;
        margin-top: 2rem;
        transition: all 0.2s;
    }
    .pfsense-main-btn:hover {
        background-color: #13395d;
        border-color: #fbbf0f;
    }
</style>
<div class="bg-white rounded-3xl border border-slate-200 p-5 p-md-5 max-w-7xl w-100 mx-auto shadow-lg" style="margin-top: 10%">
    <h1 style="font-size:2rem;font-weight:700;color:#64748b;margin-bottom:2.5rem;letter-spacing:1px;">PROPERTY NAME</h1>
    <div class="pfsense-row">
        <!-- PFsense Config File Segment -->
        <div class="pfsense-segment">
            <h2>PFsense Config File</h2>
            <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:2rem;">
                <div style="flex-shrink:0;">
                    <!-- SVG Icon -->
                    <svg width="90" height="90" fill="none"><path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="#2563eb" stroke-width="2" fill="#e0f2fe"/><path d="M14 2V8H20" stroke="#2563eb" stroke-width="2"/><path d="M16 13H8" stroke="#2563eb" stroke-width="1.5"/><path d="M16 17H8" stroke="#2563eb" stroke-width="1.5"/><path d="M10 9H8" stroke="#2563eb" stroke-width="1.5"/></svg>
                </div>
                <div style="flex-grow:1;">
                    <div class="pfsense-table-row">
                        <span class="pfsense-label">user name</span>
                        <span class="pfsense-value">siteA</span>
                    </div>
                    <div class="pfsense-table-row">
                        <span class="pfsense-label">password</span>
                        <span class="pfsense-value">••••••••••••••••••••••</span>
                    </div>
                </div>
            </div>
            <div class="pfsense-btn-group">
                <button class="pfsense-action-btn">
                    <!-- Download Icon -->
                    <svg width="16" height="16" fill="none"><path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/><path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/><path d="M12 15V3" stroke="currentColor" stroke-width="2"/></svg>
                    Download
                </button>
                <button class="pfsense-action-btn">
                    <!-- Share Icon -->
                    <svg width="16" height="16" fill="none"><path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/><path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/><path d="M12 2V15" stroke="currentColor" stroke-width="2"/></svg>
                    Share
                </button>
            </div>
        </div>
        <!-- DAS Master Unit IPs -->
        <div class="pfsense-segment">
            <h2>DAS Master Unit IPs</h2>
            <div style="flex-grow:1;margin-bottom:2rem;">
                <div class="pfsense-table-row">
                    <span class="pfsense-label">Master Unit Sector 1</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 1 FIRST USABLE IP</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 1 LAST USABLE IP</span>
                </div>
                <div class="pfsense-table-row">
                    <span class="pfsense-label">Master Unit Sector 2</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 2 FIRST USABLE IP</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 2 LAST USABLE IP</span>
                </div>
                <div class="pfsense-table-row">
                    <span class="pfsense-label">Master Unit Sector 3</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 3 FIRST USABLE IP</span>
                    <span class="pfsense-value" style="text-align:center;">MASTER UNIT 3 LAST USABLE IP</span>
                </div>
            </div>
            <div class="pfsense-btn-group">
                <button class="pfsense-action-btn">
                    <svg width="16" height="16" fill="none"><path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/><path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/><path d="M12 15V3" stroke="currentColor" stroke-width="2"/></svg>
                    Download
                </button>
                <button class="pfsense-action-btn">
                    <svg width="16" height="16" fill="none"><path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/><path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/><path d="M12 2V15" stroke="currentColor" stroke-width="2"/></svg>
                    Share
                </button>
            </div>
        </div>
        <!-- ERRCS BDA IPs -->
        <div class="pfsense-segment">
            <h2>ERRCS BDA IPs</h2>
            <div style="flex-grow:1;margin-bottom:2rem;">
                <div class="pfsense-table-row">
                    <span class="pfsense-label">ERRCS BDA</span>
                    <span class="pfsense-value" style="text-align:center;">ERRCS BDA FIRST USABLE IP</span>
                    <span class="pfsense-value" style="text-align:center;">ERRCS BDA LAST USABLE IP</span>
                </div>
            </div>
            <div class="pfsense-btn-group">
                <button class="pfsense-action-btn">
                    <svg width="16" height="16" fill="none"><path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/><path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/><path d="M12 15V3" stroke="currentColor" stroke-width="2"/></svg>
                    Download
                </button>
                <button class="pfsense-action-btn">
                    <svg width="16" height="16" fill="none"><path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/><path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/><path d="M12 2V15" stroke="currentColor" stroke-width="2"/></svg>
                    Share
                </button>
            </div>
        </div>
    </div>
    <button class="pfsense-main-btn">
        Start Provisioning
    </button>
</div>
@endsection