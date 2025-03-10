<?php
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$branch_status = $_POST['branch_status'];
?>
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>">
<form id="frm_save">
  <div class="modal fade" id="save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">New Advance</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <select id="cust_id" name="cust_id" class="form-control" style="width:100%" title="Customer">
                <?php get_customer_dropdown($role, $branch_admin_id, $branch_status); ?>
              </select>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="payment_amount" name="payment_amount" placeholder="*Amount" title="Amount" onchange="validate_balance(this.id);payment_amount_validate(this.id,'payment_mode','transaction_id','bank_name')">
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="payment_date" name="payment_date" placeholder="*Date" title="Date" value="<?= date('d-m-Y') ?>" onchange="check_valid_date(this.id)">
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <select name="payment_mode" id="payment_mode" class="form-control" title="Mode" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id')">
                <?php get_payment_mode_dropdown(); ?>
              </select>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <input type="text" id="bank_name" name="bank_name" class="form-control bank_suggest" placeholder="Bank Name" title="Bank Name" disabled>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <input type="number" id="transaction_id" name="transaction_id" onchange="validate_balance(this.id)" class="form-control" placeholder="Cheque No/ID" title="Cheque No/ID" disabled>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
              <select name="bank_id" id="bank_id" title="Select Bank" disabled>
                <?php get_bank_dropdown(); ?>
              </select>
            </div>
            <div class="col-md-8 col-sm-6 col-xs-12 mg_bt_10">
              <textarea name="particular" id="particular" rows="1" onchange="validate_address(this.id);" placeholder="*Particular" title="Particular"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-9 mg_bt_20">
              <span style="color: red;line-height: 35px;" data-original-title="" title="" class="note"><?= $txn_feild_note ?></span>
            </div>
          </div>

          <div class="row text-center mg_tp_20">
            <div class="col-xs-12">
              <button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</form>

<script>
  $('#save_modal').modal('show');
  $('#payment_date').datetimepicker({
    timepicker: false,
    format: 'd-m-Y'
  });
  $('#cust_id').select2();

  $('#frm_save').validate({
    rules: {
      cust_id: {
        required: true
      },
      payment_amount: {
        required: true,
        number: true
      },
      payment_date: {
        required: true
      },
      payment_mode: {
        required: true
      },
      bank_id: {
        required: function() {
          if ($('#payment_mode').val() != "Cash") {
            return true;
          } else {
            return false;
          }
        }
      },
      particular: {
        required: true
      },
    },
    submitHandler: function(form) {

      $('#btn_save').prop('disabled', true);
      var base_url = $('#base_url').val();

      var cust_id = $('#cust_id').val();
      var payment_amount = $('#payment_amount').val();
      var payment_date = $('#payment_date').val();
      var payment_mode = $('#payment_mode').val();
      var bank_name = $('#bank_name').val();
      var transaction_id = $('#transaction_id').val();
      var bank_id = $('#bank_id').val();
      var particular = $('#particular').val();
      var branch_admin_id = $('#branch_admin_id1').val();

      if (payment_mode == 'Advance' || payment_mode == 'Credit Note' || payment_mode == 'Credit Card') {
        $('#btn_save').prop('disabled', false);
        error_msg_alert("Please select other payment mode!");
        return false;
      }
      $.post(base_url + 'view/load_data/finance_date_validation.php', {
        check_date: payment_date
      }, function(data) {
        if (data !== 'valid') {
          error_msg_alert("The Payment date does not match between selected Financial year.");
          $('#btn_save').prop('disabled', false);
          return false;
        } else {
          $('#btn_save').button('loading');
          $.ajax({
            type: 'post',
            url: base_url + 'controller/corporate_advance/corporate_save.php',
            data: {
              cust_id: cust_id,
              payment_amount: payment_amount,
              payment_date: payment_date,
              payment_mode: payment_mode,
              bank_name: bank_name,
              transaction_id: transaction_id,
              bank_id: bank_id,
              particular: particular,
              branch_admin_id: branch_admin_id
            },
            success: function(result) {
              msg_alert(result);
              var msg = result.split('--');
              $('#btn_save').prop('disabled', false);
              if (msg[0] != "error") {
                $('#save_modal').modal('hide');
                list_reflect();
              }
            }
          });
        }
      });
    }
  });
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>