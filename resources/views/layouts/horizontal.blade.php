@extends("shared.base", ["title" => "Horizontal"])

@section("html_attribute")
    data-layout="horizontal"
@endsection

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Layouts", "title" => "Horizontal"]) @include("shared.partials.horizontal-nav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                <div class="container-fluid">
                    @include("shared.partials.page-title", ["subtitle" => "Layouts", "title" => "Horizontal"])

                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-lg">Your custom content here</h4>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card -->
                </div>
            </main>

            @include("shared.partials.footer")
        </div>
        <!-- End Page content -->
    </div>

    @include("shared.partials.customizer")
@endsection

@section("scripts")
@endsection
