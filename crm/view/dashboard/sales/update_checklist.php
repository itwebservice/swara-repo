<?php 
include "../../../model/model.php";
$login_id = $_SESSION['login_id'];
$role = $_SESSION['role'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$branch_status = 'yes';

$booking_id = $_POST['booking_id'];
$tour_type = $_POST['tour_type'];

$new_array = array();
$sq_emp = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$emp_id'"));
if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){
    $class = '';
}else{
    $class = 'hidden';
}

if($tour_type=="Package Tour"){
    $sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from package_tour_booking_master where booking_id='$booking_id' and delete_status='0'"));
    
    if($sq_booking['dest_id']=='0'){
        $sq_package = mysqli_fetch_assoc(mysqlQuery("select * from custom_package_master where package_id='$sq_booking[package_id]'"));
        $dest_id = $sq_package['dest_id'];
    }else{
        $dest_id = $sq_booking['dest_id'];
    }
    
}
if($tour_type=="Group Tour"){
    $sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from  tourwise_traveler_details where id='$booking_id'"));
    $tour_id = $sq_booking['tour_id'];

    $sq_tour = mysqli_fetch_assoc(mysqlQuery("select * from  tour_master where tour_id='$tour_id'"));
    $dest_id = $sq_tour['dest_id'];

}

if($tour_type=="Package Tour" || $tour_type=="Group Tour"){
    $sq_entitiesc = mysqli_num_rows(mysqlQuery("select * from checklist_entities where entity_for='$tour_type' and destination_name = '$dest_id'"));
}else{
    $sq_entitiesc = mysqli_num_rows(mysqlQuery("select * from checklist_entities where entity_for='$tour_type'"));
}
if($tour_type=="Package Tour"){
    $modal_name = "Package Tour Booking";
}else if($tour_type=="Group Tour"){
    $modal_name = "Group Tour Booking";
}else if($tour_type=="Excursion Booking"){
    $modal_name = "Activity Booking";
}else{
    $modal_name = $tour_type;
}
?>

