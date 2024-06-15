@extends("layouts.base")

@section("app")
    <div id="wrapper">

        @include("layouts.components.sidebar")
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                @include("layouts.components.navigation")
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
                    </div>

                    @yield("body")

                </div>
            </div>
            @include("layouts.components.footer")

        </div>
    </div>
@endsection
