@extends("shared.base", ["title" => "Preloader"])

@section("html_attribute")
    data-layout="preloader"
@endsection

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Layouts", "title" => "Preloader"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                @include("shared.partials.page-title", ["subtitle" => "Layouts", "title" => "Preloader"])

                <div class="fixed inset-0 z-50 flex items-center justify-center bg-white animate-[preloader_1s_ease_forwards]">
                    <div class="size-8 animate-spin rounded-full border-3 border-primary border-t-transparent"></div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-lg">Your custom content here</h4>
                    </div>
                    <!-- end card-body-->
                </div>
                <!-- end card-->
            </main>

            @include("shared.partials.footer")
        </div>
        <!-- End Page content -->
    </div>

    @include("shared.partials.customizer")
@endsection

@section("scripts")
@endsection
