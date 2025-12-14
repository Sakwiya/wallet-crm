<?php
if (!isset($conn)) {
    include 'db_connect.php';
}
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_organization">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="form-group">
                    <label for="name" class="control-label">Organization Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" required value="<?php echo isset($name) ? $name : '' ?>">
                </div>
                <div class="form-group">
                    <label for="description" class="control-label">Description</label>
                    <textarea name="description" id="description" class="form-control form-control-sm" rows="4"><?php echo isset($description) ? $description : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="<?php echo isset($email) ? $email : '' ?>">
                </div>
                <div class="form-group">
                    <label for="phone" class="control-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control form-control-sm" value="<?php echo isset($phone) ? $phone : '' ?>">
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2">Save</button>
                    <button class="btn btn-secondary" type="reset">Clear</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#manage_organization').submit(function(e) {
        e.preventDefault();
        $('input, textarea').removeClass("border-danger");
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_organization',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Organization successfully saved.', "success");
                    setTimeout(function() {
                        location.reload();
                    }, 750);
                } else if (resp == 2) {
                    alert_toast('Organization name already exists.', "error");
                    $('[name="name"]').addClass("border-danger");
                    end_load();
                }
            }
        });
    });
</script>
