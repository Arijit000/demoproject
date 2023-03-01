<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Image Management</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css' />
  <link rel='stylesheet'
    href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />

</head>
<header class="p-3 bg-dark text-white">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
      </a>

      @auth
        {{auth()->user()->name}}
        <div class="text-end">
          <a href="{{ route('logout.perform') }}" class="btn btn-outline-light me-2">Logout</a>
        </div>
      @endauth

      @guest
        <div class="text-end">
          <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
          <a href="{{ route('register.perform') }}" class="btn btn-warning">Sign-up</a>
        </div>
      @endguest
    </div>
  </div>
</header>
{{-- add new image modal start --}}
<div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_image_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="my-2">
            <label for="image">Select Image</label>
            <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg," required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_image_btn" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add new image modal end --}}

{{-- edit image modal start --}}
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_image_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="img_id" id="img_id">
        <input type="hidden" name="edit_image" id="edit_image">
        <div class="modal-body p-4 bg-light">
          <div class="my-2">
            <label for="image">Select Image</label>
            <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg," required>
          </div>
          <div class="mt-2" id="images">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_image_btn" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit image modal end --}}

{{-- check ExOh modal start --}}
<div class="modal fade" id="checkExOhModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Check ExOh</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="check_exoh_form">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="my-2">
            <label for="check">Insert Input</label>
            <input type="text" name="check" required id="check">
          </div>
          <div class="my-2">
            <label for="checkfirst">Check First Input</label>
            <input type="text" name="checkfirst" required id="checkfirst">
          </div>
          <div class="my-2">
            <label for="checksecond">Check Second Input</label>
            <input type="text" name="checksecond" required id="checksecond">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="check_ecoh_btn" class="btn btn-success">Check</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- check ExOh modal end --}}

<body class="bg-light">
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-header bg-danger d-flex justify-content-between align-items-center">
            <h3 class="text-light">Manage Images</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addImageModal"><i class="bi-plus-circle me-2"></i>Add New Image</button>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#checkExOhModal">ExOh Check</button>
          </div>
          <div class="card-body" id="show_all_images">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(function() {

      // add new employee ajax request
      $("#add_image_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_image_btn").text('Adding...');
        $.ajax({
          url: "{{ route('store') }}",
          method: 'post',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Added!',
                'Image Added Successfully!',
                'success'
              )
              fetchAllEmployees();
            }
            $("#add_image_btn").text('Add');
            $("#add_image_form")[0].reset();
            $("#addImageModal").modal('hide');
          }
        });
      });

      // edit employee ajax request
      $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
          url: "{{ route('edit') }}",
          method: 'get',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            $("#images").html(
              `<img src="${response.image}" width="100" class="img-fluid img-thumbnail">`);
            $("#img_id").val(response.id);
            $("#edit_image").val(response.image);
          }
        });
      });

      // update employee ajax request
      $("#edit_image_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_image_btn").text('Updating...');
        $.ajax({
          url: "{{ route('update') }}",
          method: 'post',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Updated!',
                'Image Updated Successfully!',
                'success'
              )
              fetchAllEmployees();
            }
            $("#edit_image_btn").text('Update');
            $("#edit_image_form")[0].reset();
            $("#editImageModal").modal('hide');
          }
        });
      });

      // delete employee ajax request
      $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('delete') }}",
              method: 'delete',
              data: {
                id: id,
                _token: csrf
              },
              success: function(response) {
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
                fetchAllEmployees();
              }
            });
          }
        })
      });

      // fetch all employees ajax request
      fetchAllEmployees();

      function fetchAllEmployees() {
        $.ajax({
          url: "{{ route('fetchAll') }}",
          method: 'get',
          success: function(response) {
            $("#show_all_images").html(response);
            $("#image-manager").DataTable();
          }
        });
      }

      // check exoh ajax request
      $("#check_exoh_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#check_exoh_btn").text('Checking...');
        $.ajax({
          url: "{{ route('check') }}",
          method: 'post',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Result!',
                `${response.message}`,
                'success'
              )
              fetchAllEmployees();
            } else {
                Swal.fire(
                'Result!',
                `${response.message}`,
                'error'
              )
              fetchAllEmployees();
            }
            $("#check_exoh_btn").text('Check');
            $("#check_exoh_form")[0].reset();
            $("#checkExOhModal").modal('hide');
          },
          error: function(response) {
            Swal.fire(
                'Result!',
                `${response.message}`,
                'error'
            );
            fetchAllEmployees();
            $("#check_exoh_btn").text('Check');
            $("#check_exoh_form")[0].reset();
            $("#checkExOhModal").modal('hide');
          }
        });
      });
    });
  </script>
</body>

</html>