@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Thư viện ảnh sách') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        @forelse ($booksWithGallery as $book)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($book->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $book->images->first()->image_path) }}" 
                                            class="card-img-top" alt="{{ $book->title }}" 
                                            style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex justify-content-center align-items-center" 
                                            style="height: 250px;">
                                            <span class="text-muted">{{ __('Không có ảnh') }}</span>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $book->title }}</h5>
                                        <p class="card-text text-muted">{{ __('Số lượng ảnh') }}: {{ $book->images->count() }}</p>
                                        <a href="{{ route('gallery.show', $book->id) }}" class="btn btn-primary">
                                            {{ __('Xem thư viện ảnh') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    {{ __('Không có sách nào có thư viện ảnh.') }}
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $booksWithGallery->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 