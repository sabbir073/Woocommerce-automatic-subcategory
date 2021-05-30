<?php

$showsave = 'Please Select Your Menu';
if(isset($_POST['automaticsubmit'])){
    $menu_value = intval($_POST['slct']);
    update_option('automatic_menu_id',$menu_value);
    $showsave = "Saved! Please take a look in website.";
}



$select_box = '';
$locations = get_terms('nav_menu');
foreach($locations as $location){
  	$menu_name = $location->name;
    $menu_id = $location->term_id;
	$select_box .= '<option value="'.$menu_id.'">'.$menu_name.'</option>';
}
?>

<center>
<div class="automatic-title">
    <span class="automatictitle">Welcome To Automatic Subcategories to Menu</span>
</div>
<form action="" method="post">
<div class="select-box">
<span class="select-span"><?php echo $showsave;?></span>
<div class="select">

  <select name="slct" id="slct">
    <option selected disabled>Choose an option</option>
    <?php echo $select_box;?>
  </select>
  
</div>
</div>
<button type="submit" name="automaticsubmit" class="amicritas-button">Submit</button>
</form>
</center>