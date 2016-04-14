 <p>*If failed, please check the permission: "chmod -Rf g+w hyerp"</p>
 <br>
 <p>----git pull start----</p>
 <?php
   $output = shell_exec('cd /home/liangyi/hyerp && git pull');
   echo "<pre>$output</pre>";
 ?>
 <p>-----git pull end-----</p>