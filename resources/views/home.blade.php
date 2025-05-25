@extends('layouts.app')

@section('content')
    <!-- Explore Topics -->
    <div class="mt-5 p-4 bg-light rounded shadow-sm">
        <h2 class="h5 fw-semibold mb-3 text-dark">Explore Topics</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/tags/sports') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🏀
                Sports</a>
            <a href="{{ url('/tags/programming') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">💻
                Programming</a>
            <a href="{{ url('/tags/health') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🩺
                Health</a>
            <a href="{{ url('/tags/design') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🎨
                Design</a>
            <a href="{{ url('/tags/startups') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🚀
                Startups</a>
            <a href="{{ url('/tags/politics') }}"
                class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🗳️
                Politics</a>
        </div>
    </div>

    <!-- Article Section -->

    @foreach ($articles as $article)
        <div class="d-flex border-bottom pb-4 mb-4 mt-5 align-items-start">
            <div class="flex-grow-1">
                <div class="text-muted small mb-1">
                    <span class="fw-semibold"></span> by <strong>{{ $article->user->name }}</strong>
                </div>
                <h1 class="h3 fw-bold text-dark mb-2">{{ $article->title }}</h1>
                {{-- <p class="text-muted small mb-2">{{ $article['subtitle'] }}</p> --}}
                <div class="text-muted small d-flex gap-3 align-items-center">
                    <span>📅 {{ $article->created_at->format('d-m-Y') }}</span>
                    <span class="like-btn text-dark" style="cursor: pointer;" data-article-id="{{ $article->id }}">
                        👍 <span class="like-count">{{ $article->likes->count() }}</span>
                    </span>
                    <span>💬 12</span>
                    <a href="#" class="text-decoration-none text-primary">Read More...</a>
                </div>
            </div>
            <div class="ms-3">
                <img src="{{ asset('storage/' . $article->image) }}" class="rounded" alt="Article Image"
                    style="width: 100px; height: 100px; object-fit: cover;">
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.like-btn').on('click', function () {
        var button = $(this);
        var articleId = button.data('article-id');

        $.ajax({
            url: '/articles/' + articleId + '/like',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                button.find('.like-count').text(response.count);

                if (response.liked) {
                    button.removeClass('text-dark').addClass('text-primary');
                } else {
                    button.removeClass('text-primary').addClass('text-dark');
                }
            }
        });
    });
});
</script>
@endsection
