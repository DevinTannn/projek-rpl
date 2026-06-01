@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold text-[#1B3022] mb-6">Dashboard Admin</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
            <p class="text-sm text-gray-500 uppercase tracking-wider">Kampanye Berjalan</p>
            <h3 class="text-3xl font-bold text-[#1B3022] mt-2">45 <span class="text-sm font-normal text-gray-400">Aktif</span></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-orange-500">
            <p class="text-sm text-gray-500 uppercase tracking-wider">Perlu Verifikasi</p>
            <h3 class="text-3xl font-bold text-[#1B3022] mt-2">12 <span class="text-sm font-normal text-gray-400">Kampanye</span></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
            <p class="text-sm text-gray-500 uppercase tracking-wider">Akun Perlu Verifikasi</p>
            <h3 class="text-3xl font-bold text-[#1B3022] mt-2">8 <span class="text-sm font-normal text-gray-400">Pengguna</span></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-[#1B3022] mb-4">Kampanye Terbaru</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg">
                    <span>Bantu Korban Banjir</span>
                    <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">Pending</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-[#1B3022] mb-4">Akun Terbaru</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg">
                    <span>Andi Wijaya</span>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Menunggu</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection