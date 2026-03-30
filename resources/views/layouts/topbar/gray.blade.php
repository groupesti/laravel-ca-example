@extends("shared.base", ["title" => "Topbar Gray"])

@section("html_attribute")
    data-topbar-color="gray"
@endsection

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Layouts", "title" => "Topbar Gray"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                @include("shared.partials.page-title", ["subtitle" => "Layouts", "title" => "Topbar Gray"])

                <div class="container-fluid">
                    <div class="bg-warning/10 text-warning border-s-3 border-warning rounded-md flex items-start gap-3 py-3 px-4 mb-4">
                        <div>
                            <i class="iconify tabler--info-circle text-xl"></i>
                        </div>
                        <div>
                            To enable the gray topbar, add
                            <code>data-topbar-color="gray"</code>
                            to the
                            <code>&lt;html&gt;</code>
                            tag in your layout.
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
