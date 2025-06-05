@template_extends('views.layouts.default')

@title($page->title, false)
@description($page->content)

@section('content')
    <div class="main mt-3">
        <div class="container">
            <h1 class="text-2xl font-bold">{{ $page->title }}</h1>

            <div class="text-justify leading-7 mt-5">
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection
