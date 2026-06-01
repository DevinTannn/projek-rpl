@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold text-[#1B3022] mb-6">Verifikasi Akun Pengguna</h2>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nama Lengkap</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Dokumen</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-800">Budi Santoso</td>
                    <td class="px-6 py-4 text-gray-500">budi@gmail.com</td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 underline text-sm hover:text-blue-800">Lihat KTP</button>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition duration-200">
                                Verifikasi
                            </button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition duration-200">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection