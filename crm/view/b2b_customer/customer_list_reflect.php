<?php
include "../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$approve_status = $_POST['approve_status'];
$active_flag = $_POST['active_flag'];
$branch_status = $_POST['branch_status'];

$query = "select * from b2b_registration where 1 ";
if ($active_flag != "") {
    $query .= " and active_flag='$active_flag' ";
}
if ($approve_status != "") {
    if ($approve_status == 'Pending') {
        $query .= " and approval_status='' ";
    } else {
        $query .= " and approval_status='$approve_status' ";
    }
}
$query .= " order by register_id desc";
?>
<div class="row mg_tp_20">
    <div class="col-md-12 no-pad">
        <div class="table-responsive">
            <table class="table table-hover" id="tbl_customer_list" style="margin: 20px 0 !important;">
                <thead>
                    <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Company_Name</th>
                        <th>Contact_Person</th>
                        <th>Mobile</th>
                        <th>Email_ID</th>
                        <th>City</th>
                        <th>State/Country</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 0;
                    $sq_customer = mysqlQuery($query);
                    while ($row_customer = mysqli_fetch_assoc($sq_customer)) {
                        if ($row_customer['approval_status'] == "Rejected") {
                            $bg = "danger";
                            $icon = 'fa-times';
                        } else if ($row_customer['approval_status'] == "Approved") {
                            $bg = "success";
                            $icon = 'fa-check';
                        } else {
                            $bg = '';
                            $icon = 'fa-check-square-o';
                        }

                        $sq_city = mysqli_fetch_assoc(mysqlQuery("select city_name from city_master where city_id='$row_customer[city]'"));

                        $color = ($row_customer['approval_status'] == "Rejected") ? 'btn-danger' : 'btn-info';
                        $sq_state = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$row_customer[state]'"));
                        $country_name = ($row_customer['state'] != '0') ? $sq_state['state_name'] : 'NA';
                    ?>
                        <tr class="<?= $bg ?>">
                            <td><?= ++$count ?></td>
                            <td><?= $row_customer['company_name'] ?></td>
                            <td><?= $row_customer['cp_first_name'] . ' ' . $row_customer['cp_last_name']  ?></td>
                            <td><?= $row_customer['mobile_no'] ?></td>
                            <td><?= $row_customer['email_id'] ?></td>
                            <td><?= $sq_city['city_name'] ?></td>
                            <td><?= $country_name ?></td>
                            <td style="white-space: nowrap;">
                                <button class="btn <?= $color ?> btn-sm" id="updates-<?= $row_customer['register_id'] ?>" onclick="customer_update_modal(<?= $row_customer['register_id'] ?>)" title="Update status" data-toggle="tooltip"><i class="fa <?= $icon ?>" aria-hidden="true"></i></button>
                                <button class="btn <?= $color ?> btn-sm" id="updatea-<?= $row_customer['register_id'] ?>" onclick="b2b_update_modal(<?= $row_customer['register_id'] ?>)" title="Update Details" data-toggle="tooltip"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                <button class="btn btn-info btn-sm" id="view-<?= $row_customer['register_id'] ?>" onclick="customer_display_modal(<?= $row_customer['register_id'] ?>)" title="View Details" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-danger btn-sm" id="delete-<?= $row_customer['register_id'] ?>" onclick="delete_customer(<?= $row_customer['register_id'] ?>)" title="Delete Agent" id="delete" data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="div_customer_update_modal"></div>
<script>
    $('#tbl_customer_list').dataTable({
        "pagingType": "full_numbers"
    });

    function customer_update_modal(customer_id) {

        $('#updates-' + customer_id).prop('disabled', true);
        $('#updates-' + customer_id).button('loading');
        $.post('customer_update_modal.php', {
            register_id: customer_id
        }, function(data) {
            $('#div_customer_update_modal').html(data);
            $('#updates-' + customer_id).prop('disabled', false);
            $('#updates-' + customer_id).button('reset');
        })
    }

    function customer_display_modal(customer_id) {

        $('#view-' + customer_id).prop('disabled', true);
        $('#view-' + customer_id).button('loading');
        $.post('view/index.php', {
            register_id: customer_id
        }, function(data) {
            $('#div_customer_update_modal').html(data);
            $('#view-' + customer_id).prop('disabled', false);
            $('#view-' + customer_id).button('reset');
        })
    }

    function delete_customer(customer_id) {
        var base_url = $('#base_url').val();
        $("#vi_confirm_box").vi_confirm_box({
            callback: function(result) {
                if (result == "yes") {
                    $('#delete').button('loading');
                    $.ajax({
                        type: 'post',
                        url: base_url + 'controller/b2b_customer/customer_delete.php',
                        data: {
                            register_id: customer_id
                        },
                        success: function(result) {
                            msg_alert(result);
                            $('#delete').button('reset');
                            customer_list_reflect();
                        }
                    });
                }
            }
        });
    }

    function b2b_update_modal(register_id) {
        $('#updatea-' + register_id).prop('disabled', true);
        $('#updatea-' + register_id).button('loading');
        $.post('update/index.php', {
            register_id: register_id
        }, function(data) {
            $('#div_customer_update_modal').html(data);
            $('#updatea-' + register_id).prop('disabled', false);
            $('#updatea-' + register_id).button('reset');
        })
    }
</script>