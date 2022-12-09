<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('albums.store') }}">
    @csrf
    <div class="row">


        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="title" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">Album Name</span>
            </label>
            <!--end::Label-->
            <input required id="title" type="text" class="form-control form-control-solid"
                placeholder="Album Name " name="title" value="" />
        </div>


    </div>
</form>
