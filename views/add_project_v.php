<html>
<head>
	<title>Buat Project</title>
</head>
	<form action ="" method="post">
		<label for="title">Judul Project</label><input type="text" name="title"><br/>
		<label for="category_id">Kategori</label>
		<select name="category_id">
			<option value="1">IT</option>
			<option value="2">Advertising</option>
			<option value="3">Tulisan</option>
		</select>
		<label for="budget">Budget</label><input type="text" name="budget"><br/>
		<label for="date_expired">Batas Bidding</label><br/>
		<label for="tanggal">Tanggal</label>
		<select name="tanggal">
			<?php 
				for($i = 1; $i <= 30; $i++){?>
				<option value="<?php echo $i ?>"><?php echo $i ?></option>
			<?php } ?>
		</select>
		<label for="bulan">Bulan</label>
		<select name="bulan">
			<?php 
				for($j = 1; $j <= 12; $j++){?>
				<option value="<?php echo $j ?>"><?php echo $j ?></option>
			<?php } ?>
		</select>
		<label for="tahun">tahun</label>
		<select name="tahun">
			<?php 
				for($k = 2000; $k <= 2030; $k++){?>
				<option value="<?php echo $k ?>"><?php echo $k ?></option>
			<?php } ?>
		</select><br/>
		<input type="submit" name="submit" value="Buat Project !">
	</form>
</html>