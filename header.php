<?php
	if (isset($pub_subaction))
		$link = $pub_subaction;
	else
		$link = "stats";
?>

<table width="60%">
	<tbody>
		<tr align="center">
			<td class="c" onclick="window.location = 'index.php?action=eXpedition&subaction=stats';"><?php if($link == 'stats'){echo ' <a>Mes statistiques</a>'; }else{echo '<a style="cursor:pointer"><font color="lime">Mes statistiques</font></a>';} ?></td>
			<td class="c" onclick="window.location = 'index.php?action=eXpedition&subaction=global';"><?php if($link == 'global'){echo ' <a>Satistiques du serveur</a>'; }else{echo '<a style="cursor:pointer"><font color="lime">Satistiques du serveur</font></a>';} ?></td>
			<td class="c" onclick="window.location = 'index.php?action=eXpedition&subaction=hof';"><?php if($link == 'hof'){echo ' <a>HOF eXpéditions</a>'; }else{echo '<a style="cursor:pointer"><font color="lime">HOF eXpéditions</font></a>';} ?></td>
			<td class="c" onclick="window.location = 'index.php?action=eXpedition&subaction=detail';"><?php if($link == 'detail'){echo ' <a>Détail de mes eXpéditions</a>'; }else{echo '<a style="cursor:pointer"><font color="lime">Détail de mes eXpéditions</font></a>';} ?></td>
			<td class="c" onclick="window.location = 'index.php?action=eXpedition&subaction=detailAll';"><?php if($link == 'details'){echo ' <a>Détail des eXpéditions du serveur</a>'; }else{echo '<a style="cursor:pointer"><font color="lime">Détail des eXpéditions du serveur</font></a>';} ?></td>
		</tr>
	</tbody>
</table>
