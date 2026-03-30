@extends("shared.base", ["title" => "Compact"])

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Layouts", "title" => "Compact"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                @include("shared.partials.page-title", ["subtitle" => "Layouts", "title" => "Compact"])

                <div class="container">
                    <div class="bg-info/10 text-info border-s-3 border-info rounded-md flex items-start gap-3 py-3 px-4 mb-4">
                        <div>
                            <i class="iconify tabler--info-circle text-xl"></i>
                        </div>
                        <div>
                            To use the compact layout, follow this structure: wrap your page title in
                            <code>&lt;div class="container-fluid"&gt;</code>
                            and place your main content inside
                            <code>&lt;div class="container-xl"&gt;</code>
                            . This ensures proper spacing and alignment.
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-lg">Your custom content here</h4>
                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card-->
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
