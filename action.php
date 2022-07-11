<?php
	session_start();
	require_once '../config.php';

	$update=false;
	$id="";
	$name="";
	$price="";
	$size="";
	$photo="";
	$entry_time="";
	$cat="";
	$stock="";
	$handler="";


	if(isset($_POST['add'])){
		$name=$_POST['name'];
		$price=$_POST['price'];
		$size=$_POST['size'];
		$entry_time=$_POST['entry_time'];
		$cat=$_POST['cat'];
		$stock=$_POST['stock'];
		$handler=$_POST['handler'];

		$photo=$_FILES['image']['name'];
		$upload="uploads/".$photo;

		$query="INSERT INTO tblproduct(name,price,size,image,entry_time,type,stock)VALUES(?,?,?,?,?,?,?)";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("sssssss",$name,$price,$size,$upload,$entry_time,$cat,$stock);
		$stmt->execute();
		move_uploaded_file($_FILES['image']['tmp_name'], $upload);

		header('location:index.php');
		$_SESSION['response']="Successfully Inserted to the database!";
		$_SESSION['res_type']="success";
	}
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];

		$sql="SELECT image FROM tblproduct WHERE id=?";
		$stmt2=$conn->prepare($sql);
		$stmt2->bind_param("i",$id);
		$stmt2->execute();
		$result2=$stmt2->get_result();
		$row=$result2->fetch_assoc();

		$imagepath=$row['photo'];
		unlink($imagepath);

		$query="DELETE FROM tblproduct WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:index.php');
		$_SESSION['response']="Successfully Deleted!";
		$_SESSION['res_type']="danger";
	}
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];

		$query="SELECT * FROM tblproduct WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['id'];
		$name=$row['name'];
		$price=$row['price'];
		$cat=$row['cat'];
		$size=$row['size'];
		$stock=$row['stock'];
		$photo=$row['image'];
	
		
	
		

		$update=true;
	}
if(isset($_POST['update'])){
		$id=$_POST['id'];
		$name=$_POST['name'];
		$price=$_POST['price'];
		$cat=$_POST['cat'];
		$size=$_POST['size'];
		$stock=$_POST['stock'];
		$oldimage=$_POST['oldimage'];

		if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")){
			$newimage="uploads/".$_FILES['image']['name'];
			unlink($oldimage);
			move_uploaded_file($_FILES['image']['tmp_name'], $newimage);
		}
		else{
			$newimage=$oldimage;
		}
		$query="UPDATE tblproduct SET name=?,price=?,type=?,size=?,stock=?,image=? WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("ssssssi",$name,$price,$cat,$size,$stock,$newimage,$id);
		$stmt->execute();

		$_SESSION['response']="Updated Successfully!";
		$_SESSION['res_type']="primary";
		header('location:index.php');
	}


	if(isset($_GET['details'])){
		$id=$_GET['details'];
		$query="SELECT * FROM tblproduct WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$vid=$row['id'];
		$vname=$row['name'];
		$vprice=$row['price'];
		$vsize=$row['size'];
		$vtime=$row['entry_time'];
		$vcat=$row['cat'];
		$vstock=$row['stock'];
		$vphoto=$row['image'];
	}
?>