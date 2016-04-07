 <h2>Please make sure the permission: "chmod -Rf g+w hyerp"</h2>
 <?php
   $output = shell_exec('cd /home/liangyi/hyerp && git pull');
   echo "<pre>$output</pre>";
 ?>
