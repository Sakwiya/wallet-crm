<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h4 class="card-title">Assign Inputs to Saving Scheme</h4>
        </div>
        <div class="card-body">


<form id="add_input_item">
    <div class="row g-2 align-items-end">
        <!-- Saving Scheme -->
        <div class="col-md-3">
            <label for="scheme_id" class="form-label">Select Saving Scheme</label>
            <select name="scheme_id" id="scheme_id" class="form-control form-control-sm select2" required>
                <option value="">-- Select Scheme --</option>
                <?php
                $schemes = $conn->query("SELECT id, scheme_name FROM saving_schemes WHERE status = 'ACTIVE' ORDER BY scheme_name ASC");
                while($row = $schemes->fetch_assoc()):
                ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo ucwords($row['scheme_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Input Type -->
        <div class="col-md-2">
            <label for="input_type" class="form-label">Input Type</label>
            <select name="input_type" class="form-control form-control-sm select2" required>
                <option value="">-- Select Type --</option>
                <option value="Fertilizer">Fertilizer</option>
                <option value="Seeds">Seeds</option>
                <option value="Chemicals">Chemicals</option>
                <option value="Feeds">Feeds</option>
                <option value="Phone">Phone</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Variety / Input Name -->
        <div class="col-md-2">
            <label for="input_name" class="form-label">Variety</label>
            <input type="text" name="input_name" class="form-control form-control-sm" placeholder="e.g. Urea, DK777" required>
        </div>

        <!-- Quantity -->
        <div class="col-md-1">
            <label for="quantity" class="form-label">Qty</label>
            <input type="number" name="quantity" class="form-control form-control-sm" min="1" required>
        </div>

        <!-- Unit Size -->
        <div class="col-md-1">
            <label for="unit_size" class="form-label">Unit Size</label>
            <input type="number" name="unit_size" class="form-control form-control-sm" min="1" placeholder="e.g. 50" required>
        </div>

        <!-- Unit -->
        <div class="col-md-2">
            <label for="unit" class="form-label">Unit</label>
            <select name="unit" class="form-control form-control-sm select2" required>
                <option value="">-- Select Unit --</option>
                <option value="kgs">kgs</option>
                <option value="bags">bags</option>
                <option value="litres">litres</option>
                <option value="packets">packets</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col-md-1">
            <button class="btn btn-primary btn-sm w-100">Add</button>
        </div>
    </div>
</form>




            <hr>
            <h5 class="mt-4">Inputs Assigned (Select Scheme to View)</h5>
            <div id="scheme_inputs_table"></div>
        </div>
    </div>
</div>

<script>
$('#add_input_item').submit(function(e){
    e.preventDefault();
    const scheme_id = $('#scheme_id').val();
    if (!scheme_id) {
        alert_toast("Please select a scheme first", "error");
        return;
    }
    start_load();
    $.ajax({
        url: 'ajax.php?action=save_scheme_input_item',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            if(resp == 1){
                alert_toast("Input added successfully", "success");
                loadInputs(scheme_id);
                $('#add_input_item')[0].reset();
            } else {
                alert_toast("Failed to add input", "error");
            }
            end_load();
        }
    });
});

$('#scheme_id').change(function(){
    const scheme_id = $(this).val();
    if (scheme_id) {
        loadInputs(scheme_id);
    } else {
        $('#scheme_inputs_table').html('');
    }
});

function loadInputs(scheme_id) {
    $.ajax({
        url: 'ajax.php?action=fetch_scheme_inputs',
        method: 'POST',
        data: { scheme_id: scheme_id },
        success: function(resp){
            $('#scheme_inputs_table').html(resp);
        }
    });
}

$(document).on('click', '.delete_input', function(){
    const id = $(this).data('id');
    const scheme_id = $('#scheme_id').val();

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this input?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            start_load();
            $.ajax({
                url: 'ajax.php?action=delete_scheme_input',
                method: 'POST',
                data: { id: id },
                success: function(resp){
                    if(resp == 1){
                        alert_toast("Input deleted successfully", "success");
                        loadInputs(scheme_id);
                    } else {
                        alert_toast("Failed to delete input", "error");
                    }
                    end_load();
                }
            });
        }
    });
});

</script>
