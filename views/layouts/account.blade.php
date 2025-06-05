@template_extends('views.layouts.default')

@section('content')
    <div class="main">
        <div class="container">
            <div class="account-skeleton">
                @template_include('views.layouts.account.sidebar')

                <div class="page-content">
                    @yield('page-content')
                </div>
            </div>
        </div>
    </div>
@endsection
