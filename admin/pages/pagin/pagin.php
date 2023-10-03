


<?php
	$cur=$_SERVER['QUERY_STRING'];
	if(isset($_GET['pn'])){
		if (strstr($cur,'pn',true)){
			$cur=strstr($cur,'&pn',true);
		}
	}
?>
			
<nav class="app-pagination">
  <ul class="pagination justify-content-center">
		
			<?php
				/*-------first button----------- */
				if(isset($_GET['pn'])){
					if($_GET['pn']>1){
						$p=1;
						echo "<li class='page-item active'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Previous</a></li>";
					}else{
						$p=1;
						echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Previous</a></li>";
						
					}
				}else{
					$p=1;
					echo "<li class='page-item'><a class='page-link'  href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Previous</a></li>";
				}
				
				/*-------previous-----------*/
	
				if(isset($_GET['pn'])){
					if($_GET['pn']>1){
						$p=$_GET['pn']-1;
						echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&laquo;</a></li>";
					}else{
						$p=1;
						echo "<li class='page-item'><a class='page-link'  href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&laquo;</a></li>";
						
					}
				}else{
					$p=1;
						echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&laquo;</a></li>";
				}
			
			?>	
			<li class="page-item"> <a class='page-link'><?php echo (isset($_GET['pn']))?$_GET['pn']:1;?></a></li>  
			<li class="page-item"> <a class='page-link'><?php echo $number_of_page; ?></a> </li>
			
			<?php
			//=------- next button-----------
			if(isset($_GET['pn'])){
				if($_GET['pn']<$number_of_page){
					$p=$_GET['pn']+1;
					echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&raquo;</a></li>";
				}else{
					$p=$number_of_page;
					echo "<li class='page-item'><a class='page-link'  href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&raquo;</a></li>";
					
				}
			}else{
				$p=2;
				echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>&raquo;</a></li>";	
			}
			/*-------last button----------- */
					
			if(isset($_GET['pn'])){
				if($_GET['pn']<$number_of_page){
					$p=$number_of_page;
					echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Next</a></li>";
				}else{
					$p=$number_of_page;
					echo "<li class='page-item'><a class='page-link'  href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Next</a></li>";
				}
			}else{
				$p=$number_of_page;
				echo "<li class='page-item'><a class='page-link' href=' ".$_SERVER['PHP_SELF']."?".$cur."&pn=$p'>Next</a></li>";						
			}	
			?>
  </ul>
</nav>

