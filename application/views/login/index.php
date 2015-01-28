<form role="form" class="form-signin" action='<?php echo base_url();?>login/process' method='post' name='process'>
    <h2 class="form-signin-heading">Connexion</h2>
    <?php if(! is_null($msg)) echo $msg;?>
    <input type="text" name="username" autofocus="" required="" placeholder="Nom d'utilisateur" class="form-control">
    <input type="password" name="password" required="" placeholder="Mot de passe" class="form-control">
    <button type="submit" class="btn btn-lg btn-primary btn-block">Connexion</button>
</form>