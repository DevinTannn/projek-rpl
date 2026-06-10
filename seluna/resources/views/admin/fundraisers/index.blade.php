@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-4">
        <h4 class="fw-bold text-primary-custom mb-4">Verifikasi Fundraiser</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Fundraiser</th>
                        <th>Nama Sesuai KTP</th>
                        <th>NIK</th>
                        <th>Organisasi</th>
                        <th>KTP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($verifications as $v)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $v->user->username }}</div>
                                <div class="small text-muted">{{ $v->user->email }}</div>
                            </td>
                            <td>{{ $v->full_name }}</td>
                            <td><code>{{ $v->nik }}</code></td>
                            <td>{{ $v->organization_name }}</td>
                            <td>
                                <a href="{{ Storage::url($v->ktp_photo) }}" target="_blank" class="btn btn-sm btn-light">
                                    <i data-lucide="image" style="width: 14px;"></i> Lihat KTP
                                </a>
                            </td>
                            <td>
                                @if($v->status === 'approved')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($v->status === 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($v->status === 'pending')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.fundraisers.verify', $v->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Verifikasi</button>
                                        </form>
                                        <form action="{{ route('admin.fundraisers.reject', $v->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">Tolak</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $verifications->links() }}
        </div>
    </div>
</div>
@endsection
