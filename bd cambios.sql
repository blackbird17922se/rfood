-------------------------BUID 4.1-----------------------------------------------
ALTER TABLE `venta`
  DROP `id_mesero`,
  DROP `id_coc_lider`
  DROP `id_mesa`;

ALTER TABLE `pedido` DROP `id_cajero`;

------------------------done
ALTER TABLE `mesa` ADD `disponible` BOOLEAN NOT NULL DEFAULT TRUE AFTER `nom`;
ALTER TABLE `venta` ADD `id_mesa` INT(3) NOT NULL AFTER `id_orden`;