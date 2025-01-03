<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php');
?>



<main id="main" class="main">
     <div class="pagetitle d-flex justify-content-between">
          <div>
               <h1>Manage Users</h1>
               <nav>
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                         <li class="breadcrumb-item"><a href="user_faculty.php">Faculty</a></li>
                         <li class="breadcrumb-item active">Faculty/Staff Approval</li>
                    </ol>
               </nav>
          </div>

     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="m-0 text-dark fw-semibold">Faculty/Staff Approval</h5>

                              <a href="user_faculty.php" class="btn btn-primary">
                                   Back</a>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive mt-3">
                              <table id="example" class="display nowrap" style="width:100%">
                                        <thead>
                                             <tr>
                                                  <th>QR Code</th>
                                                  <th>Full Name</th>
                                                  <th>Department</th>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $query = "SELECT * FROM faculty WHERE (role_as = 'faculty' OR role_as = 'staff') AND status = 'pending' ORDER BY faculty_id ASC";
                                             $query_run = mysqli_query($con, $query);
                                             
                                             if(mysqli_num_rows($query_run))
                                             {
                                                  foreach($query_run as $user)
                                                  {
                                                       ?>
                                             <tr>
                                                  <td>
                                                       <center>
                                                       <?php if($user['profile_image'] != ""): ?>
                                                            <img src="../uploads/profile_images/<?php echo $user['profile_image']; ?>"
                                                                 alt="image" width="120px" height="100px">
                                                            <?php else: ?>
                                                            <img src="uploads/books_img/book_image.jpg" alt=""
                                                                 width="120px" height="100px">
                                                            <?php endif; ?>
                                                       </center>
                                                  </td>

                                                  <td>
                                                       <?=$user['firstname'].' '.$user['middlename'].' '.$user['lastname']?>
                                                  </td>
                                                  <td><?=$user['course'];?></td>




                                                  <td class=" justify-content-center">
                                                       <form action="user_faculty_code.php" method="POST">
                                                            <input type="hidden" name="faculty_id"
                                                                 value="<?= $user['faculty_id']; ?>">
                                                            <input type="submit" name="approved" value="Approve"
                                                                 class="btn btn-success">
                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#denyModal" data-userid="<?= $user['faculty_id']; ?>">Deny</button>
                                                       </form>
                                                  </td>
                                             </tr>

                                             <?php
                                                  }
                                             }
                                                                                    
                                             ?>
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                         <div class="card-footer"></div>
                    </div>
               </div>
          </div>
     </section>
</main>

<!-- Deny Reason Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" aria-labelledby="denyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="denyModalLabel">Deny Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="denyForm" action="user_faculty_code.php" method="POST">
                    <input type="hidden" value="<?= $user['faculty_id']; ?>" name="faculty_id" id="denyUserId">
                    <div class="mb-3">
                        <label for="denyReason" class="form-label">Reason for Denial</label>
                        <textarea class="form-control" id="denyReason" name="deny_reason" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="deny" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
?>
<script>
     document.getElementById('denyReason').addEventListener('input', function () {
        var denyReason = this.value.trim();
        
        var dangerousCharsPattern = /[<>\"\']/;
        
        if (denyReason === "") {
            this.setCustomValidity('Reason cannot be empty or just spaces.');
        } else if (this.value !== denyReason) {
            this.setCustomValidity('Reason cannot start with a space.');
        } else if (dangerousCharsPattern.test(denyReason)) {
            this.setCustomValidity('Reason cannot contain HTML special characters like <, >, ", \'.');
        } else {
            this.setCustomValidity('');
        }
        
        var isValid = denyReason !== "" && this.value === denyReason && !dangerousCharsPattern.test(denyReason);
        this.classList.toggle('is-invalid', !isValid);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
     if (!$.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
    }

     // Handle Deny button click to set user_id in the modal form
     document.querySelectorAll('button[data-bs-target="#denyModal"]').forEach(button => {
          button.addEventListener('click', function () {
               let userId = this.getAttribute('data-userid');
               document.getElementById('denyUserId').value = userId;
          });
     });
});
</script>