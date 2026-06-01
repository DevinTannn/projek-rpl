@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold text-[#1B3022] mb-6">Verifikasi Kampanye</h2>
    
    <div class="grid gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                <div>
                    <h3 class="font-bold text-lg">Bantu Renovasi Sekolah</h3>
                    <p class="text-sm text-gray-500">Oleh: Ahmad Fauzi • 2 jam lalu</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Tolak</button>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Setujui</button>
            </div>
        </div>
    </div>
</div>
@endsection