@extends('layouts.app')

@section('content')
<div class="bg-white rounded-3xl border-2 border-slate-200 p-10 max-w-5xl w-full mx-auto shadow-lg">
    <h1 class="text-3xl font-bold text-slate-500 mb-10 tracking-wide">ALEXANDER HOTEL</h1>
    <div class="flex flex-col md:flex-row gap-0 mb-10 items-stretch">
        <!-- PFsense Config File Segment -->
        <div class="flex-1 flex flex-col justify-between min-h-[300px] py-8 px-6">
            <h2 class="text-lg font-semibold text-slate-500 mb-8 text-center">PFsense Config File</h2>
            <div class="flex items-center gap-5 mb-8 flex-grow">
                <div class="shrink-0">
                    <!-- SVG Icon -->
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="#2563eb" stroke-width="2" fill="#e0f2fe"/>
                        <path d="M14 2V8H20" stroke="#2563eb" stroke-width="2" fill="none"/>
                        <path d="M16 13H8" stroke="#2563eb" stroke-width="1.5"/>
                        <path d="M16 17H8" stroke="#2563eb" stroke-width="1.5"/>
                        <path d="M10 9H8" stroke="#2563eb" stroke-width="1.5"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <div class="flex justify-between items-center mb-4 py-2">
                        <span class="text-sm text-slate-500 font-medium">user name</span>
                        <span class="text-sm text-slate-900 font-medium">siteA</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500 font-medium">password</span>
                        <span class="text-sm text-slate-900 font-medium">••••••••••••••••••••••</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-4 justify-center">
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Download Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/>
                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 15V3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Download
                </button>
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Share Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/>
                        <path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 2V15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Share
                </button>
            </div>
        </div>

        <!-- Divider -->
        <div class="hidden md:block w-px bg-slate-300 my-2"></div>
        <div class="block md:hidden h-px bg-slate-300 mx-0"></div>

        <!-- DAS Master Unit IPs -->
        <div class="flex-1 flex flex-col justify-between min-h-[300px] py-8 px-6">
            <h2 class="text-lg font-semibold text-slate-500 mb-8 text-center">DAS Master Unit IPs</h2>
            <div class="flex-grow mb-8">
                <div class="grid grid-cols-3 gap-4 border-b last:border-b-0 py-3 items-center">
                    <span class="text-sm text-slate-500 font-medium">Master Unit Sector 1</span>
                    <span class="text-sm text-slate-900 font-medium text-center">192.168.0.2</span>
                    <span class="text-sm text-slate-900 font-medium text-center">255.255.192</span>
                </div>
                <div class="grid grid-cols-3 gap-4 border-b last:border-b-0 py-3 items-center">
                    <span class="text-sm text-slate-500 font-medium">Master Unit Sector 2</span>
                    <span class="text-sm text-slate-900 font-medium text-center">192.168.0.4</span>
                    <span class="text-sm text-slate-900 font-medium text-center">255.255.192</span>
                </div>
                <div class="grid grid-cols-3 gap-4 py-3 items-center">
                    <span class="text-sm text-slate-500 font-medium">Master Unit Sector 3</span>
                    <span class="text-sm text-slate-900 font-medium text-center">192.168.0.4</span>
                    <span class="text-sm text-slate-900 font-medium text-center">255.255.192</span>
                </div>
            </div>
            <div class="flex gap-4 justify-center">
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Download Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/>
                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 15V3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Download
                </button>
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Share Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/>
                        <path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 2V15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Share
                </button>
            </div>
        </div>

        <!-- Divider -->
        <div class="hidden md:block w-px bg-slate-300 my-2"></div>
        <div class="block md:hidden h-px bg-slate-300 mx-0"></div>

        <!-- ERRCS BDA IPs -->
        <div class="flex-1 flex flex-col justify-between min-h-[300px] py-8 px-6">
            <h2 class="text-lg font-semibold text-slate-500 mb-8 text-center">ERRCS BDA IPs</h2>
            <div class="flex-grow mb-8">
                <div class="grid grid-cols-3 gap-4 py-3 items-center">
                    <span class="text-sm text-slate-500 font-medium">ERRCS BDA</span>
                    <span class="text-sm text-slate-900 font-medium text-center">192.168.0.5</span>
                    <span class="text-sm text-slate-900 font-medium text-center">255.255.192</span>
                </div>
            </div>
            <div class="flex gap-4 justify-center">
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Download Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M21 15V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V15" stroke="currentColor" stroke-width="2"/>
                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 15V3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Download
                </button>
                <button class="bg-blue-900 text-white border-2 border-amber-400 rounded-lg px-5 py-2 font-semibold flex items-center gap-2 min-w-[110px] justify-center hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[1px]">
                    <!-- Share Icon -->
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M4 12V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V12" stroke="currentColor" stroke-width="2"/>
                        <path d="M16 6L12 2L8 6" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 2V15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Share
                </button>
            </div>
        </div>
    </div>
    <!-- Main Action Button -->
    <div class="flex justify-center mt-5">
        <button class="bg-blue-900 text-white border-4 border-amber-400 rounded-xl px-10 py-4 text-xl font-bold min-w-[250px] hover:bg-blue-800 hover:border-amber-500 transition-all shadow hover:-translate-y-[2px]">
            Start Provisioning
        </button>
    </div>
</div>
@endsection