@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 mx-auto max-w-4xl" style="margin-top: 2%;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 mx-auto max-w-4xl" style="margin-top: 2%;">
        {{ session('error') }}
    </div>
@endif
@php
    use Illuminate\Support\Str;

    $hasMaster = isset($ipAssignments) && collect($ipAssignments)->contains(function($a) {
        return Str::startsWith($a['label'], 'Master Unit Sector');
    });
    $hasBda = isset($ipAssignments) && collect($ipAssignments)->contains(function($a) {
        return Str::startsWith($a['label'], 'ERRCS BDA');
    });

    // Generate the email body for sharing
    $outlookBody = "IPs: \n\n";
    if ($hasMaster) {
        $outlookBody .= "DAS Master Unit IPs:\n";
        foreach ($ipAssignments as $assignment) {
            if (Str::startsWith($assignment['label'], 'Master Unit Sector')) {
                $outlookBody .= "{$assignment['label']}:\n";
                $outlookBody .= "IP: " . ($assignment['ip'] ?? 'N/A') . "\n";
                $outlookBody .= "Mask: " . ($assignment['mask'] ?? 'N/A') . "\n\n";
            }
        }
    }
    if ($hasBda) {
        $outlookBody .= "ERRCS BDA IPs:\n";
        foreach ($ipAssignments as $assignment) {
            if (Str::startsWith($assignment['label'], 'ERRCS BDA')) {
                $outlookBody .= "{$assignment['label']}:\n";
                $outlookBody .= "IP: " . ($assignment['ip'] ?? 'N/A') . "\n";
                $outlookBody .= "Mask: " . ($assignment['mask'] ?? 'N/A') . "\n\n";
            }
        }
    }
    if (isset($xmlFile)) {
        $outlookBody .= "XML File: " . route('network-provisioning.downloadXml', ['fileName' => $xmlFile]) . "\n";
    }
    $mailtoBody = rawurlencode($outlookBody);
    $mailtoSubject = rawurlencode('IPs Provisioning - ' . ($propertyName ?? 'PROPERTY NAME'));
