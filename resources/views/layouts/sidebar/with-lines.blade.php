@extends("shared.base", ["title" => "Sidebar with Lines"])

@section("html_attribute")
    class="sidebar-with-line"
@endsection

@section("styles")
@endsection

@section("content")
    <div class="wrapper">
        @include("shared.partials.topbar", ["subtitle" => "Base UI", "title" => "Sidebar with Lines"]) @include("shared.partials.sidenav")

        <!-- Start Page Content here -->
        <div class="page-content">
            <main>
                @include("shared.partials.page-title", ["subtitle" => "Base UI", "title" => "Sidebar with Lines"])

                <div class="container-fluid">
                    <div class="bg-info/10 text-info border-s-3 border-info rounded-md flex items-start gap-3 py-3 px-4 mb-4">
                        <div>
                            <i class="iconify tabler--info-circle text-xl"></i>
                        </div>
                        <div>
                            If you want to display a line with each menu item, add the class
                            <code>"sidebar-with-line"</code>
                            to the
                            <code>&lt;html&gt;</code>
                            tag.
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
