<h1>Register</h1>

<?php 

echo ($params['name']); 

?>

<form method="post" action="register">
  <div class="form-group">
    <label >Firstname</label>
    <input type="text"  name="firstname" class="form-control" >
  </div>
  <div class="form-group">
    <label >Lastname</label>
    <input type="text"  name="lastname" class="form-control" >
  </div>
  <div class="form-group">
    <label >Email</label>
    <input type="text"  name="email" class="form-control" >
  </div>
  <div class="form-group">
    <label >Password</label>
    <input type="text"  name="password" class="form-control" >
  </div>
  <div class="form-group">
    <label >ConfirmPassword</label>
    <input type="text"  name="ConfirmPassword" class="form-control" >
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>







