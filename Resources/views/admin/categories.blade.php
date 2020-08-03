@extends('Plugins.Support_alfiory__930442654.Resources.views.layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-th-list"></i> {{trans('support_alfiory::admin.categories_list')}}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="support-categories-list">
                    <thead>
                        <tr>
                            <th>{{trans('support_alfiory::admin.id')}}</th>
                            <th>{{trans('support_alfiory::admin.name')}}</th>
                            <th>{{trans('support_alfiory::admin.color')}}</th>
                            @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|DASHBOARD_SUPPORT_DELETE_CATEGORY|admin')) <th>{{trans('support_alfiory::admin.manage')}}</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr data-category-id="{{$category->id}}">
                                <td># {{$category->id}}</td>
                                <td class="category-name"><span class="label" style="background: #{{$category->color}};">{{$category->name}}</span></td>
                                <td class="category-color">#{{$category->color}}</td>
                                @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|DASHBOARD_SUPPORT_DELETE_CATEGORY|admin'))
                                    <td>
                                        @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|admin'))
                                            <a class="edit-category" data-toggle="modal" data-target="#edit-category-modal" data-id="{{$category->id}}" data-name="{{$category->name}}" data-color="{{$category->color}}"><i class="fas fa-cog"></i></a>
                                        @endif

                                        @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_CATEGORY|admin')) <a class="delete-category"><i class="fas fa-trash"></i></a> @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(user()->hasPermission('DASHBOARD_SUPPORT_ADD_CATEGORY|admin'))
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus"></i> {{trans('support_alfiory::admin.add_category')}}</h6>
            </div>
            <div class="card-body">
                <form method="post" class="row" id="add-category-form">
                    @csrf

                    <div class="form-group col-md-6">
                        <label>{{trans('support_alfiory::admin.name')}}</label>
                        <input type="text" id="name" class="form-control" placeholder="{{trans('support_alfiory::admin.name')}}">
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{trans('support_alfiory::admin.color')}}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">#</span>
                            </div>
                            <input type="text" id="color" class="form-control" placeholder="{{trans('support_alfiory::admin.color')}}">
                        </div>
                        <small>{{trans("support_alfiory::admin.hex_color_explanation")}}</small>
                    </div>

                    <div class="form-group col-12">
                        <button type="button" class="btn btn-success" id="add-support-category">{{trans('support_alfiory::admin.add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|admin'))
        <div class="modal fade" id="edit-category-modal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{trans("support_alfiory::admin.edit")}} <span id="edit-category-title"></span></h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <input type="hidden" id="edit-category-id">

                    <div class="modal-body">
                        <div class="form-group col-12">
                            <label>{{trans('support_alfiory::admin.name')}}</label>
                            <input type="text" id="edit-category-name" class="form-control" placeholder="{{trans('support_alfiory::admin.name')}}">
                        </div>

                        <div class="form-group col-12">
                            <label>{{trans('support_alfiory::admin.color')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                </div>
                                <input type="text" id="edit-category-color" class="form-control" placeholder="{{trans('support_alfiory::admin.color')}}">
                            </div>
                            <small>{{trans("support_alfiory::admin.hex_color_explanation")}}</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('support_alfiory::admin.cancel')}}</button>
                        <button type="button" class="btn btn-primary" id="update-category">{{trans('support_alfiory::admin.edit')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(user()->hasPermission('DASHBOARD_SUPPORT_ADD_CATEGORY|DASHBOARD_SUPPORT_EDIT_CATEGORY|DASHBOARD_SUPPORT_DELETE_CATEGORY|admin'))
        <script>
            @if(user()->hasPermission('DASHBOARD_SUPPORT_ADD_CATEGORY|admin'))
                // ADD CATEGORY
                $('#add-support-category').on('click', () => {
                    $("#add-support-category").prop('disabled', true);

                    $.ajax({
                        url: '{{route('admin.support_alfiory.add_category')}}',
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                        },
                        data: 'name=' + $('#name').val() + '&color=' + $('#color').val(),
                        success: (data) => {
                            $('#add-category-form .invalid-feedback').remove();
                            $("#support-categories-list tbody").append("<tr data-category-id='"+data.id+"'>" +
                                                                            "<td># " + data.id  + "</td>" +
                                                                            "<td class=\"category-name\"><span class='label' style='background: #" + $('#color').val().replace("#", "") + ";'>" +  $('#name').val() + "</span></td>" +
                                                                            "<td class=\"category-color\">#" + $('#color').val().replace("#", "") + "</td>" +
                                                                            @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|DASHBOARD_SUPPORT_DELETE_CATEGORY|admin'))
                                                                                "<td>" +
                                                                                    @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|admin'))
                                                                                        "<a class='edit-category' data-toggle='modal' data-target='#edit-category-modal' data-name='"+data.id+"' data-name='"+$('#name').val()+"' data-color='"+$('#color').val().replace('#', '')+"'><i class='fas fa-cog'></i></a>" +
                                                                                    @endif
                                                                                    @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_CATEGORY|admin'))
                                                                                        "<a class='delete-category'><i class='fas fa-trash'></i></a>" +
                                                                                    @endif
                                                                                "</td>" +
                                                                            @endif
                                                                        "</tr>");
                            $("#alert-container").html('<div class="alert alert-success" id="support-alert">' +
                                                            data.message +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                                '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                        '</div>');

                            $('#add-category-form input').removeClass("is-invalid").val("");
                            $("#add-support-category").prop('disabled', false);
                        },
                        error: (data) => {
                            let errors = data.responseJSON.errors;
                            $('#add-category-form .invalid-feedback').remove();
                            $('#add-category-form input').removeClass("is-invalid");

                            for(let error in errors) {
                                let input = $('#' + error);
                                input.addClass('is-invalid');
                                input.parent().append('<span class="invalid-feedback">'+errors[error]+'</span>');
                            }
                            $("#add-support-category").prop('disabled', false);
                        }
                    });
                });
            @endif

            @if(user()->hasPermission('DASHBOARD_SUPPORT_DELETE_CATEGORY|admin'))
                // DELETE CATEGORY
                $("#support-categories-list").on('click', "tr:not(.disabled) .delete-category", function () {
                    let catId = $(this).closest("tr").data('category-id');
                    $(this).closest('tr').addClass("disabled");

                    $.ajax({
                        url: '{{route('admin.support_alfiory.delete_category')}}',
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                        },
                        data: 'id=' + catId,
                        success: (data) => {
                            $("#alert-container").html('<div class="alert alert-success" id="support-alert">' +
                                                            data.message +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                                '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                        '</div>');

                            $('tr[data-category-id=' + catId+']').remove();
                        },
                        error: () => {
                            $("#alert-container").html('<div class="alert alert-danger" id="support-alert">' +
                                                            '{{trans("support_alfiory::admin.error_deleting_category")}}' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                                '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                        '</div>');
                        }
                    });
                });
            @endif

            // EDIT CATEGORY
            @if(user()->hasPermission('DASHBOARD_SUPPORT_EDIT_CATEGORY|admin'))
                $('#edit-category-modal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);

                    $("#edit-category-title").text(button.data('name'));
                    $("#edit-category-name").val(button.data('name'));
                    $("#edit-category-color").val(button.data('color'));
                    $("#edit-category-id").val(button.data('id'));
                });

                $("#update-category").on('click', function () {
                    let catId = $("#edit-category-id").val();

                    $.ajax({
                        url: '{{route('admin.support_alfiory.edit_category')}}',
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
                        },
                        data: 'id=' + catId + "&name=" + $("#edit-category-name").val() + "&color=" + $("#edit-category-color").val(),
                        success: (data) => {
                            $('#edit-category-modal .invalid-feedback').remove();
                            $("#support-alert").html('<div class="alert alert-success" id="support-alert">' +
                                                            data.message +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                                '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                        '</div>');

                            $("tr[data-category-id=" + catId + "] .category-name span").text($("#edit-category-name").val()).css('background', "#" + $("#edit-category-color").val().replace("#", ""));
                            $("tr[data-category-id=" + catId + "] .category-color").text("#"+$("#edit-category-color").val().replace("#", ""));
                            $("tr[data-category-id=" + catId + "] .edit-category").data('name', $("#edit-category-name").val()).data('color', $("#edit-category-color").val().replace("#", ""));

                            $('#edit-category-modal').modal('hide');
                        },
                        error: (data) => {
                            let errors = data.responseJSON.errors;
                            $('#edit-category-modal .invalid-feedback').remove();
                            $('#edit-category-modal input').removeClass("is-invalid");

                            for(let error in errors) {
                                let input = $('#edit-category-' + error);
                                input.addClass('is-invalid');
                                input.parent().append('<span class="invalid-feedback">'+errors[error]+'</span>');
                            }
                        }
                    });
                });
            @endif
        </script>
    @endif
@endsection