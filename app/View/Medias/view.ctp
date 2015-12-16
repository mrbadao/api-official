<?php //var_dump($media['Media']['media_name'])?>
<h1><?php echo h($media['Media']['media_name']) ?></h1>
<p><?php echo $this->Html->link($media['Media']['media_link'], $media['Media']['media_link']) ?></p>
<p>Size: <?php echo number_format($media['Media']['media_size']) ?> Kb</p>
<p>Dimension: <?php echo $media['Media']['media_dimension'] ?></p>
<p>Created: <?php echo $media['Media']['created']; ?></p>
<p>Modified: <?php echo $media['Media']['modified']; ?></p>
