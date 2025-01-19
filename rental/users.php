<?php ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-12">
            <button style="background-color: #007bff; border-color: #007bff; color: white;" class="btn btn-primary float-right btn-sm" id="new_user">
                <i class="fa fa-plus"></i> New user
            </button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table-striped table-bordered col-md-12">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include 'db_connect.php';
                            $type = array("", "Admin", "Staff", "Alumnus/Alumna");
                            $users = $conn->query("SELECT * FROM users ORDER BY name ASC");
                            $i = 1;
                            while($row = $users->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td><?php echo $row['username'] ?></td>
                                <td><?php echo $type[$row['type']] ?></td>
                                <td>
                                    <center>
                                        <div class="btn-group">
                                            <button style="background-color: #007bff; border-color: #007bff; color: white;" type="button" class="btn btn-primary">Action</button>
                                            <button style="background-color: #007bff; border-color: #007bff; color: white;" type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
                                            </div>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
        transform: scale(1.05);
    }
    .card {
        border-radius: 10px;
        border: none;
    }
    .card-body {
        padding: 30px;
    }
    .table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }
    .thead-dark th {
        background-color: #343a40;
        color: white;
    }
    .container-fluid {
        padding: 30px;
    }
    .dropdown-menu {
        background-color: #f8f9fa;
    }
    .dropdown-item:hover {
        background-color: #007bff;
        color: white;
    }
</style>

<script>
    $('table').dataTable();

    $('#new_user').click(function() {
        uni_modal('New User', 'manage_user.php');
    });

    $('.edit_user').click(function() {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'));
    });

    $('.delete_user').click(function() {
        _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')]);
    });

    function delete_user($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: {id: $id},
            success: function(resp) {
                if(resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
