<?php 

include "../../../../model/model.php";
$att_id = $_POST['att_id'];
$sq_img = mysqlQuery("select * from fourth_coming_att_images where fourth_id='$att_id'");
$sq_count1 =  mysqli_num_rows(mysqlQuery("select * from fourth_coming_att_images where fourth_id='$att_id'"));
?>

<div class="modal fade profile_box_modal" id="att_view_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
        <?php
        if($sq_count1 >0){ ?>
            <div class="row mg_tp_10">
                <div class="col-md-12">
                    <div class="owl-carousel">
                        <?php
                        while($row_img = mysqli_fetch_assoc($sq_img))
                        {
                            $newUrl = $row_img['upload'];
                            if($newUrl!=""){
                                $newUrl = preg_replace('/(\/+)/','/',$row_img['upload']); 
                                $newUrl_arr = explode('uploads/', $newUrl);
                                $newUrl = BASE_URL.'uploads/'.$newUrl_arr[1];   
                            } 
                            if($newUrl != ''){
                                ?>
                                <div class="item">
                                    <img src="<?= $newUrl ?>" id="images_list" class="img-resposive">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php }
        else echo '<h3>Images not uploaded</h3>'; ?>
        </div>
    </div>
</div>
</div>

<script>
$('#att_view_modal').modal('show');
</script>

<script type="text/javascript">
	$('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        pagination:false,
        autoPlay:true,
        singleItem:true,
        navigation:true,
        navigationText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })
</script>

