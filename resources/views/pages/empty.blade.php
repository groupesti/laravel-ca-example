@extends("shared.base", ["title" => "Starter"])

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Pages", "title" => "Starter"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>@include("shared.partials.page-title", ["subtitle" => "Pages", "title" => "Starter"])</main>

            @include("shared.partials.footer")
        </div>
        <!-- End Page content -->
    </div>

    @include("shared.partials.customizer")
@endsection

@section("scripts")
@endsection
