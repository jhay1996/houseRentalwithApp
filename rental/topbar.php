  <style>
   .logo {

   font-size: 20px;
   background: white;
   padding: 7px 11px;
   border-radius: 50% 50%;
   color: black;
   }
</style>

<script>
   $('#manage_my_account').click(function(){
     uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
   })
</script>






<style>
   
</style>
