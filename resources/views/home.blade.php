@extends('layouts.app')

@section('content')
    <!-- Explore Topics -->
    <div class="mt-5 p-4 bg-light rounded shadow-sm">
        <h2 class="h5 fw-semibold mb-3 text-dark">Explore Topics</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/tags/sports') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🏀 Sports</a>
            <a href="{{ url('/tags/programming') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">💻 Programming</a>
            <a href="{{ url('/tags/health') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🩺 Health</a>
            <a href="{{ url('/tags/design') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🎨 Design</a>
            <a href="{{ url('/tags/startups') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🚀 Startups</a>
            <a href="{{ url('/tags/politics') }}" class="badge rounded-pill text-bg-light px-3 py-2 border border-secondary-subtle text-dark fw-normal hover-shadow">🗳️ Politics</a>
        </div>
    </div>

    <!-- Articles with Comments -->
    @foreach ($articles as $article)
        <div class="d-flex border-bottom pb-4 mb-4 mt-5 align-items-start">
            <div class="flex-grow-1">
                <div class="text-muted small mb-1">
                    <span class="fw-semibold">by</span> <strong>{{ $article->user->name }}</strong>
                </div>
                <h1 class="h3 fw-bold text-dark mb-2">{{ $article->title }}</h1>
                <div class="text-muted small d-flex gap-3 align-items-center">
                    <span>📅 {{ $article->created_at->format('d-m-Y') }}</span>
                    <span class="like-btn text-dark" style="cursor: pointer;" data-article-id="{{ $article->id }}">
                        👍 <span class="like-count">{{ $article->likes->count() }}</span>
                    </span>
                    <span class="comment-count">💬 {{ $article->comments->count() }}</span>
                    <a href="#" class="text-decoration-none text-primary">Read More...</a>
                </div>

                <!-- Comment Form -->
                @auth
                    <form class="comment-form mt-3" data-article-id="{{ $article->id }}">
                        @csrf
                        <div class="mb-2">
                            <textarea class="form-control" name="comment" rows="2" placeholder="Write a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Submit Comment</button>
                    </form>
                @else
                    <p class="mt-3 text-muted">Please <a href="{{ route('login') }}">login</a> to comment.</p>
                @endauth

                <!-- Comment List -->
                <div class="comment-list mt-3" id="comment-list-{{ $article->id }}">
                    {{-- Show latest 2 comments --}}
                    @foreach ($article->comments->sortByDesc('created_at')->take(2) as $comment)
                        <div class="border-bottom pb-2 mb-2 comment-item" data-comment-id="{{ $comment->id }}">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            <div>{{ $comment->body }}</div>
                            @auth
                                @if (auth()->id() === $comment->user_id)
                                    <button class="btn btn-sm btn-link text-danger delete-comment-btn p-0">Delete</button>
                                @endif
                            @endauth
                        </div>
                    @endforeach

                    {{-- Hidden comments --}}
                    <div class="all-comments d-none">
                        @foreach ($article->comments->sortByDesc('created_at')->skip(2) as $comment)
                            <div class="border-bottom pb-2 mb-2 comment-item" data-comment-id="{{ $comment->id }}">
                                <strong>{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                <div>{{ $comment->body }}</div>
                                @auth
                                    @if (auth()->id() === $comment->user_id)
                                        <button class="btn btn-sm btn-link text-danger delete-comment-btn p-0">Delete</button>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>

                    @if ($article->comments->count() > 2)
                        <button class="btn btn-link p-0 text-primary toggle-comments-btn" data-article-id="{{ $article->id }}">
                            See More Comments
                        </button>
                    @endif
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
            // Like functionality
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
                        button.toggleClass('text-dark text-primary');
                    }
                });
            });

            // Submit comment
            $('.comment-form').on('submit', function (e) {
                e.preventDefault();

                var form = $(this);
                var articleId = form.data('article-id');
                var commentText = form.find('textarea[name="comment"]').val();
                var commentList = $('#comment-list-' + articleId);

                $.ajax({
                    url: '/articles/' + articleId + '/comments',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: commentText
                    },
                    success: function (response) {
                        if (response.success) {
                            const commentHtml = `
                                <div class="border-bottom pb-2 mb-2 comment-item" data-comment-id="${response.comment.id}">
                                    <strong>${response.comment.user}</strong>
                                    <small class="text-muted">${response.comment.created_at}</small>
                                    <div>${response.comment.body}</div>
                                    <button class="btn btn-sm btn-link text-danger delete-comment-btn p-0">Delete</button>
                                </div>
                            `;
                            commentList.find('.comment-form').after(commentHtml);
                            form[0].reset();

                            // Update comment count
                            let countSpan = commentList.closest('div.d-flex').find('.comment-count');
                            let currentCount = parseInt(countSpan.text().replace(/\D/g, '')) + 1;
                            countSpan.text('💬 ' + currentCount);
                        }
                    },
                    error: function () {
                        alert('Failed to submit comment. Please try again.');
                    }
                });
            });

            // Delete comment
            $(document).on('click', '.delete-comment-btn', function (e) {
                e.preventDefault();

                if (!confirm('Are you sure you want to delete this comment?')) return;

                var commentDiv = $(this).closest('.comment-item');
                var commentId = commentDiv.data('comment-id');

                $.ajax({
                    url: `/comments/${commentId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            commentDiv.remove();

                            // Update comment count
                            let commentList = commentDiv.closest('.comment-list');
                            let countSpan = commentList.closest('div.d-flex').find('.comment-count');
                            let currentCount = parseInt(countSpan.text().replace(/\D/g, '')) - 1;
                            countSpan.text('💬 ' + currentCount);
                        }
                    },
                    error: function () {
                        alert('Failed to delete comment. Please try again.');
                    }
                });
            });

            // Toggle comments
            $('.toggle-comments-btn').on('click', function () {
                var btn = $(this);
                var commentList = $('#comment-list-' + btn.data('article-id'));
                var hiddenComments = commentList.find('.all-comments');

                if (hiddenComments.hasClass('d-none')) {
                    hiddenComments.removeClass('d-none');
                    btn.text('See Less Comments');
                } else {
                    hiddenComments.addClass('d-none');
                    btn.text('See More Comments');
                }
            });
        });
    </script>
@endsection
