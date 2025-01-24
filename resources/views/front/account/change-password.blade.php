@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
            <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                        </div>
                        <form action="" method="post" name="changePasswordForm" id="changePasswordForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Previous Password</label>
                                        <input type="password" name="previous_password" id="previous_password" placeholder="Previous password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">New Password</label>
                                        <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button id="submit" name="submit" type="submit" class="btn btn-dark">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
           </div>
                  
                
        </div>
    </div>
@endsection
@section('customJs')
<script type="text/javascript">
    $("#changePasswordForm").submit(function (e) {
       e.preventDefault();

       $("button[type='submit']").prop('disabled',true);
       $.ajax({
        url:'{{ route("account.processChangePassword")}}',
        type:'post',
        data: $(this).serializeArray(),
        dataType:'json',
        success: function (response){

            $("button[type='submit']").prop('disabled',false); 
            if (response.status == true){

                // $("#previous_password").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');  

                // $("#new_password").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    
                //  $("#confirm_password").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback'); 

                 window.location.href = '{{ route("account.changePassword")}}';
                }else{
                var errors =response.errors;
                if (errors.previous_password){
                $("#previous_password").addClass('is-invalid').siblings('p').html(errors.previous_password).addClass
                    ('invalid-feedback');  
                }else{
                    $("#previous_password").removeClass('is-invalid').siblings('p').html(errors.previous_password).removeClass
                    ('invalid-feedback');
                }

                if (errors.new_password){
                $("#new_password").addClass('is-invalid').siblings('p').html(errors.new_password).addClass
                    ('invalid-feedback');  
                }else{
                    $("#new_password").removeClass('is-invalid').siblings('p').html('').removeClass
                    ('invalid-feedback');
                }
                if (errors.confirm_password){
                $("#confirm_password").addClass('is-invalid').siblings('p').html(errors.confirm_password).addClass
                    ('invalid-feedback');  
                }else{
                    $("#confirm_password").removeClass('is-invalid').siblings('p').html('').removeClass
                    ('invalid-feedback');
                }

          }

            
        }
    });
});
</script>
@endsection