@endphp
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
    /* --- NEW GRID LAYOUT FOR TABLES --- */
    .pfsense-table-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }
    .pfsense-table-grid-header {
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: .5rem;
        margin-bottom: 0;
        background: none;
    }
    .pfsense-table-grid span,
    .pfsense-table-grid-header span {
        text-align: center;
        padding: .5rem 0;
    }
    .pfsense-table-grid span:first-child,
    .pfsense-table-grid-header span:first-child {
        text-align: left;
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
    }
    .pfsense-value-center {
        text-align: center !important;
        width: 100%;
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
        text-decoration: none;
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
    .password-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .password-icon {
        color: #64748b;
        cursor: pointer;
        font-size: 18px;
        transition: color 0.2s;
    }
    .password-icon:hover {
        color: #13395d;
    }
    .password-input {
        border: none;
        background: transparent;
        outline: none;
        font-size: .95rem;
        color: #1e293b;
        font-weight: 500;
        width: 120px;
    }
</style>
<div class="bg-white rounded-3xl border border-slate-200 p-5 p-md-5 w-100 mx-auto shadow-lg" style="margin-top: 10%; max-width: 1600px;">
    <h1 style="font-size:2rem;font-weight:700;color:#64748b;margin-bottom:2.5rem;letter-spacing:1px;">{{ $propertyName ?? 'PROPERTY NAME' }}</h1>
    <div class="pfsense-row">
        <!-- PFsense Config File Segment (always visible) -->
        <div class="pfsense-segment">
            <h2>PFsense Config File</h2>
            <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:2rem;">
                <div style="flex-shrink:0;">
                    <!-- Ícone MDI grande -->
                    <i class="mdi mdi-file-document" style="font-size:90px;border-radius:8px;display:inline-block;padding:10px;color: #13395d;"></i>
                </div>
                <div style="flex-grow:1;">
                    <div class="pfsense-table-grid">
                        <span class="pfsense-label" style="text-align:left;">user name</span>
                        <span class="pfsense-value pfsense-value-center" colspan="2">admin</span>
                    </div>
                    <div class="pfsense-table-grid" style="border-bottom: none;">
                        <span class="pfsense-label" style="text-align:left;">password</span>
                        <span colspan="2">
                            <div class="password-group" style="justify-content:center;">
                                <input class="password-input" type="password" value="$ynd30@noc" id="password_PFsense" readonly>
                                <i class="mdi mdi-eye password-icon" onclick="show_password()" title="Show/Hide Password"></i>
                                <i class="mdi mdi-content-copy password-icon" onclick="copy_to_clipboard()" title="Copy to Clipboard"></i>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="pfsense-btn-group">
                <button class="pfsense-action-btn">
                    <!-- Download Icon -->
                    <i class="mdi mdi-download" style="color: white;"></i>
                    @if(isset($xmlFile))
                        <a href="{{ route('network-provisioning.downloadXml', ['fileName' => $xmlFile]) }}" style="color: white; text-decoration: none;">
                            Download XML
                        </a>
                    @endif
                </button>
                <a 
                    class="pfsense-action-btn"
                    style="text-decoration: none; display: flex; align-items: center; gap: 8px;"
                    href="mailto:?subject={{ $mailtoSubject }}&body={{ $mailtoBody }}"
                    target="_blank"
                >
                    <i class="mdi mdi-share-variant" style="color: white;"></i>
                    Share
                </a>
            </div>
        </div>

        @if($hasMaster)
        <!-- DAS Master Unit IPs (Dynamic) -->
        <div class="pfsense-segment">
            <h2>DAS Master Unit IPs</h2>
            <div class="pfsense-table-grid pfsense-table-grid-header">
                <span></span>
                <span>IP</span>
                <span>Mask</span>
            </div>
            @foreach($ipAssignments as $assignment)
                @if(Str::startsWith($assignment['label'], 'Master Unit Sector'))
                    <div class="pfsense-table-grid">
                        <span class="pfsense-label">{{ $assignment['label'] }}</span>
                        <span class="pfsense-value" style="text-align:center;">{{ $assignment['ip'] ?? 'N/A' }}</span>
                        <span class="pfsense-value" style="text-align:center;">{{ $assignment['mask'] ?? 'N/A' }}</span>
                    </div>
                @endif
            @endforeach
        </div>
        @endif

        @if($hasBda)
        <!-- ERRCS BDA IPs (Dynamic) -->
        <div class="pfsense-segment">
            <h2>ERRCS BDA IPs</h2>
            <div class="pfsense-table-grid pfsense-table-grid-header">
                <span></span>
                <span>IP</span>
                <span>Mask</span>
            </div>
            @foreach($ipAssignments as $assignment)
                @if(Str::startsWith($assignment['label'], 'ERRCS BDA'))
                    <div class="pfsense-table-grid">
                        <span class="pfsense-label">{{ $assignment['label'] }}</span>
                        <span class="pfsense-value" style="text-align:center;">{{ $assignment['ip'] ?? 'N/A' }}</span>
                        <span class="pfsense-value" style="text-align:center;">{{ $assignment['mask'] ?? 'N/A' }}</span>
                    </div>
                @endif
            @endforeach
        </div>
        @endif

    </div>
    <button id="start-provisioning" class="pfsense-main-btn">
        Start Provisioning
    </button>
</div>

<script>
document.getElementById('start-provisioning').addEventListener('click', function() {
    fetch('/provision/start', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            // Add any data you want to send
        })
    })
    .then(response => response.json())
    .then(data => {
        alert('Provisioning started!'); // Or handle success feedback
    })
    .catch(error => {
        alert('Provisioning failed!');
    });
});
</script>

<script>
function copy_to_clipboard() {
    var copyText = document.getElementById("password_PFsense");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    var copyIcon = event.target;
    copyIcon.classList.remove("mdi-content-copy");
    copyIcon.classList.add("mdi-check");
    copyIcon.style.color = "#10b981";
    setTimeout(function() {
        copyIcon.classList.remove("mdi-check");
        copyIcon.classList.add("mdi-content-copy");
        copyIcon.style.color = "#64748b";
    }, 2000);
}

function show_password() {
    var x = document.getElementById("password_PFsense");
    var icon = event.target;
    if (x.type === "password") {
        x.type = "text";
        icon.classList.remove("mdi-eye");
        icon.classList.add("mdi-eye-off");
    } else {
        x.type = "password";
        icon.classList.remove("mdi-eye-off");
        icon.classList.add("mdi-eye");
    }
}
</script>
@endsection