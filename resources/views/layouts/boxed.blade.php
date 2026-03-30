@extends("shared.base", ["title" => "Boxed"])

@section("html_attribute")
    data-layout-width="boxed" data-sidenav-size="on-hover"
@endsection

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Layouts", "title" => "Boxed"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                @include("shared.partials.page-title", ["subtitle" => "Layouts", "title" => "Boxed"])

                <div class="container">
                    <div class="bg-info/10 text-info border-s-3 border-info rounded-md flex items-start gap-3 py-3 px-4 mb-4">
                        <div>
                            <i class="iconify tabler--info-circle text-xl"></i>
                        </div>
                        <div>
                            To enable the boxed layout, add
                            <code>data-layout-width="boxed"</code>
                            to the
                            <code>&lt;html&gt;</code>
                            tag. For optimal spacing and usability, we also recommend adding
                            <code>data-sidenav-size="on-hover"</code>
                            to make the sidebar compact while keeping more room for content.
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
