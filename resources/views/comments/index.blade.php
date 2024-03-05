@foreach($comments as $comment)
    <div>
        <p>{{ $comment->content }}</p>
    </div>
@endforeach