<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">

<head>
    @include('Admin.layouts.assets.css')
</head>

<body dir="rtl">
    @include('layouts.loader.loader')

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('Admin.layouts.inc.header')
        @include('Admin.layouts.inc.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('page-title') </h4>



                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('Admin.layouts.inc.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->





    <div class="modal fade" id="profileEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="profileEdit-addOrDelete">

                </div>
                <div class="modal-footer text-center d-flex justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button id="profileEditSave" form="EditForm" type="submit" class="btn btn-success">
                        Save
                        &nbsp;
                        <i class="fa fa-save"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>





    @include('Admin.layouts.inc.setting')
    @include('Admin.layouts.assets.js')


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //-------------------- update profile -----------------------------------
    </script>

    <script>
        $.validate({
            ignore: 'input[type=hidden]',
            lang: "ar",
        });
    </script>

    <script>
        @isset(admin()->user()->id)

            $(document).on('click', '.editProfile', function(e) {
                e.preventDefault()
                var id = $(this).attr('id');

                var url = '{{ route('admins.show', admin()->user()->id) }}';

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        $('.loader-ajax').show()
                    },
                    success: function(data) {
                        $('.loader-ajax').hide()
                        $('#profileEdit-addOrDelete').html(data.html);
                        $('#profileEdit').modal('show')
                        $('#logoOfAdmin').dropify();
                        $.validate({
                            ignore: 'input[type=hidden]',
                            lang: "ar",
                        });

                    },
                    error: function(data) {
                        $('.loader-ajax').hide()
                        $('#profileEdit-addOrDelete').html(
                            '<h3 class="text-center"> No Permisstion   </h3>')
                    }
                });

            });


            $(document).on('submit', 'form#EditForm', function(e) {
                e.preventDefault();
                var myForm = $("#EditForm")[0]
                var formData = new FormData(myForm)
                var url = $('#EditForm').attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('.loader-ajax').show()
                    },
                    complete: function() {


                    },
                    success: function(data) {
                        $('.loader-ajax').hide()
                        $('#profileEdit').modal('hide')
                        $(".header-profile-user").attr("src", data.logo);
                        $(".user-name-text").html(data.name);
                        $(".user-name-sub-text").html(data.business_name);

                        // $('#page-header-user-dropdown').html(data[html]);
                        toastr.success("Done")

                    },
                    error: function(data) {
                        $('.loader-ajax').hide()
                        if (data.status === 500) {
                            $('#profileEdit').modal("hide");

                        }
                        if (data.status === 422) {
                            var errors = $.parseJSON(data.responseText);
                            $.each(errors, function(key, value) {
                                if ($.isPlainObject(value)) {
                                    $.each(value, function(key, value) {
                                        cuteToast({
                                            type: "error", // or 'info', 'error', 'warning'
                                            message: value,
                                            timer: 3000
                                        });

                                    });

                                } else {

                                }
                            });
                        }
                    }, //end error method

                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        @endisset
    </script>




</body>

</html>
