<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1>Data List Email</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
          <div class="card-body">
              <table id="table" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Email</th>
                      <th>Company</th>
                      <th>Total Employee</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  $(document).ready( function () {
    $('#table').DataTable({
      "processing": true, 
      "serverSide": true, 
      "order" : [[0,"asc"]],
      "ajax": {
        "url": "<?php echo site_url('Superadmin/subscribe/getData')?>",
        "type": "POST",
        // success: function(data, status, xhr) {
        //     console.log(xhr.responseText);
        // }
      },
      "columns" : [
        {"data": "no"},
        {"data": "email"},
        {"data": "company"},
        {"data": "employee"},
        {"data": "created_at"}
      ]
    });
  });
</script>