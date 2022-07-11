<?php
	session_start();
	include 'config.php';

	$update=false;
	$id="";
	$name="";
	$price="";
	$size="";
	$photo="";

	if(isset($_POST['add'])){
		$name=$_POST['name'];
		$price=$_POST['price'];
		$size=$_POST['size'];

		$photo=$_FILES['image']['name'];
		$upload="uploads/".$photo;

		$query="INSERT INTO tblproduct(name,price,size,image)VALUES(?,?,?,?)";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("ssss",$name,$price,$size,$upload);
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
		$size=$row['size'];
		$photo=$row['image'];

		$update=true;
	}
	if(isset($_POST['update'])){
		$id=$_POST['id'];
		$name=$_POST['name'];
		$price=$_POST['price'];
		$size=$_POST['size'];
		$oldimage=$_POST['oldimage'];

		if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")){
			$newimage="uploads/".$_FILES['image']['name'];
			unlink($oldimage);
			move_uploaded_file($_FILES['image']['tmp_name'], $newimage);
		}
		else{
			$newimage=$oldimage;
		}
		$query="UPDATE tblproduct SET name=?,price=?,size=?,image=? WHERE id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("ssssi",$name,$price,$size,$newimage,$id);
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
		$vphoto=$row['image'];
	}
?>