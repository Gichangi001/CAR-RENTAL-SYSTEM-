<!-- Update Categories -->
<div class="modal fade fixed-right" id="update_<?php echo $categories['category_id']; ?>" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <div class="text-center">
                    <h6 class="mb-0 text-bold">Update <?php echo $categories['category_name']; ?></h6>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" enctype="multipart/form-data" role="form">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">Category code</label>
                            <input type="hidden" value="<?php echo $categories['category_id']; ?>" required name="category_id" class="form-control">
                            <input type="text" value="<?php echo $categories['category_code']; ?>" required name="category_code" class="form-control">
                        </div>
                        <div class="form-group col-md-8">
                            <label for="">Category name</label>
                            <input type="" required name="category_name" value="<?php echo $categories['category_name']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" name="Update_Categories" class="btn btn-outline-success">Update category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Update Categories -->


<!-- Delete Categories -->
<div class="modal fade" id="delete_<?php echo $categories['category_id']; ?>" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-body text-center text-danger">
                    <i class="fas fa-exclamation-triangle fa-4x"></i><br>
                    <h5>Heads up!, you are about to delete <?php echo $categories['category_name']; ?> details</h5>
                    <p>Are you sure you want to delete this vehicle category details?</p>
                    <!-- Hide This -->
                    <input type="hidden" name="category_id" value="<?php echo $categories['category_id']; ?>">
                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                    <button type="submit" name="Delete_Categories" class="text-center btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Delete -->