<div class="modal fade profile_box_modal" id="view_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="text-align:center!important;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?= $modal_name.' Checklist' ?></h4>
        </div>
        <div class="modal-body profile_box_padding">
        <?php if($sq_entitiesc != 0){ ?>
            <form id="frm_emquiry_save">
                <input type="hidden" id="booking_id" name="booking_id" value="<?= $booking_id ?>">
                <input type="hidden" id="tour_type" name="tour_type" value="<?= $tour_type ?>">
                <div class="row mg_tp_10"><div class="table-responsive">
                <table class="table table-bordered no-marg pd_bt_51" style="width: 100%;">
                    <thead>
                        <tr class="active table-heading-row">
                            <th></th>
                            <th>S_No.</th>
                            <th>To_Do</th>
                            <?php  if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){?>
                            <th>Assigned_To</th>
                            <?php } ?>
                            <th>Status </th>
                        </tr>
                    </thead>
                    <tbody> 
                    <?php
                    if($tour_type=="Package Tour" || $tour_type=="Group Tour"){
                        $sq_entities = mysqlQuery("select * from checklist_entities where entity_for='$tour_type' and destination_name = '$dest_id'");
                    }else{
                        $sq_entities = mysqlQuery("select * from checklist_entities where entity_for='$tour_type'");
                    }
                    while($row_entity = mysqli_fetch_assoc($sq_entities))
                    { 
                        $count = 1;
                        $sql=mysqlQuery("select * from to_do_entries where entity_id='$row_entity[entity_id]'");
                        
                        while($sq_todo_list=mysqli_fetch_assoc($sql))
                        {
                            $sq = mysqlQuery("select * from checklist_package_tour where booking_id='$booking_id' and tour_type='$tour_type' and entity_id='$sq_todo_list[id]'");
                            while($sql_entry = mysqli_fetch_assoc($sq)){

                                if($sql_entry['assigned_emp_id']=="$emp_id"){  
                            
                                    $sq_chk_count = mysqli_num_rows(mysqlQuery("select * from checklist_package_tour where booking_id='$booking_id' and entity_id='$sq_todo_list[id]' "));
                                
                                    $chk_status = ($sq_chk_count==1) ? "checked" : "";
                                    $sq_chk_count1 = mysqli_num_rows(mysqlQuery("select * from checklist_package_tour where booking_id='$booking_id' and entity_id='$sq_todo_list[id]' and status='Completed'"));
                                    $bg = ($sq_chk_count1==1) ? "success" : "";
                                
                                    ?>
                                    <tr class="<?= $bg ?>">
                                    <td>
                                        <input type="checkbox" id="chk_package_tour_checklist_<?= $sq_todo_list['id'] ?>" name="chk_package_tour_checklist" <?= $chk_status ?> data-entity-id="<?= $sq_todo_list['id'] ?>" disabled>
                                    </td>
                                    <td><?= $count++ ?></td>
                                    <td><?= $sq_todo_list['entity_name'] ?></td>
                                    <!-- <?php  ?> -->
                                    <td class='<?= $class ?>'><select name="assigned_emp_id<?= $sq_todo_list['id'] ?>" id="assigned_emp_id<?= $sq_todo_list['id'] ?>" title="Allocate To" style="width:100%">
                                    <?php 
                            
                                    if($sql_entry['assigned_emp_id']!=""){
                                        $sql_emp = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id = '$sql_entry[assigned_emp_id]'"));     
                                        ?>
                                        <option value="<?= $sql_entry['assigned_emp_id'] ?>"><?= $sql_emp['first_name'] ?></option>
                                            <?php  
                                            if($login_id == "1"){
                                            ?>
                                            <option value="0">Admin</option>
                                            <?php
                                            }
                                            if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){
                                                $query = "select * from emp_master where active_flag='Active' order by first_name desc";
                                                $sq_emp = mysqlQuery($query);
                                                while($row_emp = mysqli_fetch_assoc($sq_emp)){
                                                    ?>
                                                    <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            elseif($branch_status=='yes' && $role=='Branch Admin'){
                                                $query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
                                                $sq_emp = mysqlQuery($query);
                                                while($row_emp = mysqli_fetch_assoc($sq_emp)){
                                                    ?>
                                                    <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                    <?php
                                                }
                                            }else{ 
                                                $query1 = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$emp_id' and active_flag='Active'")); ?>
                                                    <option value="<?= $query1['emp_id'] ?>"><?= $query1['first_name'].' '.$query1['last_name'] ?>
                                                    </option>
                                                <script>
                                                    $('#assigned_emp_id<?= $sq_todo_list['id'] ?>').select2();
                                                </script>
                                            <?php } ?>
                                        <?php 
                                    }
                                    else{ ?> 
                                        <option value="">*Allocate To</option>
                                        <?php  
                                        if($login_id == "1"){
                                        ?>
                                        <option value="0">Admin</option>
                                        <?php
                                        }
                                        if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){
                                            $query = "select * from emp_master where active_flag='Active' order by first_name desc";
                                            $sq_emp = mysqlQuery($query);
                                            while($row_emp = mysqli_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        elseif($branch_status=='yes' && $role=='Branch Admin'){
                                            $query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
                                            $sq_emp = mysqlQuery($query);
                                            while($row_emp = mysqli_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                        }else{ 
                                            $query1 = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$emp_id' and active_flag='Active'")); ?>
                                                <option value="<?= $query1['emp_id'] ?>"><?= $query1['first_name'].' '.$query1['last_name'] ?>
                                                </option>
                                        
                                        <?php } ?>
                                        <?php 
                                    } ?>
                                        </select></td> 
                                        <td><select name="status<?= $sq_todo_list['id'] ?>" id="status<?= $sq_todo_list['id'] ?>" title="Status" style="width:100%">
                                        <?php if($sql_entry['status']!=''){ ?>
                                            <option value="<?= $sql_entry['status'] ?>"><?= $sql_entry['status'] ?></option>
                                            <option value="Not Updated">Not Updated</option>
                                            <option value="Completed">Completed</option>
                                        <?php }else{ ?>
                                            <option value="Not Updated">Not Updated</option>
                                            <option value="Completed">Completed</option>
                                        <?php } ?>
                                        </select></td>
                                        <td class="hidden"><input type="hidden" id="entry_id<?= $sq_todo_list['id'] ?>" name="entry_id<?= $sq_todo_list['id'] ?>" value="<?= $sql_entry['id'] ?>"></td>
                                    </tr>
                                    <script>
                                        $('#assigned_emp_id<?=$sq_todo_list['id'] ?>').select2();
                                    </script>
                                    <?php  
                                    } 
                                }
                            }
                        }
                        ?> 
                    </tbody>		
                </table>
                </div></div>
                <div class="row text-center mg_tp_20">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-success" id="supdate_checklist"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
                    </div>
                </div>
            </form>
        <?php }else{
            echo '<h4>No checklist added!</h4>';
        } ?>
        <div>
    </div>
  </div>
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#view_modal').modal('show');

$(function(){
$('#frm_emquiry_save').validate({
    rules:{
        draft : { draft : true },
    },
    submitHandler:function(form){

    $('#supdate_checklist').prop('disabled',true);
    var booking_id = $('#booking_id').val();
    var branch_admin_id = $('#branch_admin_id1').val();
    var tour_type = $('#tour_type').val();
    var entity_id_arr = new Array();
    var assigned_emp_arr = new Array();
    var ass_emp_id_arr = new Array();
    var status_arr = new Array();
    var entry_id_arr = new Array();
    var check_id_arr = [];
    var count=1;

    $('input[name="chk_package_tour_checklist"]:checked').each(function(){
        var entity_id = $(this).attr('data-entity-id');
        var emp_id = $('#assigned_emp_id'+entity_id).val();
        var status = $('#status'+entity_id).val();
        var entry_id = $('#entry_id'+entity_id).val();
        ass_emp_id_arr.push(emp_id);
        status_arr.push(status);
        entry_id_arr.push(entry_id);
        entity_id_arr.push(entity_id);
        check_id_arr.push('1');
    });
    if(entity_id_arr.length == 0){ 
        error_msg_alert('Atleast select one entity');
        $('#supdate_checklist').prop('disabled',false);
        return false;
    }
    $('input[name="chk_package_tour_checklist"]:not(:checked)').each(function(){
        var entity_id = $(this).attr('data-entity-id');
        var emp_id = $('#assigned_emp_id'+entity_id).val();
        var status = $('#status'+entity_id).val();
        var entry_id = $('#entry_id'+entity_id).val();
        ass_emp_id_arr.push(emp_id);
        status_arr.push(status);
        entry_id_arr.push(entry_id);
        entity_id_arr.push(entity_id);
        check_id_arr.push('0');
    });
    
    var base_url = $('#base_url').val();
    $('#supdate_checklist').button('loading');

    $.ajax({
        type:'post',
        url: base_url+'controller/checklist/tour_checklist_save.php', 
        data:{ booking_id:booking_id,branch_admin_id:branch_admin_id,entity_id_arr:entity_id_arr,tour_type:tour_type,ass_emp_id_arr:ass_emp_id_arr,status_arr:status_arr,entry_id_arr:entry_id_arr,check_id_arr:check_id_arr },
        success:function(result){
            msg_alert(result);
            $('#supdate_checklist').button('reset'); 
            $('#view_modal').modal('hide');  
        }
    });
    }
});

});

</script>