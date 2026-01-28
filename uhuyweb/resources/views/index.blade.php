@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row">
        @forelse($posts as $post)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm h-100">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $post->id }}">
                    <img src="{{ asset('storage/' . $post->imageUrl) }}" class="card-img-top" alt="{{ $post->title }}">
                </a>
                <div class="card-body px-3">
                    <h6 class="fw-bold mb-1 text-truncate">{{ $post->title }}</h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill mb-2" style="font-size: 0.7rem;">
                        {{ $post->category_name ?? 'Umum' }}
                    </span>
                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <div class="small text-muted">
                            <i class="fa-solid fa-heart text-danger me-1"></i> {{ $post->likes }}
                        </div>
                        <div class="small text-muted">
                            <i class="fa-solid fa-eye me-1 text-primary"></i> {{ $post->views }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalDetail{{ $post->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content shadow-2xl">
                        <div class="modal-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-8 bg-dark-gallery d-flex align-items-center justify-content-center" style="min-height: 500px;">
                                    <img src="{{ asset('storage/' . $post->imageUrl) }}" class="img-fluid" style="max-height: 85vh;">
                                </div>
                                <div class="col-lg-4 d-flex flex-column bg-white">
                                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-0">Informasi Foto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="p-4 flex-grow-1 overflow-auto" style="max-height: 450px;">
                                        <h4 class="fw-bold text-primary">{{ $post->title }}</h4>
                                        <p class="text-secondary small">{{ $post->description }}</p>
                                        
                                        <div class="mt-3">
                                            @foreach(explode(',', $post->tags) as $tag)
                                                <span class="badge bg-secondary-subtle text-secondary me-1">#{{ trim($tag) }}</span>
                                            @endforeach
                                        </div>

                                        <hr class="my-4">
                                        
                                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-comments me-2"></i>Komentar</h6>
                                        <div class="comment-box bg-light p-4 rounded-3 text-center">
                                            <p class="text-muted small mb-0">Fitur komentar hanya tersedia untuk pengguna terdaftar.</p>
                                            <a href="/login" class="btn btn-sm btn-link text-decoration-none fw-bold">Masuk Sekarang</a>
                                        </div>
                                    </div>

                                    <div class="p-4 border-top bg-light">
                                        <div class="d-flex justify-content-between align-items-center small mb-3">
                                            <span><i class="fa-solid fa-calendar-day me-1"></i> {{ date('d M Y', strtotime($post->createdAt)) }}</span>
                                            <span class="fw-bold"><i class="fa-solid fa-heart text-danger"></i> {{ $post->likes }} Suka</span>
                                        </div>
                                        <a href="/login" class="btn btn-primary w-100 rounded-pill">Login untuk Like</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h3 class="text-muted">Belum ada foto yang tersedia.</h3>
        </div>
        @endforelse
    </div>
</div>
@endsection
@include('layouts.footer')
