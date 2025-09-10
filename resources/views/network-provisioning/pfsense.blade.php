@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>
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
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-code me-2"></i>
                        PFsense Config File
                    </h5>
                </div>
                <div class="card-body">
                    <h1 style="font-size:1.5rem;font-weight:700;color:#64748b;margin-bottom:1.5rem;letter-spacing:1px;">{{ $propertyName ?? 'PROPERTY NAME' }}</h1>
                    <div class="pfsense-row">
                        <div class="pfsense-segment" style="padding:0; border:none;">
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
                @if(isset($xmlFile))
                <a class="pfsense-action-btn" href="{{ route('network-provisioning.downloadXml', ['fileName' => $xmlFile]) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                    <i class="mdi mdi-download" style="color: white;"></i>
                    Download XML
                </a>
                @endif
                <a type="button"
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($hasMaster)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        DAS Master Unit IPs
                    </h5>
                </div>
                <div class="card-body">
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
            </div>
        </div>
    </div>
    @endif

    @if($hasBda)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-broadcast-tower me-2"></i>
                        ERRCS BDA IPs
                    </h5>
                </div>
                <div class="card-body">
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
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-12 text-center">
            <button id="start-provisioning" class="pfsense-main-btn" data-provision-id="{{ $provisionId }}">
                Start Provisioning
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('start-provisioning');
    btn.addEventListener('click', async function() {
        const provisionId = btn.getAttribute('data-provision-id');
        const csrfToken = '{{ csrf_token() }}';

        try {
            const response = await fetch('{{ route("provision.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ provision_id: provisionId })
            });

            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                // Not JSON, probably HTML or plain text error
                alert('Server returned non-JSON response:\n\n' + text);
                return;
            }

            if (data.success) {
                alert('Provisioning successful!');
            } else {
                alert('Provisioning failed: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            alert('An error occurred: ' + error.message);
        }
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