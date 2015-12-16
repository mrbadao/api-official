<?php var_dump($medias)?>
<h1>Medias</h1>
<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Created</th>
		<th>Modified</th>
	</tr>

	<?php foreach ($medias as $media): ?>
		<tr>
			<td><?php echo $media['Media']['media_id']; ?></td>
			<td>
				<?php echo $this->Html->link($media['Media']['media_name'],
						array('controller' => 'medias', 'action' => 'view', $media['Media']['media_id'])); ?>
			</td>
			<td><?php echo $media['Media']['created']; ?></td>
			<td><?php echo $media['Media']['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	<?php unset($medias); ?>
</table>
