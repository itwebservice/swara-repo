<?php include "../../../../model/model.php"; ?>
<?php 

$city_id = $_GET['city_id'];
?>
<option value="">Hotel</option>
<?php
$sq_hotel = mysqlQuery("select hotel_id, hotel_name from hotel_master where city_id='$city_id' and active_flag!='Inactive'");
while($row_hotel = mysqli_fetch_assoc($sq_hotel))
{
?>
	<option value="<?php echo $row_hotel['hotel_id'] ?>"><?php echo $row_hotel['hotel_name'] ?></option>
<?php	
}

?>