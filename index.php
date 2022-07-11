<?php
  include 'action.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dash Board | </title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />

  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="#">Dashboard Home</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Inventory</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Shop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Records</a>
        </li>
      </ul>
    </div>
    <form class="form-inline" action="/action_page.php">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-primary" type="submit">Search</button>
    </form>
  </nav>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <h3 class="text-center text-dark mt-2">Dashboard to carry out all inventory, sales / orders and record keeping activities.</h3>
        <hr>
        <?php if (isset($_SESSION['response'])) { ?>
        <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <h3 class="text-center text-info">Add Product</h3>
        <form action="action.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $id; ?>">
          <div class="form-group">
               <label>Drink Name</label>
            <input type="text" name="name" value="<?= $name; ?>" class="form-control" placeholder="Enter name" required>
          </div>
          <div class="form-group">
               <label>Drink Price</label>
            <input type="text" name="price" value="<?= $price; ?>" class="form-control" placeholder="Enter Price" required>
          </div>
          <div class="form-group">
               <label>Drink Type</label>
             <select  name="cat" class="form-control" required>
                  
               <option value="<?= $cat; ?>"><?= $cat; ?></option>
              <option value="beers">Beers</option> 
              <option value="wines">Wines</option>  
              <option value="spirits">Spirit</option>
              <option value="mixers">Mixers</option>
             
             </select>
          </div>
          <div class="form-group">
               <label>Drink Size</label>
             <select  name="size" class="form-control" required>
                  
              <option value="<?= $size; ?>"><?= $size; ?></option>
              <option value="100ml">100 ml</option> 
              <option value="250ml">250 ml</option>  
              <option value="300ml">300 ml</option>
              <option value="350ml">350 ml</option>
              <option value="500ml">500 ml</option>
              <option value="750ml">750 ml</option>
              <option value="1ltr">1 Ltr</option>
              <option value="1.5ltr">1.5 Ltrs</option>
              <option value="2.0ltr">2.0 Ltrs</option>
             </select>
          </div>
           <div class="form-group">
             <label>Stock</label>
             <input type="text" name="stock" value="<?= $stock; ?>" class="form-control" placeholder="Enter Stock" required>
          </div> 
                      
            
          <div class="form-group">
            <input type="hidden" name="oldimage" value="<?= $photo; ?>">
            <input type="file" name="image" class="custom-file">
            <img src="<?= $photo; ?>" width="120" class="img-thumbnail">
          </div>
          <div class="form-group">
            <?php
            date_default_timezone_set("Africa/Nairobi");
            $time=date("d.m.Y, h:i:sa");
            ?>
                            
            <input type="hidden" name="entry_time" class="form-control " value="<?php echo $time; ?>">
          </div>
                            
        
          <div class="form-group">
            <?php if ($update == true) { ?>
            <input type="submit" name="update" class="btn btn-success btn-block" value="Update Record">
            <?php } else { ?>
            <input type="submit" name="add" class="btn btn-primary btn-block" value="Add Record">
            <?php } ?>
          </div>
        </form>
      </div>
      <div class="col-md-8">
        <?php
          $query = 'SELECT * FROM tblproduct';
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        <h3 class="text-center text-info">Products In Stock</h3>
        <table class="table table-hover" id="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Name</th>
              <th>Type</th>
              <th>Price</th>
              <th>Size</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['id']; ?></td>
              <td><img src="<?= $row['image']; ?>" width="25"></td>
              <td><?= $row['name']; ?></td>
              <td><?= $row['type']; ?></td>
              <td><?= $row['price']; ?></td>
              <td><?= $row['size']; ?></td>
              <?php $number= $row['stock']; if($number <= 10){echo"<td style='background-color: #f05050d6;font-weight: 700;'> $number</td>";} elseif($number > 10){echo"<td style='background-color: #82e182;font-weight: 700;'> $number</td>"; }?>  
             
              
              <td>
                <a href="details.php?details=<?= $row['id']; ?>" class="badge badge-primary p-2">Details</a> |
                <a href="action.php?delete=<?= $row['id']; ?>" class="badge badge-danger p-2" onclick="return confirm('Do you want delete this record?');">Delete</a> |
                <a href="index.php?edit=<?= $row['id']; ?>" class="badge badge-success p-2">Edit</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#data-table').DataTable({
      paging: true
    });
  });
  </script>
</body>

</html>