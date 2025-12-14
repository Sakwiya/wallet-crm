<?php include 'db_connect.php' ?>

<div class="col-lg-12">
  <div class="card card-outline card-info">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-sm table-hover w-100" id="savingSchemesTable">
        <thead class="thead-light">
          <tr class="text-center">
            <th>#</th>
            <th>Scheme Name</th>
            <th>Scheme Type</th>
            <th>Target Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          $qry = $conn->query("SELECT * FROM saving_schemes ORDER BY id DESC");
          while($row = $qry->fetch_assoc()):
            $status = strtoupper($row['status']);
            $badge = $status === 'ACTIVE' ? 'badge-success' : 'badge-secondary';
          ?>
          <tr>
            <td class="text-center"><?php echo $i++ ?></td>
            <td><?php echo ucwords(strtolower($row['scheme_name'])) ?></td>
            <td><?php echo ucwords(strtolower($row['scheme_type'])) ?></td>
            <td>K <?php echo number_format($row['target_savings_amount'], 2) ?></td>
            <td class="text-center">
              <span class="badge <?php echo $badge ?>"><?php echo ucfirst(strtolower($status)) ?></span>
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                Action
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item view_account" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="./?page=edit_scheme&id=<?php echo $row['id'] ?>">Edit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item toggle_status" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-status="<?php echo $status ?>">
                  <?php echo $status === 'ACTIVE' ? 'Suspend' : 'Activate' ?>
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables & SweetAlert for Status Toggle -->
<script>
$(document).ready(function(){
  $('#savingSchemesTable').DataTable({
    dom: 'Bfrtip',
    responsive: true,
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100, 200],
    buttons: ['copy', 'excel', 'pdf', 'print']
  });
});

$(document).on('click', '.toggle_status', function () {
  const id = $(this).data('id');
  const currentStatus = $(this).data('status');
  const newStatus = currentStatus === 'ACTIVE' ? 'SUSPENDED' : 'ACTIVE';

  Swal.fire({
    title: `${newStatus} Scheme?`,
    text: `Do you want to change the status to ${newStatus}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: `Yes, ${newStatus.toLowerCase()} it!`,
    cancelButtonText: 'Cancel',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'ajax.php?action=toggle_scheme_status',
        method: 'POST',
        data: { id: id, status: newStatus },
        success: function (resp) {
          if (resp == 1) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: `Scheme has been ${newStatus.toLowerCase()}ed.`,
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Failed',
              text: 'Failed to update scheme status.'
            });
          }
        }
      });
    }
  });
});
</script>
