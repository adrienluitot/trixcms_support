<link rel="stylesheet" href="@PluginAssets('css/admin/datatables.min.css')">
<script src="@PluginAssets('js/admin/datatables.min.js')"></script>

<link rel="stylesheet" href="@PluginAssets('css/admin/admin.css')">

<div class="row">
    <div class="col-12" id="support-main">
        <div id="alert-container">
            @if(session('support_flash'))
                <div class="alert alert-{{session('support_flash')['state']}}">
                    {{session('support_flash')['message']}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        @yield('content')
    </div>
</div>