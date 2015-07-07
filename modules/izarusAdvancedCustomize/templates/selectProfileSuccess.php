<br>
<br>
<p>Seleccione el Perfil con el que desea acceder</p><br>
<br>
<div class="row">
<?php foreach($sf_user->getGuardUser()->getPermissions() AS $p){ ?>
  <div class="col-sm-<?php echo (12/min(4,count($sf_user->getGuardUser()->getPermissions()))); ?> text-center">
    <form method="post" id="profile<?php echo $p->getId();?>">
      <input type="hidden" name="profile_name" value="<?php echo $p->getId();?>" />
      <a href="#" onclick="$('#profile<?php echo $p->getId();?>').submit();return false;">
        <span class="fa-2x"><i class="fa fa-fw fa-user fa-5x"></i></span><br><?php echo $p; ?>
      </a>
    </form>
  </div>
<?php } ?>
</div>
