<?php
if(!isset($conn)){
    include 'db_connect.php';
}
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_staff">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <b class="text-muted">Personal Information</b>
                        <div class="form-group">
                            <label for="" class="control-label">First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">System Role</label>
                            <select class="form-control form-control-sm select2" name="type" id="type" required>
                                <option value=""></option>
                                <option value="1">Admin</option>
                                <option value="2">Data Entry</option>    
                                <option value="3">Accountant</option>    
                            </select>
                        </div>
                        <div class="form-group">
    <label for="" class="control-label">Organization</label>
    <select class="form-control form-control-sm select2" name="organization_id" id="organization_id" required>
        <option value="0" <?php echo isset($organization_id) && $organization_id == 0 ? 'selected' : '' ?>>MlimiPay (All Organizations)</option>
        <?php 
        $orgs = $conn->query("SELECT * FROM organizations WHERE status = 1 ORDER BY name");
        while($row = $orgs->fetch_assoc()):
        ?>
        <option value="<?php echo $row['id'] ?>" <?php echo isset($organization_id) && $organization_id == $row['id'] ? 'selected' : '' ?>>
            <?php echo $row['name'] ?>
        </option>
        <?php endwhile; ?>
    </select>
    <small class="text-muted">Set to "MlimiPay" to grant access to all organization data</small>
</div>

                    </div>
                    <div class="col-md-6">
                        <b class="text-muted">System Credentials</b>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
                            <small id="#msg"></small>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" <?php echo isset($id) ? "":'required' ?>>
                            <small><i><?php echo isset($id) ? "Leave this blank if you dont want to change user password":'' ?></i></small>
                        </div>
                        <div class="form-group">
                            <label class="label control-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-sm" name="cpass" <?php echo isset($id) ? 'required' : '' ?>>
                            <small id="pass_match" data-status=''></small>
                        </div>
                    </div>
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
    $('[name="password"],[name="cpass"]').keyup(function(){
        var pass = $('[name="password"]').val()
        var cpass = $('[name="cpass"]').val()
        if(cpass == '' ||pass == ''){
            $('#pass_match').attr('data-status','')
        }else{
            if(cpass == pass){
                $('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
            }else{
                $('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
            }
        }
    })
    function displayImg(input,_this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#manage_staff').submit(function(e){
        e.preventDefault()
        $('input').removeClass("border-danger")
        start_load()
        $('#msg').html('')
        if($('#pass_match').attr('data-status') != 1){
            if($("[name='password']").val() !=''){
                $('[name="password"],[name="cpass"]').addClass("border-danger")
                end_load()
                return false;
            }
        }
        $.ajax({
            url:'ajax.php?action=save_user',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast('Data successfully saved.',"success");
                    setTimeout(function(){
                        location.replace('index.php?page=users_list')
                    },750)
                }else if(resp == 2){
                    alert_toast('Email already exist.',"error");
                    $('[name="email"]').addClass("border-danger")
                    end_load()
                }
            }
        })
    })
</script>